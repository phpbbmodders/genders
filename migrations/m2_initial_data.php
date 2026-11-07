<?php
/**
 *
 * Genders extension for the phpBB Forum Software package
 *
 * @copyright (c) 2015 Rich Mcgirr (RMcGirr83)
 * @copyright (c) 2026, phpBB Modders, https://www.phpbbmodders.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbmodders\genders\migrations;

class m2_initial_data extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['genders_version']) && version_compare($this->config['genders_version'], '1.0.0', '>=');
	}

	static public function depends_on()
	{
		return array('\phpbbmodders\genders\migrations\m1_initial_schema');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('genders_version', '1.0.0')),
		);
	}
}
