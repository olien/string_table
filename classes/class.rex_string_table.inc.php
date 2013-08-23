<?php

class rex_string_table {
	protected static $stringTable = array();
	protected static $stringTableKeys = array();
	protected static $stringTableValues = array();

	public static function init() {
		global $REX;
		
		$sql = new rex_sql();
		$sql->setQuery('SELECT keyname, value_' . $REX['CUR_CLANG'] . ' FROM '. $REX['TABLE_PREFIX'] . 'string_table');
		$rows = $sql->getRows();

		for ($i = 0; $i < $rows; $i ++) {
			$key = $sql->getValue('keyname');
			$value = nl2br($sql->getValue('value_' . $REX['CUR_CLANG']));

			self::$stringTable[$key] = $value;
			self::$stringTableKeys[] = $REX['ADDON']['string_table']['settings']['key_start_token'] . $key . $REX['ADDON']['string_table']['settings']['key_end_token'];
			self::$stringTableValues[] = $value;

			$sql->next();
		}
	}

	public static function replace($params) {
		$content = $params['subject'];
		return str_replace(self::$stringTableKeys, self::$stringTableValues, $content);
	}

	public static function getString($key) {
		if (isset(self::$stringTable[$key]) && !empty(self::$stringTable[$key])) {
			return self::$stringTable[$key];
		} else {
			return '[' . $key . ']';
		}
	}

	public static function keyExists($key) {
		return array_key_exists($key, self::$stringTable);
	}

	public static function keyEmpty($key) {
		return empty(self::$stringTable[$key]);
	}

	public static function getStringCount() {
		return count(self::$stringTable);
	}
}

