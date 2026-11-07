<?php

namespace rmcgirr83\genders\acp;

class settings_info
{
	public function module()
	{
		return [
			'filename' => '\rmcgirr83\genders\acp\settings_module',
			'title' => 'ACP_GENDERS_SETTINGS',
			'modes' => [
				'settings' => [
					'title' => 'ACP_GENDERS_SETTINGS',
					'auth' => 'ext_rmcgirr83/genders && acl_a_board',
					'cat' => ['ACP_GENDERS'],
				],
			],
		];
	}
}
