<?php
/**
 *
 * Genders extension for the phpBB Forum Software package
 *
 * @copyright (c) 2026, phpBB Modders, https://www.phpbbmodders.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbmodders\genders\acp;

/**
 * ACP module information for the Genders settings page.
 */
class settings_info
{
	/**
	 * Describe the module and its modes for phpBB's module manager.
	 *
	 * @return array
	 */
	public function module()
	{
		return [
			'filename' => '\phpbbmodders\genders\acp\settings_module',
			'title' => 'ACP_GENDERS_SETTINGS',
			'modes' => [
				'settings' => [
					'title' => 'ACP_GENDERS_SETTINGS',
					'auth' => 'ext_phpbbmodders/genders && acl_a_board',
					'cat' => ['ACP_GENDERS'],
				],
			],
		];
	}
}
