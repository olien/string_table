<?php
$mypage = 'string_table'; // only for this file

$REX['ADDON']['rxid'][$mypage] = "1024";
$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['name'][$mypage] = 'String Table';
$REX['ADDON']['perm'][$mypage] = 'string_table[]';
$REX['ADDON']['version'][$mypage] = '1.2.0';
$REX['ADDON']['author'][$mypage] = 'Jan Kristinus, Thomas Blum, RexDude';
$REX['ADDON']['supportpage'][$mypage] = 'forum.redaxo.de';
$REX['ADDON']['table_prefix'][$mypage] = $REX['TABLE_PREFIX'] . $REX['ADDON']['rxid'][$mypage] . '_';
$REX['ADDON']['path'][$mypage] = $REX['INCLUDE_PATH'] . '/addons/' . $mypage;
$REX['PERM'][] = 'string_table[]';

$prefix = $REX['ADDON']['table_prefix'][$mypage];

// includes
include($REX['INCLUDE_PATH'] . '/addons/string_table/classes/class.rex_string_table.inc.php');

// fetch all strings for later usage with getString method
if (!$REX['SETUP']) {
	rex_register_extension('ADDONS_INCLUDED', 'rex_string_table::fetchStrings');
}

if ($REX['REDAXO']) {
	// append lang file
	$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/' . $mypage . '/lang/');

	// includes
	include($REX['INCLUDE_PATH'] . '/addons/string_table/classes/class.rex_prio_switch.inc.php');
	include($REX['INCLUDE_PATH'] . '/addons/string_table/classes/class.rex_form_extended.inc.php');
	include($REX['INCLUDE_PATH'] . '/addons/string_table/extensions/extension_clang.inc.php');

	// register extensions
	rex_register_extension('CLANG_ADDED', 'string_table_clang_added');
	rex_register_extension('CLANG_DELETED', 'string_table_clang_delete');

	// for ajax call: update prio in db if necessary
	rex_prio_switch::handleAjaxCall('update_string_table_prio');
} 

