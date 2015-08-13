<?php
$newConfigSettingsFile = '<?php
// ICEcoder system settings
$ICEcoderSettings = array(
	"versionNo"		=> "5.2",
	"codeMirrorDir"		=> "CodeMirror",
	"docRoot"		=> $_SERVER[\'DOCUMENT_ROOT\'],	// Set absolute path of another location if needed
	"demoMode"		=> false,
	"devMode"		=> false,
	"fileDirResOutput"	=> "none",			// Can be none, raw, object, both (all but \'none\' output to console)
	"loginRequired"		=> true,
	"multiUser"		=> false,
	"languageBase"		=> "english.php",
	"lineEnding"		=> "\n",
	"newDirPerms"		=> 755,
	"newFilePerms"		=> 644,
	"enableRegistration"	=> true
);
?>';
?>