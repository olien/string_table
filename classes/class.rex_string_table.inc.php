<?php

class rex_string_table {
	protected static $stringTable = array();
	protected static $stringTableKeys = array();
	protected static $stringTableValues = array();

	protected static $curClang = 0;

	public static function init() {
		global $REX;

		self::$curClang = $REX['CUR_CLANG'];
		
		$sql = new rex_sql();
		$sql->setQuery('SELECT * FROM '. $REX['TABLE_PREFIX'] . 'string_table');

		foreach ($REX['CLANG'] as $clangId => $clangName) {
			for ($i = 0; $i < $sql->getRows(); $i ++) {
				$key = $sql->getValue('keyname');
				$value = nl2br($sql->getValue('value_' . $clangId));

				self::$stringTable[$clangId][$key] = $value;
				self::$stringTableKeys[$clangId][] = $REX['ADDON']['string_table']['settings']['key_start_token'] . $key . $REX['ADDON']['string_table']['settings']['key_end_token'];
				self::$stringTableValues[$clangId][] = $value;

				$sql->next();
			}

			$sql->reset();
		}
	}

	public static function replace($params) {
		$content = $params['subject'];

		return str_replace(self::$stringTableKeys[self::$curClang], self::$stringTableValues[self::$curClang], $content);
	}

	public static function getString($key, $fillEmpty = true, $clang = -1) {
		if ($clang == -1) {
			$clang = self::$curClang;
		}
		
		if (isset(self::$stringTable[$clang][$key]) && !empty(self::$stringTable[$clang][$key])) {
			return self::$stringTable[$clang][$key];
		} else {
			if ($fillEmpty) {
				return '<span class="string-table-key">[' . $key . ']</span>';
			} else {
				return '';
			}
		}
	}

	public static function keyExists($key) {
		return array_key_exists($key, self::$stringTable[self::$curClang]);
	}

	public static function keyEmpty($key) {
		return empty(self::$stringTable[self::$curClang][$key]);
	}

	public static function getKeyCount() {
		if (isset(self::$stringTable[self::$curClang])) {
			return count(self::$stringTable[self::$curClang]);
		} else {
			return 0;
		}
	}
}

