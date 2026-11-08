<?php
/**
 *
 * Genders extension for the phpBB Forum Software package
 *
 * @copyright (c) 2026, phpBB Modders, https://www.phpbbmodders.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbmodders\genders\event;

use phpbbmodders\genders\core\gendered_loader;
use phpbbmodders\genders\core\genders;
use phpbb\language\language;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Switches the viewer's language strings to their gender-specific wording.
 *
 * When the viewer has chosen a gender and their language ships a file for it
 * (language/<iso>/gendered/<gender identifier>.php), the strings in that file
 * replace the normal ones for the rest of the page. Guests, members with None
 * Specified, and languages without such a file see the normal wording.
 */
class gendered_language_listener implements EventSubscriberInterface
{
	private user $user;
	private language $language;
	private genders $genders;
	private gendered_loader $loader;

	/**
	 * Constructor
	 *
	 * @param user            $user     Current user
	 * @param language        $language Language object
	 * @param genders         $genders  Gender choices
	 * @param gendered_loader $loader   phpBB's language file loader, as wrapped by this extension
	 */
	public function __construct(user $user, language $language, genders $genders, gendered_loader $loader)
	{
		$this->user = $user;
		$this->language = $language;
		$this->genders = $genders;
		$this->loader = $loader;
	}

	/**
	 * @return array Core events mapped to the methods handling them
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.user_setup_after' => 'apply_gendered_strings',
		];
	}

	/**
	 * Load the viewer's gender-specific strings, if their language has any.
	 */
	public function apply_gendered_strings()
	{
		$gender_key = $this->genders->identifier((int) ($this->user->data['user_gender'] ?? 0));

		if ($gender_key === null || !$this->user->lang_name)
		{
			return;
		}

		$path = $this->loader->file_path((string) $this->user->lang_name, $gender_key);

		if ($path === null)
		{
			return;
		}

		// Keep the strings applied to every language file loaded later on this page...
		$this->loader->set_overrides($path);

		// ...and apply them to the files that are already loaded.
		$this->language->add_lang(gendered_loader::FOLDER . '/' . $gender_key, 'phpbbmodders/genders');
	}
}
