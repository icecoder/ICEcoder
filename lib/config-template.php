<?php
$ICEcoder = array(
"root"			=> "",
"tabsIndent"		=> true,
"checkUpdates"		=> true,
"openLastFiles"		=> true,
"findFilesExclude"	=> array("_coder","ICEcoder",".doc",".gif",".jpg",".jpeg",".pdf",".png",".swf",".xml",".zip"),
"codeAssist"		=> true,
"visibleTabs"		=> false,
"lockedNav"		=> true,
"accountPassword"	=> "",
"bannedFiles"		=> array("_coder","ICEcoder"),
"bannedPaths"		=> array("/var/www/.git","/var/www/sites/all/modules","/var/www/sites/default/files"),
"allowedIPs"		=> array("*"),
"plugins"		=> array(
			array("Terminal","plugins/terminal/icon.gif","margin-top: 3px","plugins/terminal","_blank",""),
			array("Adminer","plugins/adminer/icon.png","margin-top: 3px","plugins/adminer/adminer-3.4.0-mysql-en.php","_blank",""),
			array("JS Hint","plugins/jshint/icon.png","margin-top: 3px","http://www.jshint.com","_blank",""),
			array("Emmet","plugins/emmet/icon.png","margin-top: 3px","http://docs.emmet.io","_blank",""),
			array("ICErepo","plugins/ice-repo/icon.png","margin-top: 3px","plugins/ice-repo","_blank",""),
			array("Dochub","plugins/dochub/icon.png","margin-top: 3px","http://dochub.io","_blank",""),
			array("JS Regex","plugins/jsregex/icon.png","margin-top: 3px","http://jsregex.herokuapp.com","_blank",""),
			array("wireframe|cc","plugins/wireframecc/icon.png","margin-top: 3px","http://wireframe.cc","_blank",""),
			array("Zip It!","plugins/zip-it/icon.png","margin-top: 3px; margin-left: 3px","plugins/zip-it/?zip=|&exclude=*.doc*.gif*.jpg*.jpeg*.pdf*.png*.swf*.xml*.zip","fileControl:<b>Zipping Files</b>","30")
			),
"theme"			=> "default",
"lineWrapping"		=> true,
"tabWidth"		=> 4,
"previousFiles"		=> "",
"last10Files"		=> ""
);
?>