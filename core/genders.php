<?php
/**
 *
 * Genders extension for the phpBB Forum Software package
 *
 * @copyright (c) 2020 Rich McGirr(RMcGirr83)
 * @copyright (c) 2026, phpBB Modders, https://www.phpbbmodders.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbmodders\genders\core;

use phpbb\config\config;
use phpbb\language\language;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Gender choices, their icons and the board's Genders settings.
 */
class genders
{
	/** Icon set identifiers mapped to their ACP language keys */
	public const ICON_SETS = [
		'current' => 'GENDERS_ICONS_CURRENT',
		'prosilver' => 'GENDERS_ICONS_PROSILVER',
		'subsilver2' => 'GENDERS_ICONS_SUBSILVER2',
		'female-male' => 'GENDERS_ICONS_FEMALE_MALE',
		'mini' => 'GENDERS_ICONS_MINI',
		'minigender' => 'GENDERS_ICONS_MINIGENDER',
		'planetary' => 'GENDERS_ICONS_PLANETARY',
		'prosilver-alt' => 'GENDERS_ICONS_PROSILVER_ALT',
		'prosilver-alt2' => 'GENDERS_ICONS_PROSILVER_ALT2',
	];

	/** Genders the phpBB 3.0 MOD icon sets have images for, mapped to the image file name */
	private const LEGACY_ICONS = [
		'fa-mars' => 'icon_gender_m.gif',
		'fa-venus' => 'icon_gender_f.gif',
	];

	/** @var array Gender ids mapped to their Font Awesome icon names */
	private array $genders;
	private config $config;
	private language $language;

	/**
	 * Constructor
	 *
	 * @param config   $config           Board configuration
	 * @param language $language         Language object
	 * @param array    $gender_constants Icon names mapped to gender ids, from gender_constants.yml
	 */
	public function __construct(
		config $config,
		language $language,
		array $gender_constants
	)
	{
		$this->config = $config;
		$this->language = $language;
		$this->genders = array_flip($gender_constants);
	}

	/**
	 * Every gender, including None Specified, with its translated name.
	 *
	 * @return array Gender ids mapped to names
	 */
	public function choices(): array
	{
		$this->language->add_lang('genders', 'phpbbmodders/genders');

		return array_map(fn ($key) => $this->language->lang(strtoupper($key)), $this->genders);
	}

	/**
	 * Gender ids members may choose. None Specified (0) is always included.
	 *
	 * @return int[]
	 */
	public function available_ids(): array
	{
		$allowed = $this->config['genders_available'] ?? '*';

		return $allowed === '*' ? array_keys($this->genders) :
			array_merge([0], array_map('intval', explode(',', $allowed)));
	}

	/**
	 * The gender to save: the requested one if it is available, otherwise the stored one.
	 *
	 * @param int $requested Gender id submitted by the form
	 * @param int $stored    Gender id currently saved for the user
	 * @return int
	 */
	public function selection(int $requested, int $stored): int
	{
		return in_array($requested, $this->available_ids(), true) ? $requested : $stored;
	}

	/**
	 * Whether member profiles show gender in the main profile details instead of User Statistics.
	 *
	 * @return bool
	 */
	public function in_profile_details(): bool
	{
		return !empty($this->config['genders_profile_details']);
	}

	/**
	 * Absolute URL of a file shipped with this extension.
	 *
	 * @param string $path Path relative to the extension's folder
	 * @return string
	 */
	public function asset_url(string $path): string
	{
		return generate_board_url() . '/ext/phpbbmodders/genders/' . $path;
	}

	/**
	 * HTML for a gender: its icon, with its name first when that setting is on.
	 *
	 * @param int $id   Gender id
	 * @param int $size Icon size in pixels, for Font Awesome icons
	 * @return string Empty for None Specified or an unknown id
	 */
	public function render(int $id, int $size = 12): string
	{
		if (!$id || !isset($this->genders[$id]))
		{
			return '';
		}

		$this->language->add_lang('genders', 'phpbbmodders/genders');
		$key = $this->genders[$id];
		$label = utf8_htmlspecialchars($this->language->lang(strtoupper($key)));
		$show_text = !empty($this->config['genders_show_text']);
		$set = $this->config['genders_icon_set'] ?? 'current';

		if (isset(self::ICON_SETS[$set], self::LEGACY_ICONS[$key]) && $set !== 'current')
		{
			$file = self::LEGACY_ICONS[$key];
			$url = utf8_htmlspecialchars($this->asset_url("images/legacy/$set/$file"));
			$icon = '<img src="' . $url . '" alt="' . ($show_text ? '' : $label) . '">';
		}
		else
		{
			// Font Awesome 6 names the former Intersex symbol mars-and-venus.
			$icon_key = $key === 'fa-intersex' ? $key . ' fa-mars-and-venus' : $key;
			$accessibility = $show_text ? 'aria-hidden="true"' : 'role="img" aria-label="' . $label . '"';
			$icon = '<i class="fa ' . $icon_key . '" style="font-size:' . $size . 'px" title="' . $label . '" ' . $accessibility . '></i>';
		}

		return $show_text ? $label . ' ' . $icon : $icon;
	}

	/**
	 * Controller for the gender_id/{gender_id} route: the icon name of a gender.
	 *
	 * @param int $gender_id Gender id
	 * @return JsonResponse {"gender": icon name, or 0 for an unknown id}
	 */
	public function get_gender($gender_id): JsonResponse
	{
		return new JsonResponse(['gender' => $this->genders[$gender_id] ?? 0]);
	}
}
