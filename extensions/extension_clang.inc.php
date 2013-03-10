<?php

function string_table_clang_added($params)
{
  global $I18N, $REX;

  $id = (int)$params['id'];
	
	// abgleich der replacevalue felder..
	$sql = new rex_sql();
	//$sql->debugsql = true;
	$sql->setQuery('SELECT id, clang, value, keyname, count(keyname) AS count FROM '.TBL_STRING_TABLE.' GROUP BY keyname');
	
	$rows = $sql->getRows();
	for ($i = 1; $i <= $rows; $i++)
	{
		if (count($REX['CLANG']) != $sql->getValue('count'))
		{
			reset($REX['CLANG']);
			
			foreach ($REX['CLANG'] as $key => $val)
			{
				$id   = $sql->getValue('id');
				$repl = $sql->getValue('value');
				$wild = $sql->getValue('keyname');
				
				$sqlCheck = new rex_sql();
				$sqlCheck->setQuery('SELECT clang FROM '.TBL_STRING_TABLE.' WHERE clang = "'.$key.'" AND keyname = "'.$wild.'"');
				
				if ($sqlCheck->getRows() == 0)
				{
					// Nicht gefunden Sprache hinzufuegen	
					$sqlInsert = new rex_sql();
					$sqlInsert->setTable(TBL_STRING_TABLE);
					$sqlInsert->setValue('id', $id);
					$sqlInsert->setValue('clang', $key);
					$sqlInsert->setValue('keyname', $wild);
					$sqlInsert->setValue('value', $repl);
					$sqlInsert->insert();
				}
			}
		}
		$sql->next();
	}

}

function string_table_clang_delete($params)
{
  global $I18N, $REX;

  $id = (int)$params['id'];
	
	// abgleich der replacevalue felder..
	$sql = new rex_sql();
	//$sql->debugsql = true;
	$sql->setQuery('DELETE FROM '.TBL_STRING_TABLE.' WHERE clang = "'.$id.'"');
	
}

