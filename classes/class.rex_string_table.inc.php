<?php

class rex_string_table {
	protected static $stringTable = array();

	public static function init() {
		global $REX;
		
		$sql = new rex_sql();
		$sql->setQuery('SELECT keyname, value_' . $REX['CUR_CLANG'] . ' FROM '. $REX['TABLE_PREFIX'] . 'string_table');
		$rows = $sql->getRows();

		for ($i = 0; $i < $rows; $i ++) {
			self::$stringTable[$sql->getValue('keyname')] = nl2br($sql->getValue('value_' . $REX['CUR_CLANG']));
			$sql->next();
		}
	}

	public static function getString($key) {
		if (self::keyExists($key) && !self::keyEmpty($key)) {
			return self::$stringTable[$key];
		} else {
			return '[' . $key . ']';
		}
	}

	public static function keyExists($key) {
		return array_key_exists($key, self::$stringTable);
	}

	public static function keyEmpty($key) {
		if (self::$stringTable[$key] == '') {
			return true;
		} else {
			return false;
		}
	}

	public static function getStringCount() {
		return count(self::$stringTable);
	}
}

