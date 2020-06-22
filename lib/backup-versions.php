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
.cm-tab {border-left-width: <?php echo $ICEcoder["visibleTabs"] ? "1px" : "0";?>; margin-left: <?php echo $ICEcoder["visibleTabs"] ? "-1px" : "0";?>; border-left-style: solid; border-left-color: rgba(255,255,255,0.15)}
.cm-trailingspace {
        background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAACCAYAAAB/qH1jAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3QUXCToH00Y1UgAAACFJREFUCNdjPMDBUc/AwNDAAAFMTAwMDA0OP34wQgX/AQBYgwYEx4f9lQAAAABJRU5ErkJggg==);
        background-position: bottom left;
        background-repeat: repeat-x;
      }
.CodeMirror-foldmarker {font-family: arial; line-height: .3; color: #b00; cursor: pointer;
	text-shadow: #fff 1px 1px 2px, #fff -1px -1px 2px, #fff 1px -1px 2px, #fff -1px 1px 2px;
}
.CodeMirror-foldgutter {display: inline-block; width: 13px}
.CodeMirror-foldgutter-open, .CodeMirror-foldgutter-folded {position: absolute; display: inline-block; width: 13px; height: 13px; font-size: 14px; text-align: center; cursor: pointer}
.CodeMirror-foldgutter-open {background: rgba(255,255,255,0.04); color: #666}
.CodeMirror-foldgutter-open:after {position: relative; top: -2px}
.CodeMirror-foldgutter-folded {background: #800; color: #ddd}
.CodeMirror-foldgutter-folded:after {position: relative; top: -3px}
</style>
<link rel="stylesheet" href="../<?php echo $ICEcoder["codeMirrorDir"]; ?>/addon/fold/foldgutter.css?microtime=<?php echo microtime(true);?>">
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
<div style="display: inline-block; height: 500px; width: 210px; overflow-y: scroll">
<?php
$dateCounts = $fileCountInfo['dateCounts'];
$displayVersions = $versions;

// Establish the base, host and date dir parts...
$backupDirHost = isset($ftpSite) ? parse_url($ftpSite,PHP_URL_HOST) : "localhost";

foreach ($dateCounts as $key => $value) {
	echo "<b>".date("jS M Y",strtotime($key))." (".$value." ".($value != 1 ? $t["backups"] : $t["backup"]).")</b>";
	echo '<br>';
	for ($j=0; $j<$value; $j++) {
		echo '<a href="backup-versions-preview-loader.php?file='.str_replace("/","|",$backupDirHost.'/'.$key.$file).' ('.($value-$j).')&csrf='.$_SESSION['csrf'].'" onclick="highlightVersion('.$displayVersions.')" id="backup-'.$displayVersions.'" target="previewLoader">Backup '.$displayVersions.'</a><br>';
		$displayVersions--;
	}
	echo '<br>';
}
?>
</div>
<div style="display: inline-block; width: 480px; height: 550px; margin-left: 20px">
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
var highlightVersion = function(elem) {
	for (var i=versions; i>=1; i--) {
		document.getElementById('backup-'+i).style.color = i==elem
			? 'rgba(0,198,255,0.7)'
			: null;
	}
}

<?php
echo "fileName = '".basename($file)."';";
include(dirname(__FILE__)."/language-modes-partial.js");
?>

var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
	mode: mode,
	lineNumbers: top.ICEcoder.lineNumbers,
	gutters: ["CodeMirror-foldgutter","CodeMirror-lint-markers","CodeMirror-linenumbers"],
	foldGutter: {gutter: "CodeMirror-foldgutter"},
	foldOptions: {minFoldSize: 1},
	lineWrapping: top.ICEcoder.lineWrapping,
	indentWithTabs: top.ICEcoder.indentWithTabs,
	indentUnit: top.ICEcoder.indentSize,
	tabSize: top.ICEcoder.indentSize,
	matchBrackets: top.ICEcoder.matchBrackets,
	electricChars: false,
	highlightSelectionMatches: true,
	showTrailingSpace: top.ICEcoder.showTrailingSpace,
	lint: false,
	readOnly: "nocursor",
	theme: "<?php echo $ICEcoder["theme"]=="default" ? 'icecoder' : $ICEcoder["theme"];?>"
	});
editor.setSize("480px","500px");

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

var restoreVersion = function() {
	var cM;

	if (top.ICEcoder.ask("To confirm - this will paste the displayed backup content to your current tab and save, OK?")) {
		top.ICEcoder.showHide('hide',top.document.getElementById('blackMask'))
		cM = top.ICEcoder.getcMInstance();
		top.ICEcoder.focus();
		cM.setValue(editor.getValue());
		top.ICEcoder.saveFile();
	}
}
</script>

</body>

</html>
