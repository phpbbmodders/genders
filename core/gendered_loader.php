<?php
/**
 *
 * Genders extension for the phpBB Forum Software package
 *
 * @copyright (c) 2026, phpBB Modders, https://www.phpbbmodders.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbmodders\genders\core;

use phpbb\language\language_file_loader;

/**
 * Wraps phpBB's language file loader to apply gender-specific wording.
 *
 * phpBB loads language files throughout a page, and each file overwrites any
 * keys it shares with what is already loaded. Applying the viewer's
 * gender-specific strings once would therefore be undone by the next file that
 * contains the same keys. This loader re-applies them after every file it
 * loads, core or extension, so they stay in place for the whole page.
 */
class gendered_loader extends language_file_loader
{
	/** Folder, inside each language folder of this extension, holding the gender-specific files */
	public const FOLDER = 'gendered';

	/** @var language_file_loader The loader this one wraps */
	private language_file_loader $inner;

	/** @var array Gender-specific strings for the current viewer, applied after every load */
	private array $overrides = [];

	/**
	 * Constructor
	 *
	 * @param string               $phpbb_root_path Path to phpBB's root
	 * @param string               $php_ext         Extension of PHP files
	 * @param language_file_loader $inner           The loader this one wraps
	 */
	public function __construct($phpbb_root_path, $php_ext, language_file_loader $inner)
	{
		parent::__construct($phpbb_root_path, $php_ext);
		$this->inner = $inner;
	}

	/**
	 * Extension manager setter, passed on to the wrapped loader.
	 *
	 * @param \phpbb\extension\manager $extension_manager Extension manager
	 */
	public function set_extension_manager(\phpbb\extension\manager $extension_manager)
	{
		parent::set_extension_manager($extension_manager);
		$this->inner->set_extension_manager($extension_manager);
	}

	/**
	 * Loads a core language file, then re-applies the gender-specific strings.
	 *
	 * @param string       $component Name of the language component
	 * @param string|array $locale    ISO code, or list of ISO codes to fall back through
	 * @param array        $lang      Array reference containing language strings
	 */
	public function load($component, $locale, &$lang)
	{
		$this->inner->load($component, $locale, $lang);
		$this->apply($lang);
	}

	/**
	 * Loads an extension language file, then re-applies the gender-specific strings.
	 *
	 * @param string       $extension Name of the extension
	 * @param string       $component Name of the language component
	 * @param string|array $locale    ISO code, or list of ISO codes to fall back through
	 * @param array        $lang      Array reference containing language strings
	 */
	public function load_extension($extension, $component, $locale, &$lang)
	{
		$this->inner->load_extension($extension, $component, $locale, $lang);
		$this->apply($lang);
	}

	/**
	 * Path of the gender-specific file for a language and gender.
	 *
	 * Only the viewer's own language is used, never a fallback language:
	 * another language's wording would replace text in the wrong language.
	 *
	 * @param string $iso        Language folder name, e.g. "cs"
	 * @param string $gender_key Gender identifier from gender_constants.yml, e.g. "fa-venus"
	 * @return string|null Path to the file, or null when the language has none for that gender
	 */
	public function file_path(string $iso, string $gender_key): string|null
	{
		// Both values only ever come from phpBB and gender_constants.yml; refuse anything that could leave the folder.
		if (!preg_match('/^[a-z0-9_-]+$/i', $iso) || !preg_match('/^[a-z0-9_-]+$/i', $gender_key))
		{
			return null;
		}

		$path = dirname(__DIR__) . '/language/' . $iso . '/' . self::FOLDER . '/' . $gender_key . '.' . $this->php_ext;

		return is_file($path) ? $path : null;
	}

	/**
	 * Start applying the strings from a gender-specific file to every file loaded from now on.
	 *
	 * Strings already loaded are not changed here; the caller then loads the
	 * same file through phpBB's language object so they are.
	 *
	 * @param string $path Path returned by file_path()
	 */
	public function set_overrides(string $path): void
	{
		$this->overrides = $this->read_file($path);
	}

	/**
	 * Merge the gender-specific strings into a language array.
	 *
	 * @param array $lang Array reference containing language strings
	 */
	private function apply(array &$lang): void
	{
		if ($this->overrides)
		{
			$lang = array_merge($lang, $this->overrides);
		}
	}

	/**
	 * Read the strings from a language file without touching any other array.
	 *
	 * @param string $path Path to a phpBB-format language file
	 * @return array Language keys mapped to strings
	 */
	private function read_file(string $path): array
	{
		$lang = [];
		include $path;

		return is_array($lang) ? $lang : [];
	}
}
