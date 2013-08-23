String Table - Changelog
========================

### Version 1.4.0

* Ist der String des Keys leer oder ist der Key nicht vorhanden, wird dieser mit einem Span un der CSS-Klasse `string-table-key` versehen. Im Frontend können diese Keys dann per CSS optisch aufgewertet werden für den Redakteur
* Auto Replace Methode hinzugefügt um die Keys (###key###) automatisch ersetzen zu lassen. Einstellung der Start- und Endzeichenkette ebenfalls möglich.
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

