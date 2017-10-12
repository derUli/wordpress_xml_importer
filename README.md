# wordpress_xml_importer
## Beschreibung
Importieren Sie Inhalte aus Wordpress in UliCMS.
Wordpress kann Inhalte als "WordPress eXtended RSS" exportieren, einem XML-Dateiformat, welches Sie mit diesem Modul in UliCMS importieren können

## Anleitung
### Inhalte aus Wordpress exportieren
1. Klicken Sie in Wordpress auf "Werkzeuge" > "Daten exportieren".
2. Wählen Sie aus, was exportiert werden soll.
3. Klicken Sie auf "Export-Datei herunterladen"

![Wordpress XML-Export](https://raw.githubusercontent.com/derUli/wordpress_xml_importer/master/screenshot2.jpg)
Format: ![Alt Text](url)

### Inhalte in UliCMS importieren
1. Öffnen Sie die Admin-oberfläche des Moduls wordpress_xml_importer unter dem Menüpunkt "Pakete".
2. Wählen Sie bei "Import von" als Quelle "Datei".
3. Wenn bereits bestehende Datensätze überschrieben werden sollen, wählen Sie "ersetzen". Achtung! Die Daten beim Import werden nicht versioniert.
4. Wählen Sie aus, wohin die Daten importiert werden sollen.
5. Klicken Sie auf "Import"

![UliCMS Import XML](https://raw.githubusercontent.com/derUli/wordpress_xml_importer/master/screenshot.jpg)


## Einschränkungen
wordpress_xml_importer befindet sich noch in einem frühen Stand der Entwicklung, daher sind folgende Features noch nicht vorhanden:
* Import zu blog noch nicht möglich
* Medien werden nicht importiert
* Individuelle Menüs werden nicht unterstützt
* Ein direkter Import aus der Datenbank ist noch nicht vorhanden
