<?php
$ICEcoderUserSettings = array(
"versionNo"		=> "3.0 beta",
"root"			=> "",
"checkUpdates"		=> true,
"openLastFiles"		=> true,
"findFilesExclude"	=> array("_coder","ICEcoder",".doc",".gif",".jpg",".jpeg",".pdf",".png",".swf",".xml",".zip"),
"codeAssist"		=> true,
"visibleTabs"		=> false,
"lockedNav"		=> true,
"password"		=> "",
"bannedFiles"		=> array("_coder","ICEcoder"),
"bannedPaths"		=> array("/var/www/.git","/var/www/sites/all/modules","/var/www/sites/default/files"),
"allowedIPs"		=> array("*"),
"plugins"		=> array(
			array("Adminer\\nMySQL database manager","plugins/adminer/icon.png","margin-top: 3px","plugins/adminer/adminer-3.6.3-mysql-en.php","_blank",""),
			array("Emmet\\nSnippet type booster","plugins/emmet/icon.png","margin-top: 3px","http://docs.emmet.io","_blank",""),
			array("ICErepo\\nGithub repo manager","plugins/ice-repo/icon.png","margin-top: 3px","plugins/ice-repo","_blank",""),
			array("Dochub\\nCoding syntax info","plugins/dochub/icon.png","margin-top: 3px","http://dochub.io","_blank",""),
			array("Voke\\nCoding syntax info","plugins/voke/icon.png","margin-top: 3px","http://voke.fm","_blank",""),
			array("Regexplained\\nRegex builder","plugins/regexplained/icon.png","margin-top: 3px","http://leaverou.github.io/regexplained","_blank",""),
			array("wireframe|cc\\nWireframing tool","plugins/wireframecc/icon.png","margin-top: 3px","http://wireframe.cc","_blank",""),
			array("TinyPNG\\nImage file compressor","plugins/tinypng/icon.png","margin-top: 7px","http://tinypng.org","_blank",""),
			array("Zip It!\\nFile/folder zip utility","plugins/zip-it/icon.png","margin-top: 3px; margin-left: 3px","plugins/zip-it/?zip=|&exclude=*.doc*.gif*.jpg*.jpeg*.pdf*.png*.swf*.xml*.zip","fileControl:<b>Zipping Files</b>","30")
			),
"theme"			=> "default",
"fontSize"		=> "13px",
"lineWrapping"		=> true,
"indentWithTabs"	=> true,
"indentSize"		=> 4,
"previousFiles"		=> "",
"last10Files"		=> ""
);
?>