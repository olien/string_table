<?php
$mypage = 'string_table'; // only for this file

$REX['ADDON']['rxid'][$mypage] = "1024";
$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['name'][$mypage] = 'String Table';
$REX['ADDON']['perm'][$mypage] = 'string_table[]';
$REX['ADDON']['version'][$mypage] = '1.5.1';
$REX['ADDON']['author'][$mypage] = 'Jan Kristinus, Thomas Blum, RexDude';
$REX['ADDON']['supportpage'][$mypage] = 'forum.redaxo.de';
$REX['ADDON']['table_prefix'][$mypage] = $REX['TABLE_PREFIX'] . $REX['ADDON']['rxid'][$mypage] . '_';
$REX['ADDON']['path'][$mypage] = $REX['INCLUDE_PATH'] . '/addons/' . $mypage;
$REX['PERM'][] = 'string_table[]';

$prefix = $REX['ADDON']['table_prefix'][$mypage];

// includes
include($REX['INCLUDE_PATH'] . '/addons/string_table/settings.inc.php');
include($REX['INCLUDE_PATH'] . '/addons/string_table/classes/class.rex_string_table.inc.php');

// fetch all strings for later usage with getString method
if (!$REX['SETUP']) {
	rex_register_extension('ADDONS_INCLUDED', 'rex_string_table::init');

	if (!$REX['REDAXO'] && $REX['ADDON']['string_table']['settings']['auto_replace']) {
		rex_register_extension('OUTPUT_FILTER', 'rex_string_table::replace');
	}
}

if ($REX['REDAXO']) {
	// append lang file
	$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/' . $mypage . '/lang/');

	// includes
	include($REX['INCLUDE_PATH'] . '/addons/string_table/classes/class.rex_string_table_utils.inc.php');
	include($REX['INCLUDE_PATH'] . '/addons/string_table/classes/class.rex_form_extended.inc.php');

	// register extensions
	rex_register_extension('CLANG_ADDED', 'rex_string_table_utils::clangAdded');
	rex_register_extension('CLANG_DELETED', 'rex_string_table_utils::clangDeleted');

	// init sortable rex list with prio switch
	rex_string_table_utils::initPrioSwitch();
} 

// additional function for retrieving strings
function rex_getString($key, $fillEmpty = true, $clang = -1) {
	return rex_string_table::getString($key, $fillEmpty, $clang);
}
