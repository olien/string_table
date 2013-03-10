<?php

$sql = new rex_sql();
$sql->setQuery('DROP TABLE IF EXISTS `' . $REX['TABLE_PREFIX'] . '1024_strings`');

$REX['ADDON']['install']['string_table'] = 0;

?>
