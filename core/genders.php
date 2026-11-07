<?php
/**
*
* @package Genders
* @copyright (c) 2020 Rich McGirr(RMcGirr83)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace rmcgirr83\genders\core;

use phpbb\config\config;
use phpbb\language\language;
use Symfony\Component\HttpFoundation\JsonResponse;

class genders
{
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

	private array $genders;
	private config $config;
	private language $language;

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

	public function choices(): array
	{
		$this->language->add_lang('genders', 'rmcgirr83/genders');

		return array_map(fn ($key) => $this->language->lang(strtoupper($key)), $this->genders);
	}

	public function available_ids(): array
	{
		$allowed = $this->config['genders_available'] ?? '*';

		return $allowed === '*' ? array_keys($this->genders) :
			array_merge([0], array_map('intval', explode(',', $allowed)));
	}

	public function selection(int $requested, int $stored): int
	{
		return in_array($requested, $this->available_ids(), true) ? $requested : $stored;
	}

	public function in_profile_details(): bool
	{
		return !empty($this->config['genders_profile_details']);
	}

	public function asset_url(string $path): string
	{
		return generate_board_url() . '/ext/rmcgirr83/genders/' . $path;
	}

	public function render(int $id, int $size = 12): string
	{
		if (!$id || !isset($this->genders[$id]))
		{
			return '';
		}

		$this->language->add_lang('genders', 'rmcgirr83/genders');
		$key = $this->genders[$id];
		$label = htmlspecialchars($this->language->lang(strtoupper($key)), ENT_QUOTES, 'UTF-8');
		$show_text = !empty($this->config['genders_show_text']);
		$set = $this->config['genders_icon_set'] ?? 'current';

		if (isset(self::ICON_SETS[$set]) && $set !== 'current' && ($id === 1 || $id === 2))
		{
			$file = $id === 1 ? 'icon_gender_m.gif' : 'icon_gender_f.gif';
			$url = htmlspecialchars($this->asset_url("images/legacy/$set/$file"), ENT_QUOTES, 'UTF-8');
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

	public function get_gender($gender_id): JsonResponse
	{
		return new JsonResponse(['gender' => $this->genders[$gender_id] ?? 0]);
	}
}
