<?php

$page    = rex_request('page', 'string');

include $REX['INCLUDE_PATH'].'/layout/top.php';

rex_title($REX['ADDON']['name'][$page] . ' <span style="font-size:14px; color:silver;">' . $REX['ADDON']['version'][$page] . '</span>');

$basedir = dirname(__FILE__);

$table = $REX['TABLE_PREFIX'] . '1024_strings';
$prefix_field = '';

$id = rex_request('id', 'int');
if ($id == 0)
	$id = rex_request($prefix_field.'id', 'int');
if ($id == 0)
	$id = rex_request($prefix_field.'pid', 'int');


$clang = rex_request('clang', 'int', '0');
$params = rex_request("params",'string');
$func = rex_request("func",'string');

if (count($REX['CLANG']) > 1) {
	echo rex_string_table::string_table_languages($params);
}

//------------------------------> Liste
if ($func == '')
{
	$list = rex_list::factory('SELECT '.$prefix_field.'pid, 
																		'.$prefix_field.'id,
																		'.$prefix_field.'keyname, 
																		'.$prefix_field.'value,
																		'.$prefix_field.'prior 
													FROM 			'. $table .'
													WHERE			clang = "'.$clang.'"
													ORDER BY	'.$prefix_field.'prior 
											');
	// $list->debug = true;
	$list->addTableColumnGroup(array(40, 250, '*', 153));

	$list->addParam("clang", $clang);
	
	$imgHeader = '<a href="'. $list->getUrl(array('func' => 'add', 'clang' => $clang)) .'"><img src="media/metainfo_plus.gif" /></a>';
	
	$list->addColumn($imgHeader, '###'.$prefix_field.'id###', 0, array('<th class="rex-icon">###VALUE###</th>','<td class="rex-small">###VALUE###</td>'));

	$list->removeColumn($prefix_field.'pid');
	$list->removeColumn($prefix_field.'id');
	$list->removeColumn($prefix_field.'prior');
	
		
	$list->addColumn('function', $I18N->msg('edit'));
	$list->setColumnLabel('function', $I18N->msg('string_table_function'));
	$list->setColumnParams('function', array('func' => 'edit', $prefix_field.'id' => '###'.$prefix_field.'pid###', 'clang' => $clang));
	
	//$list->setColumnSortable($prefix_field.'keyname');
	//$list->setColumnSortable($prefix_field.'value');
	
	$list->setColumnLabel($prefix_field.'keyname', $I18N->msg('string_table_keyname'));
	$list->setColumnLabel($prefix_field.'value', $I18N->msg('string_table_value'));
	
	$list->show();
 
}


if($func == "add" || $func == "edit")
{
	
	$legend = $I18N->msg('add');
	if ($func == 'edit')
		$legend = $I18N->msg('edit');
	
	$form = rex_form::factory($table, $I18N->msg('string_table_string').' '.$legend, $prefix_field.'pid='.$id, 'post', false, 'rex_form_extended');
//	$form->debug = true;
	$form->addParam('clang', $clang);
	
	if($func == 'edit')
	{
		$form->addParam($prefix_field.'pid', $id);
	}
	
	// Sprachabhaengige Felder hinzufuegen
	if($func == 'add')
		$form->setLanguageDependent($prefix_field.'id', $prefix_field.'clang');
		
	
	if ($func == 'add' || ($func == 'edit' && $REX['USER'] && $REX['USER']->isAdmin())) {
		$field =& $form->addTextField($prefix_field . 'keyname');
	} else {
		$field =& $form->addReadOnlyField($prefix_field . 'keyname');
	}


	$field->setLabel($I18N->msg('string_table_keyname'));
	
	$field =& $form->addTextareaField($prefix_field.'value');
	$field->setLabel($I18N->msg('string_table_value'));

	$field =& $form->addPrioField('prior');
	$field->setLabel($I18N->msg('string_table_prior'));
	$field->setLabelField('keyname');
	//$field->setWhereCondition('pid = '. $id);
		
	$form->show();
		
		
}

include $REX['INCLUDE_PATH'].'/layout/bottom.php';
?>

