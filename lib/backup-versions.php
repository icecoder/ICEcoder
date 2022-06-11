<?php
// Load common functions
include "headers.php";
include "settings.php";
$text = $_SESSION['text'];
$t = $text['backup-versions'];

$file = str_replace("|" ,"/", xssClean($_GET['file'], 'html'));
$fileCountInfo = getVersionsCount(dirname($file), basename($file));
$versions = $fileCountInfo['count'];

$assetsPath = "assets" === $settingsClass->assetsRoot
    ? "../" . $settingsClass->assetsRoot
    : $settingsClass->assetsRoot;
?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> backup version control</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="<?php echo $assetsPath;?>/css/resets.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" type="text/css" href="<?php echo $assetsPath;?>/css/backup-versions.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" href="<?php echo $assetsPath;?>/css/codemirror.css?microtime=<?php echo microtime(true);?>">
<script src="<?php echo $assetsPath;?>/js/codemirror-compressed.js?microtime=<?php echo microtime(true);?>"></script>

<style type="text/css">
.CodeMirror {position: absolute; width: 409px; height: 180px; font-size: <?php echo $ICEcoder["fontSize"];?>}
.CodeMirror-scroll {overflow: hidden}
/* Make sure this next one remains the 3rd item, updated with JS */
.cm-tab {border-left-width: <?php echo $ICEcoder["visibleTabs"] ? "1px" : "0";?>; margin-left: <?php echo $ICEcoder["visibleTabs"] ? "-1px" : "0";?>; border-left-style: solid; border-left-color: rgba(255,255,255,0.15)}
</style>
<link rel="stylesheet" href="<?php
echo $assetsPath . '/css/theme/';
echo $ICEcoder["theme"] === "default" ? 'icecoder.css': $ICEcoder["theme"] . '.css';
echo "?microtime=".microtime(true);
?>">
<link rel="stylesheet" href="<?php echo $assetsPath;?>/css/foldgutter.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" href="<?php echo $assetsPath;?>/css/simplescrollbars.css?microtime=<?php echo microtime(true);?>">
</head>

<body class="backup-versions" onkeyup="parent.ICEcoder.handleModalKeyUp(event, 'versions')" onload="this.focus();">

<h1 id="title"><?php echo $versions." ".($versions != 1 ? $t["backups"] : $t["backup"])." ".$t['available for'].":";?></h1>
<h2><?php echo $file;?></h2>

<br>
<div style="display: inline-block; height: 500px; width: 210px; overflow-y: scroll">

<?php
$dateCounts = $fileCountInfo['dateCounts'];
$displayVersions = $versions;

// Establish the base, host and date dir parts...
$backupDirHost = "localhost";

foreach ($dateCounts as $key => $value) {
	echo "<b>".date("jS M Y", strtotime($key)) . " (" . $value . " " . ($value !== 1 ? $t["backups"] : $t["backup"]) . ")</b>";
	echo '<br>';
	for ($j = 0; $j < $value; $j++) {
		echo '<a href="backup-versions-preview-loader.php?file=' . str_replace("/", "|", $backupDirHost . '/' . $key . $file) .
            ' (' . ($value - $j) . ')&csrf=' . $_SESSION['csrf'] .
            '" onclick="highlightVersion(' . $displayVersions . ')" id="backup-' . $displayVersions .
            '" target="previewLoader">Backup ' . $displayVersions . '</a><br>';
		$displayVersions--;
	}
	echo '<br>';
}
?>
</div>
<div class="previewArea">
	<textarea id="code" name="code">Click a backup to the left to preview it</textarea>
</div>
<div style="display: none; width: 180px; margin-left: 30px" id="buttonsContainer">
	<div class="button" onclick="openNew()">Open in new tab</div>
	<div class="button" onclick="openDiff()">Open in diff mode</div>
	<div class="button" onclick="restoreVersion()">Restore as new version</div>
	<div id="infoContainer"></div>
</div>
<div style="display: none">
	<iframe name="previewLoader"></iframe>
</div>

<script>
versions = <?php echo $versions;?>;
let highlightVersion = function(elem) {
	for (let i = versions; i >= 1; i--) {
		document.getElementById('backup-' + i).style.color = i === elem
			? 'rgba(0, 198, 255, 0.7)'
			: null;
	}
};

<?php
echo "fileName = '" . basename($file) . "';";
include dirname(__FILE__) . "/../assets/js/language-modes-partial.js";
?>

var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
	mode: mode,
	lineNumbers: parent.ICEcoder.lineNumbers,
	gutters: ["CodeMirror-foldgutter", "CodeMirror-lint-markers", "CodeMirror-linenumbers"],
	foldGutter: {gutter: "CodeMirror-foldgutter"},
	foldOptions: {minFoldSize: 1},
	lineWrapping: parent.ICEcoder.lineWrapping,
	indentWithTabs: "tabs" === parent.ICEcoder.indentType,
	indentUnit: parent.ICEcoder.indentSize,
	tabSize: parent.ICEcoder.indentSize,
	matchBrackets: parent.ICEcoder.matchBrackets,
	electricChars: false,
	highlightSelectionMatches: true,
    scrollbarStyle: parent.ICEcoder.scrollbarStyle,
	showTrailingSpace: parent.ICEcoder.showTrailingSpace,
	lint: false,
	readOnly: "nocursor",
    theme: parent.ICEcoder.theme
});
editor.setSize("480px","500px");

let openNew = function() {
	let cM;

    parent.ICEcoder.showHide('hide',parent.document.getElementById('blackMask'));
    parent.ICEcoder.newTab(false);
	cM = parent.ICEcoder.getcMInstance();
	cM.setValue(editor.getValue());
}

let openDiff = function() {
	let cMDiff;

    parent.ICEcoder.showHide('hide',parent.document.getElementById('blackMask'));
    parent.ICEcoder.setSplitPane('on');
	cMDiff = parent.ICEcoder.getcMdiffInstance();
	cMDiff.setValue(editor.getValue());
	setTimeout(function() {
        cMDiff.focus();
    }, 100);
};

let restoreVersion = function() {
	let cM;

	if (parent.ICEcoder.ask("To confirm - this will paste the displayed backup content to your current tab and save, OK?")) {
        parent.ICEcoder.showHide('hide',parent.document.getElementById('blackMask'));
		cM = parent.ICEcoder.getcMInstance();
		cM.setValue(editor.getValue());
        parent.ICEcoder.saveFile(false, false);
        cM.focus();
	}
}
</script>

<?php
echo $systemClass->getDemoModeIndicator(true);
?>

</body>

</html>
