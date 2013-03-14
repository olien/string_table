<?php

class rex_string_table {
	static $stringTable = array();

	static function getString($key) {
		// fetchStrings() method must be called once and prior to this method call
		// done in config.inc.php
		return self::$stringTable[$key];
	}

	static function fetchStrings() {
		global $REX;
		
		$sql = new rex_sql();
		$sql->setQuery('SELECT keyname, value_' . $REX['CUR_CLANG'] . ' FROM '. $REX['TABLE_PREFIX'] . 'string_table');
		$rows = $sql->getRows();

		for ($i = 0; $i < $rows; $i ++) {
			self::$stringTable[$sql->getValue('keyname')] = nl2br($sql->getValue('value_' . $REX['CUR_CLANG']));
			$sql->next();
		}
	}

	static function keyExists($key) {
		return array_key_exists($key, self::$stringTable);
	}

	static function string_table_languages($params) {
		global $REX, $I18N;
	
		$return = '';
	
		$clang = rex_request('clang', 'int');
		$return .= '
			 <div id="rex-clang" class="rex-toolbar">
			 <div class="rex-toolbar-content">
				 <ul>
					 <li>'.$I18N->msg("languages").' : </li>';
	
		$i = 10;

		foreach($REX['CLANG'] as $key => $val) {
			$i++;
			
			if ($i == 1) {
				$return .= '<li class="rex-navi-first rex-navi-clang-' . $key . '">';
			} else {
				$return .= '<li class="rex-navi-clang-' . $key . '">';
			}
					
			$val = rex_translate($val);
			$class = '';

			if ($key == $clang) {
				$class = ' class="rex-active"';
			}

			$curQuery = rex_post('current_query', 'string', '');

			if ($curQuery != '') {
				$urlQuery = $curQuery;
				
				parse_str($curQuery, $vals);
				$vals['clang'] = $key;
				$urlQuery = http_build_query($vals);
			} else {
				$urlQuery = self::getURLQuery($key);
			}

			$return .= '<a' . $class . ' href="index.php?' . $urlQuery . '">' . $val . '</a>';
			$return .= '</li>';
		}
	
		$return .= '
				 </ul>
			 </div>
			 </div>
		';
	
		return $return;
	}

	static function getURLQuery($clang) {
		global $_GET;

		$params = $_GET;
		$params['clang'] = $clang;
		return http_build_query($params);
	}
}

