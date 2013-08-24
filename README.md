String Table AddOn für REDAXO 4
===============================

Mit diesem REDAXO AddOn kann man globale Strings in eine Datenbank-Tabelle speichern und per PHP-Methode auslesen. Es ist ein modifiziertes opf_lang AddOn mit ein paar zusätzlichen Features.

Features
--------

* Einen String kann man über die Methode `rex_string_table::getString($key)` oder alternativ über die Funktion `rex_getString($key)` auslesen
* Zusätzliche automatische Ersetzung nun auch über die OUTPUT_FILTER Methode einstellbar (siehe `settings.inc.php`). Die Keys werden dann im eigenen Code so notiert: ###key###. In der String Table aber nur so: key (also ohne die Start- und Endzeichen)
* Mehrsprachigkeit wird berücksichtigt
* Nicht-Admins dürfen den String-Key nicht ändern
* Prio änderbar über Drag'n Drop inkl. coolem On/Off Switch

Hinweise
--------

* Getestet mit REDAXO 4.4, 4.5
* AddOn-Ordner lautet: `string_table`
* Ein Key wird so notiert: `linktext_footer` (bitte hier keine ### oder ähnliches eintragen)

Changelog
---------

siehe [CHANGELOG.md](CHANGELOG.md)

Lizenz
------

siehe [LICENSE.md](LICENSE.md)

Credits
-------

* Cooler On/Off Switch Generator: http://proto.io/freebies/onoff/
* [Jan Kristinus](http://github.com/dergel) und [Thomas Blum](https://github.com/tbaddade) für das zugrundeliegende opf_lang AddOn
* [polarpixel](https://github.com/polarpixel) für Testing und Verbesserungsvorschläge
