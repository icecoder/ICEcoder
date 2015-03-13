<?php
// Dutch language translation
// Door: Julian Kaagman
//	@dutchwaters (GitHub)

// Please preserve formatting, line breaks, special characters, anything in <tags> and HTML equivalents (eg &amp;). Translations on right side.
// Special chars: http://www.ascii.cl/htmlcodes.htm

$text = array(

// / [ROOT LEVEL]

	"editor" =>
	array(
		"Click icons for..."		=> "<strong>Klik op de icoontjes<br>voor hulp &amp;<br>gebruiks info</strong>",
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
		"Are you sure you want to close?" => "Are you sure you want to close?",
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
		"Remote"			=> "Extern",
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
		"Autocomplete"			=> "Automatisch aanvullen",
		"Comment/Uncomment"		=> "Commentaar maken",
		"Jump to Definition"		=> "Spring naar definitie",
		"Manual"			=> "Handleiding",
		"Shortcuts"			=> "Snelkoppeling",
		"Settings"			=> "Opties",
		"Search for selected"		=> "Zoek naar geselecteerd",
		"website"			=> "website",
		"Close all tabs"		=> "Sluit alle tabbladen",
		"Alphabetize tabs"		=> "Sorteer tabbladen",
//		"Find"				=> "Zoek",
//		"in"				=> "in",
//		"and"				=> "en",
//		"replace"			=> "vervangen",
//		"replace all"			=> "alles vervangen",
//		"this document"			=> "dit document",
//		"open documents"		=> "open documenten",
//		"all files"			=> "alle bestanden",
//		"all filenames"			=> "alle bestandsnamen",
		"Turn on/off..."		=> "Schakel codehulp aan/uit",
		"Code Assist"			=> "Codehulp",
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
	
	"get-branch" =>
	array(
		"There are no..."		=> "Er zijn geen verschillen aangetroffen tussen de lokale en de Github repo. Wilt u terug schakelen naar de normale modus?",
		"Sorry, there was..."		=> "Sorry, er is een fout opgetreden, foutcode:",
		"Your local folder..."		=> "Uw lokale map is leeg, wilt u de inhoud klonen"
	),

	"github-manager" =>
	array(
		"Sorry, cannot create..."	=> "Sorry, kan geen map aanmaken op",
		"Cannot update config..."	=> "Kan het configuratie bestand niet updaten. Zet alstublieft eerst publieke schrijfrechten aan",
		"and try again"			=> "en probeer het opnieuw",
		"saving github paths"		=> "opslaan Github paden...",
		"github paths"			=> "Github paden",
		"Choose existing path"		=> "Kies een bestaand pad",
		"Local path"			=> "Lokaal pad",
		"Remote GitHub path"		=> "Extern Github pad",
		"Choose"			=> "Kies",
		"Set local and..."		=> "Maak het lokale en externe pad leeg, om te verwijderen",
		"Update"			=> "Update",
		"Add new path"			=> "Nieuw pad toevoegen",
		"Add"				=> "Toevoegen",
		"Usage Info"			=> "Gebruiks Info:",
		"Enter relative local..."	=> "Voer relatieve lokale paden (bv /server/mijnbestanden) en absolute Github paden (bv https://github.com/user/repo of https://github.com/user/repo/tree/branch voor vertakkingen (branches)), zoals het voorbeeld. Als je dit doet worden de bron paden op beide locaties gevestigd als een paar.",
		"You can then..."		=> "You can then choose a path pair and this then becomes your new root path in ICEcoder.",
		"The file manager..."		=> "The file manager then displays a new GitHub icon, which you can click on to perform and show a diff check between the 2 sources. These diffs can then be committed and pushed to the remote path at GitHub or cloned to your local path, to sync your files.",
		"If you want..."		=> "If you want to set another root path, this can be done in the Settings screen."
	),

	"github" =>
	array(
		"Sorry, you do..."		=> "Sorry, you do not appear to have OpenSSL loaded on your PHP instance, so https is not available. This is required for GitHub data transfer, please amend php.ini settings, restart your server and try again"
	),

	"headers" =>
	array(
		"Bad CSRF token..."		=> "Foute CSRF token. Graag de fout informatie delen op https://github.com/mattpass/ICEcoder zodat het kan worden opgelost."
	),

	"help" =>
	array(
		"shortcuts"			=> "snelkoppelingen",
		"Within document"		=> "Binnen het document",
		"On Tabs"			=> "Op Tabs",
		"Within file manager"		=> "Binnen bestandsbeheer",
		"Anywhere"			=> "Overal",
		"Space"				=> "Spatie",
		"Click"				=> "Klik",
		"or"				=> "of",
		"Left click"			=> "Linker muisklik",
		"Middle click"			=> "Middelste muisklik",
		"Double click tap..."		=> "Dubbel klikken / tap (mobiele apparatuur)",
		"Right click"			=> "Rechter muisklik",
		"Middle scrollwheel"		=> "Midden scrollwiel",
		"Drag"				=> "Slepen",
		"Autocomplete add snippet"	=> "Aanvullen / toevoegen snippet",
		"Multiple select"		=> "Multi selecteren",
		"Move line up"			=> "Verplaats regel omhoog",
		"Move line down"		=> "Verplaats regel omlaag",
		"Duplicate lines"		=> "Kopieer regel(s)",
		"Remove lines"			=> "Verwijder regel(s)",
		"Insert line before"		=> "Regel invoegen voor",
		"Insert line after"		=> "Regel invoegen na",
		"Search for selected"		=> "Zoek naar geselecteerd",
		"Jump to definition"		=> "Spring naar definitie / spring terug",
		"Comment uncomment"		=> "Commentaar (ongedaan) maken",
		"Insert tab indent"		=> "Invoegen tab / verspringen geselecteerd",
		"Wrap with div"			=> "Omhullen met &lt;div&gt;",
		"Wrap with span"		=> "Omhullen met &lt;span&gt;",
		"Wrap unwrap p"			=> "Omhullen / onthullen met &lt;p&gt;",
		"Wrap unwrap a"			=> "Omhullen / onthullen met &lt;a&gt;",
		"Wrap unwrap b"			=> "Omhullen / onthullen met &lt;b&gt;",
		"Wrap unwrap i"			=> "Omhullen / onthullen met &lt;i&gt;",
		"Wrap unwrap strong"		=> "Omhullen / onthullen met &lt;strong&gt;",
		"Wrap unwrap em"		=> "Omhullen / onthullen met &lt;em&gt;",
		"Wrap unwrap li"		=> "Omhullen / onthullen met &lt;li&gt;",
		"Wrap unwrap h1..."		=> "Omhullen / onthullen met &lt;h1&gt; - &lt;h3&gt;",
		"End line with..."		=> "Eindig regel met &lt;br&gt;",
		"Close tab"			=> "Sluit tab",
		"Select file folder"		=> "Selecteer bestand / map",
		"Open file"			=> "Open bestand",
		"Range select"			=> "Selecteer bereik",
		"Options for selected"		=> "Opties voor geselecteerd",
		"Delete selected"		=> "Verwijder geselecteerde",
		"Next previous tab"		=> "Volgende / vorige tab",
		"Next tab"			=> "Volgende tab",
		"Previous tab"			=> "Vorige tab",
		"New tab"			=> "Nieuw tab",
		"Close current tab"		=> "Sluit huidige tab",
		"Open file prompt"		=> "Open bestands prompt",
		"Find"				=> "Zoek",
		"Focus on Go..."		=> "Focus op ga naar regel invoer",
		"Save"				=> "Opslaan",
		"Save as"			=> "Opslaan als...",
		"View webpage"			=> "Bekijk webpagina",
		"Contract expand file..."	=> "Inklappen / uitklappen bestandsbeheer",
		"Fold unfold current..."	=> "Vouwen / uitvouwen huidige regel",
		"Refocus on document"		=> "Herfocus op document",
		"Cancel tasks"			=> "Annuleer taken"
	),

	"ice-coder" =>
	array(
		"No text selected..."		=> "Geen tekst geselecteerd om te zoeken",
		"Creating Folder"		=> "Aanmaken map",
		"Sorry you can..."		=> "Sorry, je kan maximaal 100 bestanden open hebben staan!",
		"Opening File"			=> "Openen bestand",
		"Enter relative file..."	=> "Geef relatieve bestands pad (voorafgaand door een slash) of een externe URL",
		"Getting"			=> "Ophalen",
		"Please enter the..."		=> "Voer de nieuwe naam in voor",
		"Renaming to"			=> "Hernoemen naar",
		"Moving to"			=> "Verplaatsen naar",
		"Deleting File"			=> "Bestand verwijderen",
		"Pasting File"			=> "Bestand plakken",
		"Sorry cannot paste..."		=> "Sorry, kan niet de gehele root plakken",
		"Nothing to paste..."		=> "Er is niets om te plakken, kopieer eerst een bestand of map!",
		"Replacing text in"		=> "Wijzig de tekst in",
		"Cancelled tasks"		=> "Geannuleerde taken",
		"Open previous files"		=> "Open voorgaande bestand(en)?",
		"Please enter your..."		=> "Please enter your GitHub token (either personal access token or client/secret pair token). See tooltip next to Github Auth Token on Help > Settings screen for more info",
		"This will compare..."		=> "This will compare and show a diff view between your local dir and the repo. OK?",
		"Please note for..."		=> "Let op: om de update goed te laten doorvoeren, moet je schrijfrechten hebben op alle bestanden en mappen van ICEcoder. Moet je deze versie van ICEcoder herstellen, dan vind je die in de map /tmp. Klik op ok om door te gaan met automatisch updaten, of druk op annuleren om af te breken. Voor een handmatige update kun je het zip bestand van de ICEcoder website downloaden.",
		"You can start..."		=> "U kunt bug rapporteren aanzetten in: Help > Settings",
		"Error cannot find..."		=> "Fout: kan geen toegang krijgen of de bestands paden vinden",
		"No new errors..."		=> "Geen nieuwe fouten gevonden",
		"You have made..."		=> "Er zijn wijzigingen aangetroffen. Wilt u verder gaan zonder op te slaan?",
		"Close all tabs"		=> "Sluit alle tabbladen?"
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
),

	"multiple-results" =>
	array(
		"rename all"			=> "hernoem alles",
		"replace all"			=> "vervang alles",
		"document"			=> "document",
		"Found"				=> "Gevonden",
		"times"				=> "keer",
		"replace"			=> "vervang",
		"file folder"			=> "bestand/map",
		"rename to"			=> "hernoemen naar",
		"rename"			=> "hernoemen",
		"file"				=> "bestand",
		"No matches found"		=> "Geen overeenkomsten gevonden",
		"selected"			=> "geselecteerd",
		"found in"			=> "gevonden in",
		"Replaced"			=> "Vervangen"
	),

	"plugins-manager" =>
	array(
		"ICEcoder needs to..."		=> "ICEcoder moet opnieuw worden geladen om deze plug-in te gebruiken. Nu opnieuw laden?",
		"saving plugins"		=> "opslaan plug-ins...",
		"Cannot update config..."	=> "Kan het configuratie bestand niet updaten. Zet alstublieft eerst publieke schrijfrechten aan",
		"and try again"			=> "en probeer het opnieuw",
		"couldnt delete dir"		=> "kan de map niet verwijderen",
		"couldnt delete file"		=> "kan bestand niet verwijderen",
		"plugins"			=> "plug-ins",
		"Guide to writing..."		=> "Gids om plug-ins te schrijven",
		"Manage Installed"		=> "Beheer ge&Iuml;nstalleerd",
		"URL"				=> "URL",
		"Target"			=> "Doel",
		"Timer"				=> "Timer",
		"Update"			=> "Update",
		"Install"			=> "Installeren",
		"Uninstall"			=> "De&Iuml;nstalleren",
		"Reload after install..."	=> "Opnieuw laden na installatie vereist"
	),

	"properties" =>
	array(
		"properties"			=> "eigenschappen",
		"Size"				=> "Groote",
		"Modified"			=> "Aangepast",
		"Last access"			=> "Laatst geopend",
		"Type"				=> "Type",
		"Readable Writeable"		=> "Leesbaar / Schrijfbaar",
		"Relative path"			=> "Relatief pad",
		"Absolute path"			=> "Absoluut pad",
		"Contains"			=> "Bevat",
		"Permissions"			=> "Rechten",
		"Owner"				=> "Eigenaar",
		"Group"				=> "Groep",
		"Public"			=> "Publiek",
		"Read"				=> "Lezen",
		"Write"				=> "Schrijven",
		"Execute"			=> "Uitvoeren",
		"Change to"			=> "Veranderen naar",
		"update"			=> "update"
	),

	"settings-common" =>
	array(
		"Your document does..."		=> "Het bleek dat uw bestand niet gebruik maakte van UTF-8 codering, dus is het geconverteerd"
	),

	"settings-save-current-files" =>
	array(
		"Cannot update config..."	=> "Kan het configuratie bestand niet updaten. Zet alstublieft eerst publieke schrijfrechten aan",
		"and try again"			=> "en probeer het opnieuw"
	),

	"settings-screen" =>
	array(
		"settings"			=> "instellingen",
		"version"			=> "versie",
		"website"			=> "website",
		"git"				=> "git",
		"codemirror dir"		=> "codemirror dir",
		"codemirror version"		=> "codemirror versie",
		"file manager root"		=> "bestandsbeheer root",
		"Free to use..."		=> "Vrij voor eigen gebruik, commercieel of persoonlijk. :)<br><br>Wij zijn niet aansprakelijk en bieden geen garantie, gebruik op eigen risico.<br><br>Een hoop fantastische mensen, en bedrijven hebben meegeholpen aan de ontwikkeling van ICEcoder waarvoor bedankt. Zie wie er allemaal heeft bijgedragen op",
		"functionality"			=> "functionaliteit",
		"check for updates..."		=> "check voor updates bij laden",
		"auto open last..."		=> "automatisch openen laatst geopende bestanden na inloggen",
		"when finding in..."		=> "when finding in files, exclude",
		"assisting"			=> "assisteren",
		"code assist"			=> "codehulp",
		"visible tabs"			=> "zichtbare tabs",
		"locked nav"			=> "vergrendelde nav",
		"tag wrapper command"		=> "tag wrapper command",
		"auto-complete on"		=> "automatisch aanvullen",
		"security"			=> "beveiliging",
		"new password"			=> "nieuw wachtwoord",
		"8 chars min"			=> "minimaal 8 tekens",
		"confirm password"		=> "herhaal wachtwoord",
		"banned files/folders"		=> "uitgesloten bestanden/mappen",
		"banned paths"			=> "uitgesloten paden",
		"ip addresses"			=> "ip adressen",
		"Slash prefixed comma..."	=> "Slash prefixed, komma gescheiden",
		"Comma delimited"		=> "komma gescheiden",
		"style"				=> "style",
		"theme"				=> "thema",
		"line wrapping"			=> "regel omslag",
		"indent type"			=> "inspring type",
		"indent size"			=> "inspring size",
		"font size"			=> "lettergrootte",
		"auto indent"			=> "auto indent",
		"layout"			=> "lay-out",
		"plugin panel aligned"		=> "plug-in paneel positie",
		"file manager"			=> "bestandsbeheer",
		"root"				=> "root",
		"Slash prefixed"		=> "Slash prefixed",
		"bug reporting"			=> "bug rapportage",
		"check in files"		=> "check in bestanden",
		"every"				=> "elke",
		"secs getting last"		=> "seconde, verstuur laatste",
		"lines"				=> "regels",
		"multi-user"			=> "multi-user",
		"Make sure you..."		=> "Zorg ervoor dat je niet jezelf buitensluit",
		"Registration"			=> "Registratie",
		"auth token"			=> "auth token",
		"Required to get..."		=> "Required to get diffs, commit to your GitHub hosted repo etc. If you do not have one, you can use a:".PHP_EOL.PHP_EOL.
							"- personal access token (https://help.github.com/articles/creating-an-access-token-for-command-line-use), or".PHP_EOL.
							"- full client/secret pair token (http://developer.github.com/v3/oauth).".PHP_EOL.PHP_EOL.
							"It is not recommended you set your token here however and is more secure to enter it when requested by ICEcoder as that will keep it in session only.".PHP_EOL.PHP_EOL.
							"However, if you work in a trusted and secure environment, it is more efficient to set it here.",
		"Sorry cannot commit..."	=> "Sorry, cannot commit settings in demo mode",
		"update"			=> "update"
	),

	"settings-update" =>
	array(
		"Cannot update config..."	=> "Kan het configuratie bestand niet updaten. Zet alstublieft eerst publieke schrijfrechten aan",
		"and try again"			=> "probeer het opnieuw",
		"and press refresh"		=> "en druk vernieuwen"
	),

	"updater" =>
	array(
		"Update appears to..."		=> "Update lijkt succesvol te zijn verlopen"
	)

);
?>
