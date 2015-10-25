<?php
// Load common functions
include("headers.php");
include("settings.php");
$text = $_SESSION['text'];
$t = $text['backup-versions'];

$file = str_replace("|","/",xssClean($_GET['file'],'html'));
$fileCountInfo = getVersionsCount(dirname($file),basename($file));
$versions = $fileCountInfo['count'];
?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> backup version control</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="backup-versions.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" href="../<?php echo $ICEcoder["codeMirrorDir"]; ?>/lib/codemirror.css?microtime=<?php echo microtime(true);?>">
<script src="../<?php echo $ICEcoder["codeMirrorDir"]; ?>/lib/codemirror-compressed.js?microtime=<?php echo microtime(true);?>"></script>

<style type="text/css">
.CodeMirror {position: absolute; width: 409px; height: 180px; font-size: <?php echo $ICEcoder["fontSize"];?>}
.CodeMirror-scroll {overflow: hidden}
/* Make sure this next one remains the 3rd item, updated with JS */
.cm-tab {border-left-width: <?php echo $ICEcoder["visibleTabs"] ? "1px" : "0";?>; margin-left: <?php echo $ICEcoder["visibleTabs"] ? "-1px" : "0";?>; border-left-style: solid; border-left-color: rgba(255,255,255,0.2)}
</style>
<link rel="stylesheet" href="editor.css?microtime=<?php echo microtime(true);?>">
<?php
$themeArray = array();
$handle = opendir('../'.$ICEcoder["codeMirrorDir"].'/theme/');

while (false !== ($cssFile = readdir($handle))) {
	if ($cssFile !== "." && $cssFile != "..") {
		array_push($themeArray,basename($cssFile,".css"));
	}
}
sort($themeArray);
for ($i=0;$i<count($themeArray);$i++) {
	echo '<link rel="stylesheet" href="../'.$ICEcoder["codeMirrorDir"].'/theme/'.$themeArray[$i].'.css?microtime='.microtime(true).'">'.PHP_EOL;
}
?>
</head>

<body class="backup-versions">

<h1 id="title"><?php echo $versions." ".($versions != 1 ? $t["backups"] : $t["backup"])." ".$t['available for'].":";?></h1>
<h2><?php echo $file;?></h2>

<br>
<div style="display: inline-block">
<?php
$dateCounts = $fileCountInfo['dateCounts'];
$displayVersions = $versions;

// Establish the base, host and date dir parts...
$backupDirHost = isset($ftpSite) ? parse_url($ftpSite,PHP_URL_HOST) : "localhost";

foreach ($dateCounts as $key => $value) {
	echo "<b>".date("jS M Y",strtotime($key))." (".$value." ".($value != 1 ? $t["backups"] : $t["backup"]).")</b>";
	echo '<br>';
	for ($j=0; $j<$value; $j++) {
		echo '<a href="backup-versions-preview-loader.php?file='.str_replace("/","|",$backupDirHost.'/'.$key.$file).' ('.$displayVersions.')&csrf='.$_SESSION['csrf'].'" target="previewLoader">Backup '.$displayVersions.'<br>';
		$displayVersions--;
	}
	echo '<br>';
}
?>
</div>
<div style="display: inline-block; height: 300px; width: 400px; margin-left: 30px">
	<textarea id="code" name="code">Click a backup to the left to preview it</textarea>
</div>
<div style="display: none; width: 180px; margin-left: 30px" id="buttonsContainer">
	<div class="button" onclick="openNew()">Open in new tab</div>
	<div class="button" onclick="openDiff()">Open in diff mode</div>
	<!--
	<div class="button" onclick="alert('Function not available yet - Coming in v5.4')">Restore as new version</div>
	//-->
</div>
<div style="display: none">
	<iframe name="previewLoader"></iframe>
</div>

<script>
var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
	lineNumbers: true,
	readOnly: "nocursor",
	indentUnit: top.ICEcoder.indentSize,
	tabSize: top.ICEcoder.indentSize,
	mode: "javascript",
	theme: "<?php echo $ICEcoder["theme"]=="default" ? 'icecoder' : $ICEcoder["theme"];?>"
	});
editor.setSize("400px","330px");

var openNew = function() {
	var cM;

	top.ICEcoder.showHide('hide',top.document.getElementById('blackMask'))
	top.ICEcoder.newTab();
	cM = top.ICEcoder.getcMInstance();
	cM.setValue(editor.getValue());
}

var openDiff = function() {
	var cMDiff;

	top.ICEcoder.showHide('hide',top.document.getElementById('blackMask'))
	top.ICEcoder.setSplitPane('on');
	cMDiff = top.ICEcoder.getcMdiffInstance();
	top.ICEcoder.focus('diff');
	cMDiff.setValue(editor.getValue());
}
</script>

</body>

</html>