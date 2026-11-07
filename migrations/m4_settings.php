<?php
/**
 *
 * Genders extension for the phpBB Forum Software package
 *
 * @copyright (c) 2026, phpBB Modders, https://www.phpbbmodders.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbmodders\genders\migrations;

class m4_settings extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return ['\phpbbmodders\genders\migrations\m3_initial_data'];
	}

	public function update_data()
	{
		return [
			['config.add', ['genders_show_text', 0]],
			['config.add', ['genders_icon_set', 'current']],
			['config.add', ['genders_profile_details', 0]],
			['config.add', ['genders_available', '*']],
			['module.add', ['acp', 'ACP_CAT_DOT_MODS', [
				'module_basename' => '\phpbbmodders\genders\acp\settings_module',
				'modes' => ['settings'],
			]]],
		];
	}
}
