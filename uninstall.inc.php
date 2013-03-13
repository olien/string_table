<?php

$sql = new rex_sql();
$sql->setQuery('DROP TABLE IF EXISTS `' . $REX['TABLE_PREFIX'] . 'string_table`');

$REX['ADDON']['install']['string_table'] = 0;

