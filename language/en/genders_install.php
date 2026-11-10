<?php
/**
 *
 * Genders extension for the phpBB Forum Software package
 *
 * @copyright (c) 2026, phpBB Modders, https://www.phpbbmodders.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'GENDERS_NOT_ENABLEABLE'	=> 'Genders could not be enabled. It requires phpBB 3.3.19 or later and PHP 8.1 or later.',
	'GENDERS_DISABLE_OLD'		=> 'Disable the old “%s” extension first. Keep its data; do not delete it.',
));
