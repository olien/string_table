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
	
		$out = '';
		$clang = rex_request('clang', 'int');

		$out .= '
			 <div id="rex-clang" class="rex-toolbar">
			 <div class="rex-toolbar-content">
				 <ul>
					 <li>'.$I18N->msg("languages").' : </li>';
	
		$i = 0;
		$stop = false;

		foreach($REX['CLANG'] as $key => $val) {
			$i++;
			
			if ($i == 1) {
				$out .= '<li class="rex-navi-first rex-navi-clang-' . $key . '">';
			} else {
				$out .= '<li class="rex-navi-clang-' . $key . '">';
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

			if (!$REX['USER']->isAdmin() && !$REX['USER']->hasPerm('clang[all]') && !$REX['USER']->hasPerm('clang['. $key .']')) {
				$out .= '<span class="rex-strike">'. $val .'</span>';
				if ($clang == $key) $stop = true;
			} else {
				$out .= '<a' . $class . ' href="index.php?' . $urlQuery . '">' . $val . '</a>';
				$out .= '</li>';
			}
		}
	
		$out .= '
				 </ul>
			 </div>
			 </div>
		';

		echo $out;

		if ($stop) {
			echo '
			<!-- *** OUTPUT OF CLANG-VALIDATE - START *** -->
			'. rex_warning($I18N->msg('string_table_no_lang_perm')) .'
			<!-- *** OUTPUT OF CLANG-VALIDATE - END *** -->
			';
			require $REX['INCLUDE_PATH']."/layout/bottom.php";
			exit;
		}
	}

	static function getURLQuery($clang) {
		global $_GET;

		$params = $_GET;
		$params['clang'] = $clang;
		return http_build_query($params);
	}

	static function updatePrio() {
		global $REX;

		$sql = rex_sql::factory();
		//$sql->debugsql = 1;
		$order = $_POST['order'];

		foreach($order as $prio => $keyname) {
			$sql->setQuery('UPDATE ' . $REX['TABLE_PREFIX'] . 'string_table SET prior = ' . ($prio + 1) . ', updatedate = ' . time() . ' WHERE keyname like "' . $keyname . '"');
		}
	}
}

