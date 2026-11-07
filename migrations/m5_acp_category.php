<?php

namespace rmcgirr83\genders\migrations;

class m5_acp_category extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return ['\rmcgirr83\genders\migrations\m4_settings'];
	}

	public function update_data()
	{
		$settings_module = [
			'module_basename' => '\rmcgirr83\genders\acp\settings_module',
			'modes' => ['settings'],
		];

		return [
			['module.remove', ['acp', 'ACP_CAT_DOT_MODS', $settings_module]],
			['module.add', ['acp', 'ACP_CAT_DOT_MODS', 'ACP_GENDERS']],
			['module.add', ['acp', 'ACP_GENDERS', $settings_module]],
		];
	}
}
