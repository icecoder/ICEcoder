<?php
// Dutch language translation
// Door: Julian Kaagman

// Please preserve formatting, line breaks, special characters, anything in <tags> and HTML equivalents (eg &amp;). Translations on right side.
// Special chars: http://www.ascii.cl/htmlcodes.htm

$text = array(

// / [ROOT LEVEL]

	"editor" =>
	array(
		"Click icons for..."		=> "<b>Klik op de icoontjes<br>voor hulp &amp;<br>gebruiks info</b>",
		"server"			=> "server",
		"Server name, OS..."		=> "Server naam, besturingssysteem & IP:",
		"Root"				=> "Root:",
		"ICEcoder root"			=> "ICEcoder root:",
		"PHP version"			=> "PHP versie:",
		"Date & time"			=> "Datum & tijd:",
		"your device"			=> "Uw apparaat",
		"Browser"			=> "Browser:",
		"Your IP"			=> "Uw IP:",
		"files"				=> "bestanden",
		"Last 10 files..."		=> "Laatste 10 geopende bestanden:",
		"none"				=> "[none]",
		"test suite"			=> "test suite",
		"Run unit tests"		=> "Voer unit tests uit",
		"dev mode"			=> "Ontwikkelaars modus",
		"Status"			=> "Status",
		"Using"				=> "Gebruikt",
		"You can switch..."		=> "U kunt de ontwikkelaars modus aan/uit zetten 
in lib/config__settings.php",
		"results"			=> "resultaten"

	),

	"files" =>
	array(
		"Lock"				=> "Vergrendelen",
		"Refresh"			=> "Vernieuwen",
		"ROOT"				=> "[ROOT]"

	),

	"index" =>
	array(
		"UPDATE INFO"			=> "UPDATE INFO",
		"now available"			=> "nu beschikbaar",
		"Your version is"		=> "Uw versie is",
		"Update now"			=> "Nu updaten",
		"You have some..."		=> "Er zijn wijzigingen die niet opgeslagen zijn",
		"working"			=> "bezig",
		"Color picker"			=> "Kleuren kiezer",
		"New File"			=> "Nieuw bestand",
		"New Folder"			=> "Nieuwe map",
		"Upload File(s)"		=> "Upload bestand(en)",
		"Paste"				=> "Plakken",
		"Open"				=> "Open",
		"Copy"				=> "Kopi&euml;ren",
		"Duplicate"			=> "Dupliceren",
		"Delete"			=> "Verwijderen",
		"Rename"			=> "Hernoemen",
		"View Webpage"			=> "Bekijk webpagina",
		"Download"			=> "Download",
		"Properties"			=> "Eigenschappen",
		"File"				=> "Bestand",
		"Edit"				=> "Bewerken",
		"Remote"			=> "Remote",
		"Help"				=> "Help",
		"Save"				=> "Opslaan",
		"Save As"			=> "Opslaan als",
		"Live Preview"			=> "Voorbeeld",
		"Upload"			=> "Upload",
		"Zip"				=> "Zip",
		"Print"				=> "Print",
		"Fullscreen toggle"		=> "Schakelen volledig scherm",
		"Logout"			=> "Uitloggen",
		"Undo"				=> "Ongedaan maken",
		"Redo"				=> "Opnieuw",
		"Indent more"			=> "Rechts inspringen",
		"Indent less"			=> "Links verspringen",
		"Autocomplete"			=> "Autocomplete",
		"Comment/Uncomment"		=> "Commentaar maken",
		"Jump to Definition"		=> "Spring naar definitie",
		"Manual"			=> "Manual",
		"Shortcuts"			=> "Snelkoppeling",
		"Settings"			=> "Opties",
		"Search for selected"		=> "Zoek naar geselecteerd",
		"website"			=> "website",
		"Close all tabs"		=> "Sluit alle tabbladen",
		"Alphabetize tabs"		=> "Sorteer tabbladen",
		"Find"				=> "Zoek",
		"in"				=> "in",
		"and"				=> "en",
		"replace"			=> "vervangen",
		"replace all"			=> "alles vervangen",
		"this document"			=> "dit document",
		"open documents"		=> "open documenten",
		"all files"			=> "alle bestanden",
		"all filenames"			=> "alle bestandsnamen",
		"Turn on/off..."		=> "Schakel code assistent aan/uit",
		"Code Assist"			=> "Code assistent",
		"Go to Line"			=> "Ga naar regel",
		"View"				=> "Beeld",
		"Bug reporting not active"	=> "Bug rapportage niet actief"
	),

// /LIB

	"bug-files-check" =>
	array(
		"Found in"			=> "Gevonden in:"
	),

	"file-control" =>
	array(
		"Sorry"				=> "Sorry",
		"does not seem..."		=> "bestaat niet op de server",
		"Sorry, could not..."		=> "Sorry, kan geen gegevens ophalen van",
		"Sorry, cannot create..."	=> "Sorry, kan geen map aanmaken op",
		"Sorry, cannot copy"		=> "Sorry, kan niet het volgende niet kopi&euml;ren",
		"into"				=> "naar",
		"Uploaded file(s) OK"		=> "Ge&uuml;ploade bestand(en)",
		"Sorry, cannot upload"		=> "Sorry, kan niet uploaden",
		"Sorry, cannot upload..."	=> "Sorry, kan niet uploaden in de demo modus",
		"Sorry, cannot rename"		=> "Sorry, kan niet hernoemen",
		"Maybe public write..."		=> "Misschien zijn er publieke schrijfrechten nodig voor deze, of de bovenliggende map?",
		"Sorry, cannot move"		=> "Sorry, kan niet worden verplaatst",
		"Sorry, cannot save"		=> "Sorry, kan niet opslaan",
		"Sorry, cannot replace..."	=> "Sorry, Kan geen tekst vervangen in",
		"Sorry, cannot change..."	=> "Sorry, kan de rechten niet wijzigen voor",
		"Sorry, cannot delete..."	=> "Sorry, kan de root level niet verwijderen",
		"Sorry, cannot delete"		=> "Sorry, kan niet verwijderd worden",
		"Sorry, this file..."		=> "Sorry, het bestand is gewijzigd, maar kan niet worden opgeslagen",
		"Reload this file..."		=> "Vernieuw dit bestand en kopieer de huidige versie naar een nieuw bestand?",
		"There was a..."		=> "Er was een technisch probleem, mogelijk was er iets niet gereed. ICEcodeer heeft bestandsbeheer opnieuw geladen.",
		"displayed at"			=> "weergegeven op",
		"Enter filename to..."		=> "Voer een bestandsnaam in om het op te slaan op",
		"That file exists..."		=> "Dit bestand bestaat al, overschrijven?",
		"Saving"			=> "Opslaan"
	),	

	"login" =>
	array(
		"set password"			=> "sla wachtwoord op",
		"login"				=> "login",
		"To disable registration..."	=> "Om de registratie modus uit te zetten, open het menu opties of open lib/config___settings.php en wijzig enableRegistration naar false",
		"Registration mode enabled"	=> "Registratie modus aan",
		"auto-check for updates"	=> "automatisch controleren op updates",
		"To put into..."		=> "Om de multi-user modus te gebruiken, open het menu opties of open lib/config___settings.php en verander multiUser naar true",
		"multi-user"			=> "multi-user"
	)
);
?>
