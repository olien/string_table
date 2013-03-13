<?php
$sql = new rex_sql();
// $sql->debugsql=1;

$sql->setQuery("
CREATE TABLE IF NOT EXISTS `" . $REX['TABLE_PREFIX'] . "1024_strings` (
`pid` INT(11) unsigned NOT NULL auto_increment,
`id` INT(11) NOT NULL,
`clang` VARCHAR(255) NOT NULL,
`value` TEXT NOT NULL,
`keyname` VARCHAR(255) NOT NULL,
`prior` INT(11) NOT NULL,
`updatedate` int(11) NOT NULL,
PRIMARY KEY ( `pid` )
);");

$REX['ADDON']['install']['string_table'] = 1;

