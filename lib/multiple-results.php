<?php
include("headers.php");
include("settings.php");
$t = $text['multiple-results'];
?>
<?php
if(isset($_GET['selectedFiles'])) {
	$selectedFiles=explode(":",strClean($_GET['selectedFiles']));
}
?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> multiple results screen</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="multiple-results.css?microtime=<?php echo microtime(true);?>">
</head>

<body class="results" onLoad="top.get('loadingMask').style.visibility = 'hidden'">

<h1 id="title"></h1>
<div class="resultsPane" id="resultsPane">
	<div id="results"></div>
</div>
<?php if (isset($_GET['replace'])) { ?>
	<div class="replaceAll" id="replaceAll" onClick="<?php
	if (isset($_GET['target']) && strpos($_GET['target'],"filenames")) {
		echo 'renameAll()';
	} else if (isset($_GET['target']) && strpos($_GET['target'],"files")) {
		echo 'replaceInFilesAll()';
	} else {
		echo 'replaceAll()';
	}
	?>" style="opacity: 0.1"><?php echo isset($_GET['target']) && strpos($_GET['target'],"filenames") ? $t['rename all'] : $t['replace all'];?></div>
<?php ;}; ?>

<script>
<?php if (!isset($_GET['replace'])) { ?>
	document.getElementById('resultsPane').style.height = "380px";
<?php ;}; ?>
var resultsDisplay = "";
var foundArray = [];
foundInSelected = false;
userTarget = top.document.findAndReplace.target.value;
findText = top.findAndReplace.find.value;
<?php
$findText = str_replace("ICEcoder:","",str_replace("&#39;","\'",$_GET['find']));
// Find in open docs?
if (!isset($_GET['target'])) {
$targetName = $t['document'];
?>
var startTab = top.ICEcoder.selectedTab;
var rExp = new RegExp(findText,"gi");
for (var i=1;i<=top.ICEcoder.openFiles.length;i++) {
	top.ICEcoder.switchTab(i);
	var cM = top.ICEcoder.getcMInstance();
	var content = cM.getValue();
	if (content.match(rExp)) {
		resultsDisplay += '<a href="javascript:gotoTab('+i+')">'+ top.ICEcoder.openFiles[i-1]+ '</a><br><div id="foundCount'+i+'"><?php echo $t['Found'];?> '+content.match(rExp).length+' <?php echo $t['times'];?></div>';
		<?php if (isset($_GET['replace'])) { ?>
		resultsDisplay += '<div class="replace" id="replace" onClick="replaceSingle('+i+');this.style.display=\'none\'"><?php echo $t['replace'];?></div>';
		<?php ;}; ?>
		resultsDisplay += '<hr>';
		foundArray.push(i);
	}
}
if (startTab!=top.ICEcoder.selectedTab) {
	top.ICEcoder.switchTab(startTab);
}
<?php
// Find in files or filenames
} else {
	if (strpos($_GET['target'],"filenames")>0) {
	$targetName = $t['file folder'];
?>
		var spansArray = top.ICEcoder.filesFrame.contentWindow.document.getElementsByTagName('span');
		for (var i=0;i<spansArray.length;i++) {
			foundInSelected = false;
			targetURL = spansArray[i].id.replace(/\|/g,"/").toLowerCase();
			if (	targetURL.lastIndexOf(findText.toLowerCase()) > targetURL.lastIndexOf("/") 
				&& targetURL.indexOf(findText.toLowerCase())>-1 && targetURL.indexOf('_perms')>-1) {
				if (userTarget.indexOf("selected")>-1) {
					for (var j=0;j<top.ICEcoder.selectedFiles.length;j++) {
						if (
							targetURL.replace(/\//g,"|").indexOf(top.ICEcoder.selectedFiles[j].replace(/\//g,"|").replace(/_perms/g,""))==0
							&& (
								targetURL.replace(/\|/g,"/").replace(/_perms/g,"")==top.ICEcoder.selectedFiles[j].replace(/\|/g,"/").replace(/_perms/g,"")
								||
								(targetURL.replace(/\|/g,"/").split("/").length > top.ICEcoder.selectedFiles[j].replace(/\|/g,"/").split("/").length && targetURL.charAt(top.ICEcoder.selectedFiles[j].length)=="/"))) {
							foundInSelected = true;
						}
					}
				}
				if (userTarget.indexOf("all")>-1 || (userTarget.indexOf("selected")>-1 && foundInSelected)) {
					resultsDisplay += '<a href="javascript:top.ICEcoder.openFile(\'<?php echo $docRoot;?>'+targetURL.replace(/\|/g,"/").replace(/_perms/g,"")+'\');top.ICEcoder.goFindAfterOpenInt = setInterval(function(){goFindAfterOpen(\'<?php echo $docRoot;?>'+targetURL.replace(/\|/g,"/").replace(/_perms/g,"")+'\')},20);top.ICEcoder.showHide(\'hide\',top.get(\'blackMask\'))">';
					resultsDisplay += targetURL.replace(/\|/g,"/").replace(/_perms/g,"").replace(/<?php echo str_replace("/","\/",strtolower($findText)); ?>/g,"<b>"+findText.toLowerCase()+"</b>");
					resultsDisplay += '</a><br>';
					<?php if (!isset($_GET['replace'])) { ?>
						resultsDisplay += '<div id="foundCount'+i+'">'+spansArray[i].innerHTML+'</div>';
					<?php ;} else { ?>
						resultsDisplay += '<div id="foundCount'+i+'">'+spansArray[i].innerHTML+', <?php echo $t['rename to'];?> '+targetURL.replace(/\|/g,"/").replace(/_perms/g,"").replace(/<?php echo str_replace("/","\/",strtolower($findText)); ?>/g,"<b><?php if(isset($_GET['replace'])) {echo strtolower(strClean($_GET['replace']));};?></b>")+'</div>';
					<?php
					;};
					if (isset($_GET['replace'])) { ?>
					resultsDisplay += '<div class="replace" id="replace" onClick="renameSingle('+i+');this.style.display=\'none\'"><?php echo $t['rename'];?></div>';
					<?php ;}; ?>
					resultsDisplay += '<hr>';
					foundArray.push(i);
				}
			}
		}
<?php
	} else {
	$targetName = $t['file'];
	$r = 0;
	function phpGrep($q, $path, $base) {
		$fp = opendir($path);
		global $t, $r, $ICEcoder, $serverType, $selectedFiles, $docRoot, $ICEcoderDir, $context;
		if (!isset($ret)) {$ret="";};
		$slash = $serverType == strpos($path,"\\")>-1 ? "\\" : "/";
		while($f = readdir($fp)) {
			if(preg_match("#^\.+$#", $f)) continue;
			$fullPath = $path.$slash.$f;
			if(is_dir($fullPath)) {
				$ret .= phpGrep($q, $fullPath, $base);
			} else if(stristr(toUTF8noBOM(file_get_contents($fullPath,false,$context),false), $q)) {
				$bFile = false;
				$foundInSelFile = false;
				// Exclude banned files
				for ($i=0;$i<count($ICEcoder['bannedFiles']);$i++) {
					if (strpos($f,$ICEcoder['bannedFiles'][$i])!==false) {$bFile = true;};
				}
				// Exclude the folder ICEcoder is running from
				$rootPrefix = '/'.str_replace("/","\/",preg_quote(str_replace("\\","/",$docRoot))).'/';
				$localPath = preg_replace($rootPrefix, '', $fullPath, 1);
				if (strpos($localPath, $ICEcoderDir)===0) {
					$bFile = true;
				}
				$findPath = str_replace($base,"",$fullPath);
				for ($i=0;$i<count($selectedFiles);$i++) {
					$stringExtra = $selectedFiles[$i] != "|" ? "/" : "";
					if (strpos($findPath.$stringExtra,str_replace("|","/",$selectedFiles[$i]).$stringExtra)===0) {
						$foundInSelFile = true;
					}
				}
				if (!$bFile && (count($selectedFiles)==0 || count($selectedFiles)>0 && $foundInSelFile)) {
					$ret .= "<a href=\\\"javascript:top.ICEcoder.openFile('".$fullPath."');top.ICEcoder.goFindAfterOpenInt = setInterval(function(){goFindAfterOpen('".$fullPath."')},20);top.ICEcoder.showHide('hide',top.get('blackMask'))\\\">";
					$ret .= str_replace($base,"",$fullPath)."</a><div id=\\\"foundCount".$r."\\\">".$t['Found']." ".substr_count(strtolower(toUTF8noBOM(file_get_contents($fullPath,false,$context),false)),$q)." ".$t['times']."</div>";
					if (isset($_GET['replace'])) {
						$ret .= "<div class=\\\"replace\\\" id=\\\"replace\\\" onClick=\\\"replaceInFileSingle('".$fullPath."');this.style.display=\'none\'\\\">".$t['replace']."</div>";
					};
					$ret .= '<hr>';
					echo 'foundArray.push("'.$fullPath.'");'.PHP_EOL;
					$r++;
				}
			}
		}
		return $ret;
	}

	$results = phpGrep($findText, $docRoot.$iceRoot, $docRoot.$iceRoot);
	echo 'resultsDisplay += "'.$results.'";';
?>
<?php
	}
}
?>
showHide = foundArray.length==0 ? "hide" : "show";
top.ICEcoder.showHide(showHide,top.get('blackMask'));
if (foundArray.length==0) {top.ICEcoder.message('<?php echo $t['No matches found'];?>')};
<?php if (isset($_GET['replace'])) { ?>
if (foundArray.length!=0) {document.getElementById('replaceAll').style.opacity = 1};
<?php ;}; ?>
plural = foundArray.length >= 2 ? "s" : "";
targetName = "<?php echo $targetName;?>";
selectedText = foundInSelected ? "<?php echo $t['selected'];?> " : "";
document.getElementById('title').innerHTML = findText.replace(/&/g,"&amp;").replace(/>/g,"&gt;").replace(/</g,"&lt;").replace(/"/g,"&quot;").replace(/'/g,"&apos;")+" <?php echo $t['found in'];?> "+foundArray.length+" "+selectedText+targetName+plural;
document.getElementById('results').innerHTML = resultsDisplay;

var gotoTab = function(tab) {
	top.ICEcoder.switchTab(tab);
	top.ICEcoder.showHide('hide',top.get('blackMask'));
}

var replaceSingle = function(tab) {
	top.ICEcoder.switchTab(tab);
	cM = top.ICEcoder.getcMInstance();
	content = cM.getValue();
	cM.setValue(cM.getValue().replace(rExp,top.get('replace').value));
	document.getElementById('foundCount'+tab).innerHTML = document.getElementById('foundCount'+tab).innerHTML.replace('<?php echo $t['Found'];?>','<?php echo $t['Replaced'];?>');
}

var replaceAll = function() {
	for (var i=0;i<=foundArray.length-1;i++) {
		replaceSingle(foundArray[i]);
	}
	top.ICEcoder.showHide('hide',top.get('blackMask'));
}

var replaceInFileSingle = function(fileRef) {
	top.ICEcoder.replaceInFile(fileRef,findText,'<?php if(isset($_GET['replace'])) {echo strClean($_GET['replace']);}; ?>');
}

var replaceInFilesAll = function() {
	for (var i=0;i<=foundArray.length-1;i++) {
		replaceInFileSingle(foundArray[i]);
	}
	top.ICEcoder.showHide('hide',top.get('blackMask'));
}

var renameSingle = function(arrayRef) {
	fileRef = spansArray[arrayRef].id.replace(/\|/g,"/").replace(/_perms/g,"");
	newName = spansArray[arrayRef].id.replace(/\|/g,"/").replace(/_perms/g,"").replace(find,"<?php if(isset($_GET['replace'])) {echo strClean($_GET['replace']);}; ?>");
	top.ICEcoder.renameFile(fileRef,newName);
}

var renameAll = function() {
	for (var i=0;i<=foundArray.length-1;i++) {
		renameSingle(foundArray[i]);
	}
	top.ICEcoder.showHide('hide',top.get('blackMask'));
}

var goFindAfterOpen = function(fileName) {
	if (top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1] == fileName.replace(top.docRoot,"") && !top.ICEcoder.loadingFile) {
		// Change options back to finding only in this document
		top.document.findAndReplace.connector.selectedIndex = 0;
		top.ICEcoder.findReplaceOptions();
		top.document.findAndReplace.target.selectedIndex = 0;
		// Submit to select first instance
		top.document.findAndReplace.submit.click();
		clearInterval(top.ICEcoder.goFindAfterOpenInt);
	}
}
</script>

</body>

</html>