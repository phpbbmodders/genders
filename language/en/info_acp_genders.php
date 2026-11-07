<?php

if (!defined('IN_PHPBB'))
{
	exit;
}

$lang = array_merge($lang ?? [], [
	'ACP_GENDERS' => 'Genders',
	'ACP_GENDERS_SETTINGS' => 'Settings',
	'GENDERS_SHOW_TEXT' => 'Show gender text before the icon',
	'GENDERS_ICON_SET' => 'Gender icon set',
	'GENDERS_ICON_SET_EXPLAIN' => 'The phpBB 3.0 MOD icon sets apply to Male and Female. Other genders use their current icons.',
	'GENDERS_ICONS_CURRENT' => 'Current icons',
	'GENDERS_ICONS_PROSILVER' => 'MOD prosilver',
	'GENDERS_ICONS_SUBSILVER2' => 'MOD subsilver2',
	'GENDERS_ICONS_FEMALE_MALE' => 'MOD (fe)male-images',
	'GENDERS_ICONS_MINI' => 'MOD mini',
	'GENDERS_ICONS_MINIGENDER' => 'MOD minigender',
	'GENDERS_ICONS_PLANETARY' => 'MOD planetary',
	'GENDERS_ICONS_PROSILVER_ALT' => 'MOD prosilver alternate 1',
	'GENDERS_ICONS_PROSILVER_ALT2' => 'MOD prosilver alternate 2',
	'GENDERS_INVALID_ICON_SET' => 'Select an available gender icon set.',
	'GENDERS_PROFILE_LOCATION' => 'Member profile location',
	'GENDERS_PROFILE_STATISTICS' => 'User Statistics',
	'GENDERS_PROFILE_DETAILS' => 'Main profile details',
	'GENDERS_AVAILABLE' => 'Available gender choices',
	'GENDERS_AVAILABLE_EXPLAIN' => 'Disabled choices remain visible for users who already selected them. None Specified is always available.',
]);
