<?php
$basedir = dirname(__FILE__);
$info = '';
$warning = '';

$page = rex_request('page', 'string');
$clang = rex_request('clang', 'int', '0');
$params = rex_request('params','string');
$func = rex_request('func','string');
$id = rex_request('id', 'int');

// layout top
include $REX['INCLUDE_PATH'].'/layout/top.php';

// title
rex_title($REX['ADDON']['name'][$page] . ' <span style="font-size:14px; color:silver;">' . $REX['ADDON']['version'][$page] . '</span>');

// delete view
if($func == 'delete' && $id > 0) {
	$sql = rex_sql::factory();
	//  $sql->debugsql = true;
	$sql->setTable($REX['TABLE_PREFIX'] . 'string_table');
	$sql->setWhere('id='. $id);

	if($sql->delete()) {
		$info = $I18N->msg('string_table_key_deleted');
	} else {
		$warning = $sql->getErrro();
	}
	
	$func = '';
}

// output messages
if ($info != '') {
	echo rex_info($info);
}

if ($warning != '') {
	echo rex_warning($warning);
}

// languages
if (count($REX['CLANG']) > 1) {
	rex_string_table::string_table_languages($params);
}

// rex_list
if ($func == '') {
	$list = rex_list::factory('SELECT * FROM '. $REX['TABLE_PREFIX'] . 'string_table ORDER BY prior');
	//$list->debug = true;
	if ($REX['USER'] && $REX['USER']->isAdmin()) {
		$list->addTableColumnGroup(array(50, 250, '*', 90, 90));
	} else {
		$list->addTableColumnGroup(array(50, 250, '*', 120));
	}
	$list->addParam("clang", $clang);
	
	$list->removeColumn('id');
	$list->removeColumn('prior');
	$list->removeColumn('updatedate');

	reset($REX['CLANG']);

	while (current($REX['CLANG'])) {
		$curKey = key($REX['CLANG']);

		if ($curKey != $clang) {
			$list->removeColumn('value_' . $curKey);
		}

		next($REX['CLANG']);
	}

	$list->setColumnLabel('keyname', $I18N->msg('string_table_keyname'));
	$list->setColumnLabel('value_' . $clang, $I18N->msg('string_table_value'));
	
	// icon
	if ($REX['USER'] && $REX['USER']->isAdmin()) {
		$thIcon = '<a class="rex-i-element rex-i-generic-add" href="'. $list->getUrl(array('func' => 'add')) .'"><span class="rex-i-element-text">Ansicht erstellen</span></a>';
	} else {
		$thIcon = '';
	}

	$tdIcon = '<span class="rex-i-element rex-i-generic"><span class="rex-i-element-text">###name###</span></span>';
	$list->addColumn($thIcon, $tdIcon, 0, array('<th class="rex-icon">###VALUE###</th>','<td class="rex-icon">###VALUE###</td>'));
	$list->setColumnParams($thIcon, array('func' => 'edit', 'id' => '###id###'));
	
	// functions
	if ($REX['USER'] && $REX['USER']->isAdmin()) {
		$list->addColumn('function', $I18N->msg('edit'), -1, array('<th colspan="2">###VALUE###</th>','<td>###VALUE###</td>'));
	} else {
		$list->addColumn('function', $I18N->msg('edit'));
	}
	
	$list->setColumnLabel('function', $I18N->msg('string_table_function'));
	$list->setColumnParams('function', array('func' => 'edit', 'id' => '###id###', 'clang' => $clang));
	
	if ($REX['USER'] && $REX['USER']->isAdmin()) {
		$delete = $I18N->msg('deleteCol');
		$list->addColumn($delete, $I18N->msg('delete'), -1, array('','<td>###VALUE###</td>'));
		$list->setColumnParams($delete, array('id' => '###id###', 'func' => 'delete'));
		$list->addLinkAttribute($delete, 'onclick', 'return confirm(\''.$I18N->msg('delete').' ?\')');
	}
	
	$list->show();
?>

<?php if (isset($REX['USER']) && $REX['USER']->isAdmin() && (OOPlugin::isActivated('be_utilities', 'jquery_ui') || OOPlugin::isActivated('be_style', 'jquery_ui'))) { ?>
<p class="field switch" style="display: none;">
	<span><?php echo $I18N->msg('string_table_prio_mode'); ?></span>
	<label class="cb-enable"><span><?php echo $I18N->msg('string_table_prio_mode_on'); ?></span></label>
	<label class="cb-disable selected"><span><?php echo $I18N->msg('string_table_prio_mode_off'); ?></span></label>
	<input type="checkbox" id="checkbox" class="checkbox" name="field2" />
</p>

<style type="text/css">
.rex-table tbody tr {cursor: auto;}
.rex-table tbody tr.move {cursor: move;}

.switch { float:right; margin-top: 10px; }
.switch > span { float: left; height: 30px; line-height: 29px; margin-right: 7px;}
.switch label { cursor: pointer; }
.switch input { display: none; }

.cb-enable, .cb-disable, .cb-enable span, .cb-disable span { background: url(data:image/gif;base64,R0lGODlhBQAOAeYAAL6+vlyRE4q8Je7u7oC0HZubm0l0EFaKEYqKimKNGJKSklxcXGCUFGmTGfDw8NjY2H2xHdLS0mSTF0JtDXWbKIaGhoKCglJ7E3mqH0tzEnyuIPX19WmfF+np6W2eGm+kGHGmGHZ2dmmZGVuFFd7e3mRkZI2NjYSEhICAgJOyV3mXTXKcH1VVVVGFD12NFVKGEIiIiHGhHI3AJ2FhYYa7HmSaFViOEleCFF6IFnx8fHBwcHp6eo+Pj2pqanJycm5ubnR0dH9/f2xsbHh4eGhoaHmtG3arGnuwHHOpGWecFmKYFGuhF+Xl5YS2I4S5HpSUlIi8H4a5H0+BD5GRkWdnZ4e5JMTExODg4HWmHc/Pz4GyImCQFXOkHX+wIYO2Hqy9lMjIyKysrPLy8nWgIG6bGrzOmbDDj87Yv9Xfw4CxHliHE3CjGHqkIX+lMnyvHW6YHYGeV3inHYa0JXquHFh/IFyCJmWWF1WAFJu2YYKwJIK0I1yPE9XV1ff392ZmZvj4+CH5BAAAAAAALAAAAAAFAA4BAAf/gACCgkyFhQ6IiH+LjI2Oj5CRkpOUlZaXmI59m5t8np4hoaEIpKQKp6dPqqqop1Ovrzyysia1taW4pRW7vL0Wv8DBQcPDOcbGO8nJQ8zMoqFA0dE+1NQ619c/2tpC3d094OBE4+NU5uYl6errLO3tDfDwEPPzUfb2UPn5NPz8Tv//vAgUSKBgQXrzjihUWKRhQyMQISKZOBGERYsfMmZcwpEjh48fk4gUWaNkSSUoUTJYuTKAy5cwbciUeaCmzZsvcuZswZOnlJ8/JwgVuqhDFit/SFjJQmIAGCaI+DzY0OdPBDGMIlRdxGfrn66MwHL1KvYr2bNh0Y5Ny3atW7Nt/+G+ncqIBAlGDvgMYNSBzxUHiwZcebDoQYEKfwCEyFHhwQ4EpwpYQGWBB6oclk9hnrXDlokQuELAKAWkl49eOoKlBvaDWJAex3IQUbbDT7Mhfp6VkAZkRjUfM7DpWLDtxwJvQhaE6zHDDzkLC/xIj7CguZ8/YVhUX5SlQolFZSg0+JNCAhkMZSTESeOFAhn2NCRg8MIvwRGACdwQEJhgjsEERdCDgxELjQCCQwZGdMMaFN3gwUUXcKDRBUl0dEENIF1gx0gGMGBShykZsAdLBtjw0gQHnHjATBO8YNMEUtiUgQE6fQFjTn+oMIEBUizyBR0TLNXBIiQcdRciTICxV/8fGzzAByNiRMBIH1Iu0seTVmL5x5VTasllll2GCeaYW3pppphlovllmmQ2WdddfwzAByJX8DHkA1fs9ccJBRD2RwU5hADAH6cgsMMDT5xiQQGo8DDZKTxsJksOnj1WCmikwBBCL6XtcoIPqgGDgg6u/RBbD7QRcVtuovjBWwm/BYfNDMUd541y4SxAjh/W+bEACtLNsEAEwrIQxh8lVJDFIm3ggcYiebCxghl/yCCDHGOgYa0MGKSwrQBtCCCuAGOMS24V6FYxRhPsNrFCu3qsoMW8WrzRxb1dvKHBvho0gMG/GPgLcANYFIxFAlwkzEUCMTQcQwIeROwBDhJPLMIuxSKMgLEIN9jhsR03SCCyBHdsYfIWF5zsAh0utHyHCi2rccEZMhtAbR1wnLFIIAA7) repeat-x; display: block; float: left; }
.cb-enable span, .cb-disable span { line-height: 30px; display: block; background-repeat: no-repeat; font-weight: bold; }
.cb-enable span { background-position: left -90px; padding: 0 10px; }
.cb-disable span { background-position: right -180px;padding: 0 10px; }
.cb-disable.selected { background-position: 0 -30px; }
.cb-disable.selected span { background-position: right -210px; color: #fff; }
.cb-enable.selected { background-position: 0 -60px; }
.cb-enable.selected span { background-position: left -150px; color: #fff; }
</style>

<script type="text/javascript">
jQuery(document).ready( function($) {
	/* sortable tr's */
	if (jQuery.ui) {
		$(".rex-table tbody").sortable({
			helper: function(e, tr) {
				var $originals = tr.children();
				var $helper = tr.clone();

				$helper.children().each(function(index) {
					$(this).width($originals.eq(index).width());
					$(this).css('background', '#ddd');
				});

				return $helper;
			},
			cursor: 'move',
			stop:function(i) {
				var order = [];

				$(".rex-table tbody tr").each(function() {
					order.push($(this).find('td:nth-child(2)').html());
				});

				$.ajax({
					type: "POST",
					url: window.location.pathname + '?function=update_string_table_prio',
					data: { 'order[]': order },
					success: function() {
					}               
				});
			}
		}).disableSelection();

		/* on/off switch */
		$('.rex-table tbody').sortable('disable');
		$('.switch').show();

		$(".cb-enable").click(function(){
			var parent = $(this).parents('.switch');
			$('.cb-disable',parent).removeClass('selected');
			$(this).addClass('selected');
			$('.checkbox',parent).attr('checked', true);

			$('.rex-table tbody').sortable('enable');
			$('.rex-table tbody tr').addClass('move');
		});

		$(".cb-disable").click(function(){
			var parent = $(this).parents('.switch');
			$('.cb-enable',parent).removeClass('selected');
			$(this).addClass('selected');
			$('.checkbox',parent).attr('checked', false);

			$('.rex-table tbody').sortable('disable');
			$('.rex-table tbody tr').removeClass('move');
		});
	}
});
</script>
<?php } ?>

<?php
} elseif ($func == "add" || $func == "edit") {
	// remove delete button for non admins
	if ($REX['USER'] && !$REX['USER']->isAdmin()) {
		rex_register_extension('REX_FORM_CONTROL_FIElDS', function ($params) {
			$params['subject']['delete'] = null;
			return $params['subject'];
		});
	}

	$legend = $I18N->msg('add');

	if ($func == 'edit') {
		$legend = $I18N->msg('edit');
	}
	
	$form = rex_form::factory($REX['TABLE_PREFIX'] . 'string_table', $I18N->msg('string_table_string') . ' ' . $legend, 'id=' . $id, 'post', false, 'rex_form_extended');
	//$form->debug = true;
	$form->addParam('clang', $clang);
	
	if($func == 'edit') {
		$form->addParam('id', $id);
	}
	
	// key
	if ($REX['USER'] && $REX['USER']->isAdmin()) {
		$field =& $form->addTextField('keyname');
	} else {
		$field =& $form->addReadOnlyField('keyname');
	}

	$field->setLabel($I18N->msg('string_table_keyname'));
	
	// value
	$field =& $form->addTextareaField('value_' . $clang);
	$field->setLabel($I18N->msg('string_table_value'));

	if ($REX['USER'] && $REX['USER']->isAdmin()) {
		$field =& $form->addPrioField('prior');
		$field->setLabel($I18N->msg('string_table_prior'));
		$field->setLabelField('keyname');
	}

	if (!rex_request('current_query', 'string')) {
		$form->addParam('current_query', rex_string_table::getURLQuery($REX['CUR_CLANG']));	
	}
		
	$form->show();
}
include $REX['INCLUDE_PATH'].'/layout/bottom.php';
?>


