<?php

class rex_string_table_utils {
	public static function printLangSelect($params) {
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

	public static function getURLQuery($clang) {
		global $_GET;

		$params = $_GET;
		$params['clang'] = $clang;

		if (isset($params['list']) && isset($params[$params['list'] . '_msg'])) {
			unset($params[$params['list'] . '_msg']);
		}

		return http_build_query($params);
	}


	public static function clangAdded($params) {
		global $I18N, $REX;

		$id = (int)$params['id'];

		$sql = new rex_sql();
		//$sql->debugsql = true;
		$sql->setQuery('ALTER TABLE `' . $REX['TABLE_PREFIX'] . 'string_table` ADD `value_' . $id . '` TEXT NOT NULL');
	}

	public static function clangDeleted($params) {
		global $I18N, $REX;

		$id = (int)$params['id'];
	
		$sql = new rex_sql();
		//$sql->debugsql = true;
		$sql->setQuery('ALTER TABLE `' . $REX['TABLE_PREFIX'] . 'string_table` DROP `value_' . $id . '`');
	}

	public static function initPrioSwitch() {
		global $REX;

		// include main class
		if (!class_exists('rex_prio_switch')) {
			include($REX['INCLUDE_PATH'] . '/addons/string_table/classes/class.rex_prio_switch.inc.php');
		}

		// include extended class for use in this addon
		include($REX['INCLUDE_PATH'] . '/addons/string_table/classes/class.rex_string_table_prio_switch.inc.php');

		// for ajax call: update prio in db if necessary
		rex_string_table_prio_switch::handleAjaxCall('string_table', 'update_string_table_prio', $REX['TABLE_PREFIX'] . 'string_table', 'keyname', true);
	}
}

