<?php
$sql = new rex_sql();
//$sql->debugsql=1;

$sql->setQuery("
	CREATE TABLE IF NOT EXISTS `" . $REX['TABLE_PREFIX'] . "string_table` (
	`id` INT(11) unsigned NOT NULL auto_increment,
	`keyname` VARCHAR(255) NOT NULL,
	`priority` INT(11) NOT NULL,
	PRIMARY KEY ( `id` )
);");

// add lang fields
reset($REX['CLANG']);

while (current($REX['CLANG'])) {
    $sql->setQuery('ALTER TABLE `' . $REX['TABLE_PREFIX'] . 'string_table` ADD `value_' . key($REX['CLANG']) . '` TEXT NOT NULL');
    next($REX['CLANG']);
}

$REX['ADDON']['install']['string_table'] = 1;

