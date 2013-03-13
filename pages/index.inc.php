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
include $REX['INCLUDE_PATH'].'/layout/top.php';

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
	echo rex_string_table::string_table_languages($params);
}

// rex_list
if ($func == '') {
	$list = rex_list::factory('SELECT * FROM '. $REX['TABLE_PREFIX'] . 'string_table ORDER BY prior');
	//$list->debug = true;
	if ($REX['USER'] && $REX['USER']->isAdmin()) {
		$list->addTableColumnGroup(array(50, 250, '*', 90, 90));
	} else {
		$list->addTableColumnGroup(array(50, 250, '*', 120));
	}
	$list->addParam("clang", $clang);
	
	$list->removeColumn('id');
	$list->removeColumn('prior');
	$list->removeColumn('updatedate');

	for ($i = 0; $i < count($REX['CLANG']); $i++) {
		if ($i != $clang) {
			$list->removeColumn('value_' . $i);
		}
	}

	$list->setColumnLabel('keyname', $I18N->msg('string_table_keyname'));
	$list->setColumnLabel('value_' . $clang, $I18N->msg('string_table_value'));
	
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
} elseif ($func == "add" || $func == "edit") {
	$legend = $I18N->msg('add');

	if ($func == 'edit') {
		$legend = $I18N->msg('edit');
	}
	
	$form = rex_form::factory($REX['TABLE_PREFIX'] . 'string_table', $I18N->msg('string_table_string') . ' ' . $legend, 'id=' . $id, 'post', false, 'rex_form_extended');
	//$form->debug = true;
	$form->addParam('clang', $clang);
	
	if($func == 'edit') {
		$form->addParam('id', $id);
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

	if ($REX['USER'] && $REX['USER']->isAdmin()) {
		$field =& $form->addPrioField('prior');
		$field->setLabel($I18N->msg('string_table_prior'));
		$field->setLabelField('keyname');
	}
		
	$form->show();
}

include $REX['INCLUDE_PATH'].'/layout/bottom.php';


