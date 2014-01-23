<?php
$basedir = dirname(__FILE__);
$info = '';
$warning = '';

$page = rex_request('page', 'string');
$clang = rex_request('clang', 'int', '0');
$params = rex_request('params','string');
$func = rex_request('func','string');
$id = rex_request('id', 'int');

// layout top
include($REX['INCLUDE_PATH'].'/layout/top.php');
?>

<style type="text/css">
#rex-page-string-table .rex-table td {
	line-height: 15px;
}
</style>

<?php
// title
rex_title($REX['ADDON']['name'][$page] . ' <span style="font-size:14px; color:silver;">' . $REX['ADDON']['version'][$page] . '</span>');

// delete view
if($func == 'delete' && $id > 0) {
	$sql = rex_sql::factory();
	//  $sql->debugsql = true;
	$sql->setTable($REX['TABLE_PREFIX'] . 'string_table');
	$sql->setWhere('id='. $id);

	if($sql->delete()) {
		$info = $I18N->msg('string_table_key_deleted');
	} else {
		$warning = $sql->getErrro();
	}
	
	$func = '';
}

// output messages
if ($info != '') {
	echo rex_info($info);
}

if ($warning != '') {
	echo rex_warning($warning);
}

// languages
if (count($REX['CLANG']) > 1) {
	rex_string_table_utils::printLangSelect($params);
}

// rex_list
if ($func == '') {
	$list = rex_list::factory('SELECT * FROM '. $REX['TABLE_PREFIX'] . 'string_table ORDER BY priority', 10000);
	//$list->debug = true;
	if ($REX['USER'] && $REX['USER']->isAdmin()) {
		$list->addTableColumnGroup(array(50, 250, '*', 90, 90));
	} else {
		$list->addTableColumnGroup(array(50, 250, '*', 120));
	}
	$list->addParam("clang", $clang);
	
	$list->removeColumn('id');
	$list->removeColumn('priority');

	reset($REX['CLANG']);

	while (current($REX['CLANG'])) {
		$curKey = key($REX['CLANG']);

		if ($curKey != $clang) {
			$list->removeColumn('value_' . $curKey);
		}

		next($REX['CLANG']);
	}

	$list->setColumnLabel('keyname', $I18N->msg('string_table_keyname'));
	$list->setColumnLabel('value_' . $clang, $I18N->msg('string_table_value'));

	// convert line breaks to <br />
	$list->setColumnFormat('value_' . $clang, 'custom', function($params) {
		return nl2br($params['value']);
	}); 
	
	// icon
	if ($REX['USER'] && $REX['USER']->isAdmin()) {
		$thIcon = '<a class="rex-i-element rex-i-generic-add" href="'. $list->getUrl(array('func' => 'add')) .'"><span class="rex-i-element-text">Ansicht erstellen</span></a>';
	} else {
		$thIcon = '';
	}

	$tdIcon = '<span class="rex-i-element rex-i-generic"><span class="rex-i-element-text">###name###</span></span>';
	$list->addColumn($thIcon, $tdIcon, 0, array('<th class="rex-icon">###VALUE###</th>','<td class="rex-icon">###VALUE###</td>'));
	$list->setColumnParams($thIcon, array('func' => 'edit', 'id' => '###id###'));
	
	// functions
	if ($REX['USER'] && $REX['USER']->isAdmin()) {
		$list->addColumn('function', $I18N->msg('edit'), -1, array('<th colspan="2">###VALUE###</th>','<td>###VALUE###</td>'));
	} else {
		$list->addColumn('function', $I18N->msg('edit'));
	}
	
	$list->setColumnLabel('function', $I18N->msg('string_table_function'));
	$list->setColumnParams('function', array('func' => 'edit', 'id' => '###id###', 'clang' => $clang));
	
	if ($REX['USER'] && $REX['USER']->isAdmin()) {
		$delete = $I18N->msg('deleteCol');
		$list->addColumn($delete, $I18N->msg('delete'), -1, array('','<td>###VALUE###</td>'));
		$list->setColumnParams($delete, array('id' => '###id###', 'func' => 'delete'));
		$list->addLinkAttribute($delete, 'onclick', 'return confirm(\''.$I18N->msg('delete').' ?\')');
	}
	
	$list->show();

	// prio switch
	rex_prio_switch::printSwitch(array($I18N->msg('string_table_prio_mode'), $I18N->msg('string_table_prio_mode_on'), $I18N->msg('string_table_prio_mode_off')));
} elseif ($func == "add" || $func == "edit") {
	// remove delete button for non admins
	if ($REX['USER'] && !$REX['USER']->isAdmin()) {
		rex_register_extension('REX_FORM_CONTROL_FIElDS', function ($params) {
			$params['subject']['delete'] = null;
			return $params['subject'];
		});
	}

	$legend = $I18N->msg('add');

	if ($func == 'edit') {
		$legend = $I18N->msg('edit');
	}
	
	$form = rex_form::factory($REX['TABLE_PREFIX'] . 'string_table', $I18N->msg('string_table_string') . ' ' . $legend, 'id=' . $id, 'post', false, 'rex_form_extended');
	//$form->debug = true;
	$form->addParam('clang', $clang);
	
	if ($func == 'edit') {
		$form->addParam('id', $id);
	} elseif ($func == 'add') {
		$form->addHiddenField('priority', rex_string_table::getKeyCount() + 1);
	}
	
	// key
	if ($REX['USER'] && $REX['USER']->isAdmin()) {
		$field =& $form->addTextField('keyname');
	} else {
		$field =& $form->addReadOnlyField('keyname');
	}

	$field->setLabel($I18N->msg('string_table_keyname'));
	
	// value
	$field =& $form->addTextareaField('value_' . $clang);
	$field->setLabel($I18N->msg('string_table_value'));

	// used for maintaining view when switching langs
	if (!rex_request('current_query', 'string')) {
		$form->addParam('current_query', rex_string_table_utils::getURLQuery($REX['CUR_CLANG']));	
	}
		
	$form->show();
}

include $REX['INCLUDE_PATH'].'/layout/bottom.php';


