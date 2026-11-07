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

class m5_acp_category extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return ['\phpbbmodders\genders\migrations\m4_settings'];
	}

	public function update_data()
	{
		$settings_module = [
			'module_basename' => '\phpbbmodders\genders\acp\settings_module',
			'modes' => ['settings'],
		];

		return [
			['module.remove', ['acp', 'ACP_CAT_DOT_MODS', $settings_module]],
			['module.add', ['acp', 'ACP_CAT_DOT_MODS', 'ACP_GENDERS']],
			['module.add', ['acp', 'ACP_GENDERS', $settings_module]],
		];
	}
}
