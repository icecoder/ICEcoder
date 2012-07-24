<?php
$ICEcoder = array(
"versionNo"		=> "v 0.7.9",
"codeMirrorDir"		=> "CodeMirror-2.32",
"cMThisVer"		=> 2.32,
"root"			=> $_SERVER['DOCUMENT_ROOT']."",
"tabsIndent"		=> true,
"checkUpdates"		=> false,
"openLastFiles"		=> true,
"findFilesExclude"	=> array("_coder",".doc",".gif",".jpg",".jpeg",".pdf",".png",".swf",".xml",".zip"),
"codeAssist"		=> true,
"visibleTabs"		=> false,
"lockedNav"		=> true,
"accountPassword"	=> "",
"restrictedFiles"	=> array("wp-",".php",".rb",".sql"),
"bannedFiles"		=> array("_coder","wp-",".exe"),
"allowedIPs"		=> array("*"),
"plugins"		=> array(
			array("Database Admin","images/database.png","margin-top: 3px","plugins/adminer/adminer-3.3.3-mysql-en.php","_blank",""),
			array("Batch Image Processor","images/images.png","margin-top: 5px","http://birme.net","_blank",""),
			array("Zip It!","images/zip-it.png","margin-top: 3px","plugins/zip-it/?zip=|&exclude=.doc,.gif,.jpg,.jpeg,.pdf,.png,.swf,.xml,.zip","fileControl:<b>Zipping Files</b>","30")
			),
"theme"			=> "default",
"tabWidth"		=> 4,
"previousFiles"		=> "",
"last10Files"		=> ""
);
?>