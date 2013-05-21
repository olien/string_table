String Table AddOn für REDAXO 4
===============================

Mit diesem REDAXO AddOn kann man globale Strings in eine Datenbank-Tabelle speichern und per PHP-Methode auslesen. Es ist ein modifiziertes opf_lang AddOn allerdings ohne den Gebrauch des OUTPUT_FILTER EP's.

Features
--------

* Einen String kann man über die Methode `rex_string_table::getString($key);` auslesen
* Mehrsprachigkeit wird berücksichtigt
* Nicht-Admins dürfen den String-Key nicht ändern
* Prio änderbar über Drag'n Drop inkl. coolem On/Off Switch

Hinweise
--------

* Getestet mit REDAXO 4.4, 4.5
* AddOn-Ordner lautet: `string_table`
* Ein Key kann so lauten: `link_back_to_overview` (### ist also nicht nötig)
* Momentan keine Ersetzungen mehr über den OUTPUT-FILTER EP

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

