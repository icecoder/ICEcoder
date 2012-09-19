<?php include("settings.php");?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> multiple results screen</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="multiple-results.css">
</head>

<body class="results">

<h1 id="title"></h1>
<div class="resultsPane" id="resultsPane">
	<div id="results"></div>
</div>
<?php if (isset($_GET['replace'])) { ?>
	<div class="replaceAll" id="replaceAll" onClick="<?php
	if (strpos($_GET['target'],"filenames")) {
		echo 'renameAll()';
	} else if (strpos($_GET['target'],"files")) {
		echo 'replaceInFilesAll()';
	} else {
		echo 'replaceAll()';
	}
	?>" style="opacity: 0.1"><?php echo strpos($_GET['target'],"filenames") ? 'rename all' : 'replace all';?></div>
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
$targetName = "document";
?>
var startTab = top.ICEcoder.selectedTab;
var rExp = new RegExp(decodeURIComponent(findText),"g");
for (var i=1;i<=top.ICEcoder.openFiles.length;i++) {
	top.ICEcoder.switchTab(i);
	var cM = top.ICEcoder.getcMInstance();
	var content = cM.getValue();
	if (content.match(rExp)) {
		resultsDisplay += '<a href="javascript:gotoTab('+i+')">'+ top.ICEcoder.openFiles[i-1]+ '</a><br><div id="foundCount'+i+'">Found '+content.match(rExp).length+' times</div>';
		<?php if (isset($_GET['replace'])) { ?>
		resultsDisplay += '<div class="replace" id="replace" onClick="replaceSingle('+i+');this.style.display=\'none\'">replace</div>';
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
	$targetName = "file/folder";
?>
		var spansArray = top.ICEcoder.filesFrame.contentWindow.document.getElementsByTagName('span');
		for (var i=0;i<spansArray.length;i++) {
			targetURL = spansArray[i].id.replace(/\|/g,"/");
			if (targetURL.indexOf(findText)>-1 && targetURL.indexOf('_perms')>-1) {
				if (userTarget.indexOf("selected")>-1) {
					for (var j=0;j<top.ICEcoder.selectedFiles.length;j++) {
						if (top.ICEcoder.selectedFiles[j].indexOf(targetURL.replace(/\//g,"|").replace(/_perms/g,""))>-1) {
							foundInSelected = true;
						}
					}
				}
				if (userTarget.indexOf("all")>-1 || (userTarget.indexOf("selected")>-1 && foundInSelected)) {
					resultsDisplay += '<a href="javascript:top.ICEcoder.openFile(\'<?php echo $docRoot;?>'+targetURL.replace(/\|/g,"/").replace(/_perms/g,"")+'\');top.ICEcoder.showHide(\'hide\',top.document.getElementById(\'blackMask\'))">'+ targetURL.replace(/\|/g,"/").replace(/_perms/g,"").replace(/<?php echo str_replace("/","\/",$findText); ?>/g,"<b>"+findText+"</b>")+ '</a><br><div id="foundCount'+i+'">'+spansArray[i].innerHTML+', rename to '+targetURL.replace(/\|/g,"/").replace(/_perms/g,"").replace(/<?php echo str_replace("/","\/",$findText); ?>/g,"<b><?php echo strClean($_GET['replace']);?></b>")+'</div>';
					<?php if (isset($_GET['replace'])) { ?>
					resultsDisplay += '<div class="replace" id="replace" onClick="renameSingle('+i+');this.style.display=\'none\'">rename</div>';
					<?php ;}; ?>
					resultsDisplay += '<hr>';
					foundArray.push(i);
				}
			}
		}
<?php
	} else {
	$targetName = "file";
	$r = 0;
	function phpGrep($q, $path, $base) {
		$fp = opendir($path);
		$slash = $serverType == "Windows" ? "\\" : "/";
		global $r;
		global $ICEcoder;
		while($f = readdir($fp)) {
			if(preg_match("#^\.+$#", $f)) continue;
			$fullPath = $path.$slash.$f;
			if(is_dir($fullPath)) {
				$ret .= phpGrep($q, $fullPath, $base);
			} else if(stristr(file_get_contents($fullPath), $q)) {
				$rFile = false;
				$bFile = false;
				for ($i=0;$i<count($ICEcoder["restrictedFiles"]);$i++) {
					if (strpos($f,$ICEcoder['restrictedFiles'][$i])>0) {$rFile = true;};
				}
				for ($i=0;$i<count($ICEcoder['bannedFiles']);$i++) {
					if (strpos($f,$ICEcoder['bannedFiles'][$i])>0) {$bFile = true;};
				}
				if (!$rFile && !$bFile) {
					$ret .= "<a href=\\\"javascript:top.ICEcoder.openFile('".$fullPath."');top.ICEcoder.showHide('hide',top.document.getElementById('blackMask'))\\\">";
					$ret .= str_replace($base,"",$fullPath)."</a><div id=\\\"foundCount".$r."\\\">Found ".substr_count(strtolower(file_get_contents($fullPath)),$q)." times</div>";
					if (isset($_GET['replace'])) {
						$ret .= "<div class=\\\"replace\\\" id=\\\"replace\\\" onClick=\\\"replaceInFileSingle('".$fullPath."');this.style.display=\'none\'\\\">replace</div>";
					};
					$ret .= '<hr>';
					echo 'foundArray.push("'.$fullPath.'");';
					$r++;
				}
			}
		}
		return $ret;
	}

	$results = phpGrep($findText, $docRoot.$ICEroot, $docRoot.$ICEroot);
	echo 'resultsDisplay += "'.$results.'";';
?>
<?php
	}
}
?>
foundArray.length==0 ? showHide = "hide" : showHide = "show";
top.ICEcoder.showHide(showHide,top.document.getElementById('blackMask'));
if (foundArray.length==0) {top.ICEcoder.message('No matches found')};
<?php if (isset($_GET['replace'])) { ?>
if (foundArray.length!=0) {document.getElementById('replaceAll').style.opacity = 1};
<?php ;}; ?>
foundArray.length >= 2 ? plural = "s" : plural = "";
targetName = "<?php echo $targetName;?>";
foundInSelected ? selectedText = "selected " : selectedText = "";
document.getElementById('title').innerHTML = findText.replace(/&/g,"&amp;").replace(/>/g,"&gt;").replace(/</g,"&lt;").replace(/"/g,"&quot;").replace(/'/g,"&apos;")+" found in "+foundArray.length+" "+selectedText+targetName+plural;
document.getElementById('results').innerHTML = resultsDisplay;

var gotoTab = function(tab) {
	top.ICEcoder.switchTab(tab);
	top.ICEcoder.showHide('hide',top.document.getElementById('blackMask'));
}

var replaceSingle = function(tab) {
	top.ICEcoder.switchTab(tab);
	cM = top.ICEcoder.getcMInstance();
	content = cM.getValue();
	cM.setValue(cM.getValue().replace(rExp,top.document.getElementById('replace').value));
	document.getElementById('foundCount'+tab).innerHTML = document.getElementById('foundCount'+tab).innerHTML.replace('Found','Replaced');
}

var replaceAll = function() {
	for (var i=0;i<=foundArray.length-1;i++) {
		replaceSingle(foundArray[i]);
	}
	top.ICEcoder.showHide('hide',top.document.getElementById('blackMask'));
}

var replaceInFileSingle = function(fileRef) {
	top.ICEcoder.replaceInFile(fileRef,findText,'<?php echo strClean($_GET['replace']); ?>');
}

var replaceInFilesAll = function() {
	for (var i=0;i<=foundArray.length-1;i++) {
		replaceInFileSingle(foundArray[i]);
	}
	top.ICEcoder.showHide('hide',top.document.getElementById('blackMask'));
}

var renameSingle = function(arrayRef) {
	fileRef = spansArray[arrayRef].id.replace(/\|/g,"/").replace(/_perms/g,"");
	newName = spansArray[arrayRef].id.replace(/\|/g,"/").replace(/_perms/g,"").replace(find,"<?php echo strClean($_GET['replace']); ?>");
	top.ICEcoder.renameFile(fileRef,newName);
}

var renameAll = function() {
	for (var i=0;i<=foundArray.length-1;i++) {
		renameSingle(foundArray[i]);
	}
	top.ICEcoder.showHide('hide',top.document.getElementById('blackMask'));
}
</script>

</body>

</html>