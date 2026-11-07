<?php
/**
 *
 * Genders extension for the phpBB Forum Software package
 *
 * @copyright (c) 2020 Rich Mcgirr (RMcGirr83)
 * @copyright (c) 2026, phpBB Modders, https://www.phpbbmodders.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbmodders\genders\migrations;

class m3_initial_data extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return ['\phpbbmodders\genders\migrations\m2_initial_data'];
	}

	public function update_data()
	{
		return [
			['config.remove', ['genders_version']],
		];
	}
}
