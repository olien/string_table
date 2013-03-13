<?php

function string_table_clang_added($params) {
	global $I18N, $REX;

	$id = (int)$params['id'];

	$sql = new rex_sql();
	//$sql->debugsql = true;
	$sql->setQuery('ALTER TABLE `' . $REX['TABLE_PREFIX'] . 'string_table` ADD `value_' . $id . '` TEXT NOT NULL');
}

function string_table_clang_delete($params) {
	global $I18N, $REX;

	$id = (int)$params['id'];
	
	$sql = new rex_sql();
	//$sql->debugsql = true;
	$sql->setQuery('ALTER TABLE `' . $REX['TABLE_PREFIX'] . 'string_table` DROP `value_' . $id . '`');
}

