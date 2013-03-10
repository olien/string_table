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
		$sql->setQuery('SELECT value, keyname FROM '. $REX['ADDON']['table_prefix']['string_table'] .'strings WHERE clang = "' . $REX['CUR_CLANG'] . '"');
		$rows = $sql->getRows();

		for ($i = 0; $i < $rows; $i ++) {
			self::$stringTable[$sql->getValue('keyname')] = nl2br($sql->getValue('value'));
			$sql->next();
		}
	}

	static function keyExists($key) {
		return array_key_exists($key, self::$stringTable);
	}

	static function string_table_languages($params) {
		global $REX, $I18N;
	
		$return = '';
	
		$url = '';
		if (is_array($params))
		{
			foreach ($params as $key => $val)
			{
				$url .= '&amp;'.$key.'='.$val;
			}
		}
	
		$clang = rex_request('clang', 'int');
		$return .= '
			 <div id="rex-clang" class="rex-toolbar">
			 <div class="rex-toolbar-content">
				 <ul>
					 <li>'.$I18N->msg("languages").' : </li>';
	
		$i = 10;
		foreach($REX['CLANG'] as $key => $val)
		{
			$i++;
			
			if($i == 1)
				$return .= '<li class="rex-navi-first rex-navi-clang-'.$key.'">';
			else
				$return .= '<li class="rex-navi-clang-'.$key.'">';
					
			$val = rex_translate($val);
	
			$class = '';
			if ($key == $clang)
				$class = ' class="rex-active"';
			
			$return .= '<a'.$class.' href="index.php?page='.$REX['PAGE'].$url.'&amp;clang='. $key .'">'. $val .'</a>';
		
			$return .= '</li>';
		}
	
		$return .= '
				 </ul>
			 </div>
			 </div>
		';
	
		return $return;
	}
}

?>
