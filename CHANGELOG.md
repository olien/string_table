String Table - Changelog
========================

### Version 1.6.1 - 03. Dezember 2014

* Addon-Name in Settings-Datei ausgelagert. Bei einem Update muss die settings.inc.php im Data Ordner einmal gelöscht werden.

### Version 1.6.0 - 25. Oktober 2014

* Updatefähigkeit für REDAXO 4.6 hergestellt. Einstellungen werden jetzt im Data-Ordner gespeichert.

### Version 1.5.1 - 23. Januar 2014

* Fixed: Bug beim Hinzufügen von einem neuen String, thx@robo

### Version 1.5.0 - 14. Januar 2014

* `rex_string_table::getString()` Methode bzw. `rex_getString()` Funktion um folgende Parameter ergänzt: `$fillEmpty = true` (steuert ob ein Platzhalter bei einem leeren String angezeigt wird) und `$clang = -1` (steuert die Sprache des Strings, damit kann man z.B. einen sprachunabhängigen String nutzen der für alle Sprachen gleich sein soll)
* Mini-Codebeispiel zur Readme hinzugefügt (API)

### Version 1.4.0 - 27. August 2013

* Paginierung der Stringliste vorerst "deaktiviert", da es zu Problemen kam mit der Drag n' Drop Sortierung.
* `rex_getString($key)` als Kurzschreibweise zu `rex_string_table::getString($key)` hinzugefügt
* Ist der String des Keys leer oder ist der Key nicht vorhanden, wird ein Platzhalter inkl. Span und der CSS-Klasse `string-table-key` angezeigt. Im Frontend können diese Platzhalter dann per CSS optisch aufgewertet werden.
* Auto Replace Methode hinzugefügt um die Keys automatisch ersetzen zu lassen (standardmäßig abgeschaltet). Einstellung der Start- und Endzeichenkette ebenfalls möglich. Die Keys ganz normal in die String Table schreiben: key. Im eigenen Code (Templates/Module) dann so: ###key### (bzw. je nach gewählter Start/Endzeichenkette)
* `settings.inc.php` hinzugefügt mit Einstellungsmöglichkeiten für das AddOn

### Version 1.3.2 - 01. August 2013

* Line Breaks in den Strings werden jetzt berücksichtigt und auch in der String-Auflistung korrekt angezeigt

### Version 1.3.1 - 21. Mai 2013

* Fixed: bei installiertem Website Manager funktionierte die Drag'n'Drop Sortierung nicht mehr
* Verbesserte Drag'n'Drop Sortierung 

### Version 1.3.0 - 10. April 2013

* Sortierbare Rex-List per Drag'n Drop inkl. On/Off Schalter
* Kleinere Bugfixes und Verbesserungen

### Version 1.2.0 - 13. März 2013

* Umbennenung der DB nach `rex_string_table`
* Umstellung auf `clang` DB Felder
* Die String-Einträge können nun sortiert werden
* Code cleanup

### Version 1.1.6 - 10. März 2013

Erstes Release mit folgenden Änderungen/Features gegenüber dem original opf_lang AddOn:

* Ersetzung über den OUTPUT_FILTER entfernt
* Klasse `rex_string_table` eingeführt
* Nicht-Admins dürfen den Key nicht abändern

