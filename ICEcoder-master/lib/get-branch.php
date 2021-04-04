<?php
require "icecoder.php";

if (!$_SESSION['loggedIn']) {
	header("Location: ../");
	die();
}

$text = $_SESSION['text'];
$t = $text['get-branch'];
?>
<!DOCTYPE html>
<html>
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> get branch</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
</head>

<body>
<?php
// If we're just getting a branch, get that and set as the finalArray
$scanDir = $docRoot . $iceRoot;

echo '<div id="branch" style="display: none">';
$location = str_replace("|", "/", xssClean($_GET['location'], "html"));
if ("/" === $location) {
    $location = "";
};

$dirArray = $filesArray = [];
$finalArray = scanDir($scanDir . $location);

foreach($finalArray as $entry) {
	$canAdd = true;
	for ($i = 0; $i < count($_SESSION['bannedFiles']); $i++) {
		if ("" != str_replace("*", "", $_SESSION['bannedFiles'][$i]) && false !== strpos($entry, str_replace("*", "", $_SESSION['bannedFiles'][$i]))) {
		    $canAdd = false;
		}
	}
	// Ignore ICEcoder's dir
	if ($docRoot . $iceRoot . $location . "/" . $entry === $docRoot . $ICEcoderDir) {
		$canAdd = false;
	}
	if ("." !== $entry && ".." !== $entry && $canAdd) {
		is_dir($docRoot . $iceRoot . $location . "/".$entry)
			? array_push($dirArray, $location . "/" . $entry)
			: array_push($filesArray, $location . "/" . $entry);
	}
}
natcasesort($dirArray);
natcasesort($filesArray);

$finalArray = array_merge($dirArray, $filesArray);
for ($i = 0; $i < count($finalArray); $i++) {
	$fileFolderName = str_replace("\\", "/", $finalArray[$i]);
	$type = is_dir($docRoot . $iceRoot . $fileFolderName) ? "folder" : "file";
	if ("file" === $type) {
		// Get extension (prefix 'ext-' to prevent invalid classes from extensions that begin with numbers)
		$ext = "ext-" . pathinfo($docRoot . $iceRoot . $fileFolderName, PATHINFO_EXTENSION);
	}
	if (0 === $i) {echo "<ul style=\"display: block\">\n";}
	if ($i === count($finalArray) - 1 && isset($_GET['location'])) {
		echo "</ul>";
	}
    $class = "folder" === $type ? 'pft-directory' : 'pft-file ' . strtolower($ext);
	$loadParam = "folder" === $type ? "true" : "false";
	echo "<li class=\"" . $class . "\" draggable=\"false\" ondragstart=\"parent.ICEcoder.addDefaultDragData(this,event)\" ondrag=\"parent.ICEcoder.draggingWithKeyTest(event); if (parent.ICEcoder.getcMInstance()) {parent.ICEcoder.editorFocusInstance.indexOf('diff') == -1 ? parent.ICEcoder.getcMInstance().focus() : parent.ICEcoder.getcMdiffInstance().focus()}\" ondragover=\"parent.ICEcoder.setDragCursor(event,".($type == "folder" ? "'folder'" : "'file'").")\" ondragend=\"parent.ICEcoder.dropFile(this)\"><a nohref title=\"$fileFolderName\" onMouseOver=\"parentNode.draggable=true;parent.ICEcoder.overFileFolder('$type',this.childNodes[1].id)\" onMouseOut=\"parentNode.draggable=false;parent.ICEcoder.overFileFolder('$type','')\" ".

	(("folder" === $type)
        ? "ondragover=\"parent.ICEcoder.overFileFolder('folder', this.childNodes[1].id); parent.ICEcoder.highlightFileFolder(this.childNodes[1].id, true); if(parentNode.nextSibling && parentNode.nextSibling.tagName != 'UL' && parent.ICEcoder.thisFileFolderLink != this.childNodes[1].id) {parent.ICEcoder.openCloseDir(this,true);}\""
        : "ondragover=\"parent.ICEcoder.overFileFolder('file', this.childNodes[1].id); parent.ICEcoder.highlightFileFolder(this.parentNode.parentNode.previousSibling.childNodes[0].childNodes[1].id, true);\""
    ) .

    (("folder" === $type)
        ? "ondragleave=\"parent.ICEcoder.highlightFileFolder(this.childNodes[1].id, false);\""
        : "ondragleave=\"parent.ICEcoder.highlightFileFolder(this.parentNode.parentNode.previousSibling.childNodes[0].childNodes[1].id, false); \""
    ) .

	" onClick=\"if(!event.ctrlKey && !parent.ICEcoder.cmdKey) {".

	(("folder" === $type) ? " parent.ICEcoder.openCloseDir(this,$loadParam);" : "") .

	" if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {parent.ICEcoder.openFile()}}\" style=\"position: relative; left:-22px\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id=\"".str_replace($docRoot,"",str_replace("/","|",$fileFolderName))."\">".xssClean(basename($fileFolderName),"html")."</span> ";
	$thisPermVal = "Windows" !== $serverType
		? intval(substr(sprintf('%o', fileperms($docRoot . $iceRoot . $fileFolderName)), -3))
		: 0;
	$permColors = 777 === $thisPermVal ? 'background: #800; color: #eee' : 'color: #888';
	echo '<span style="' . $permColors . '; font-size: 8px" id="' . str_replace($docRoot, "", str_replace("/", "|", $fileFolderName)) . '_perms">';
	echo 0 !== $thisPermVal ? $thisPermVal : '';
	echo "</span></a></li>\n";
}

echo '	</div>';
?>
	<script>
	targetElem = parent.parent.ICEcoder.filesFrame.contentWindow.document.getElementById('<?php echo xssClean($_GET['location'], "html");?>');
	newUL = document.createElement("ul");
	newUL.style = "display: block";
	locNest = targetElem.parentNode.parentNode;
	if(locNest.nextSibling && "UL" === locNest.nextSibling.tagName) {
		x = locNest.nextSibling;
		x.parentNode.removeChild(x);
	}
	folderContent = document.getElementById('branch').innerHTML;
	folderItems = folderContent.split("\n");

	showFiles = function() {
		// Now display folders & files

		// Animate into view?
		if (folderItems.length <= 50) {
			showFileI = 0;
			animFolders = setInterval(function() {
				showFileI++;
				showNextFile('progressive');
			},4);
		// Display immediately
		} else {
			showFileJ = folderItems.length;
			showContent = folderContent;
			showNextFile();
		}
	}

	showNextFile = function(progressive) {
		if (progressive) {
			showContent = "";
			for (showFileJ = 0; showFileJ <= showFileI; showFileJ++) {
				showContent += folderItems[showFileJ];
				if (showFileJ < showFileI) {showContent += "\n";};
			}
		}
		showContent = showContent.slice(28);
		if (showFileJ == folderItems.length) {
			// If we've been animating into view, clear that interval
			if ("undefined" != typeof animFolders) {clearInterval(animFolders);};
			showContent = showContent.slice(0, -2);
			setTimeout(function(){parent.parent.ICEcoder.redoTabHighlight(parent.parent.ICEcoder.selectedTab);}, 4);
			if (!parent.parent.ICEcoder.fmReady) {parent.parent.ICEcoder.fmReady = true;};
		}
		newUL.innerHTML = showContent;
		locNest.parentNode.insertBefore(newUL,locNest.nextSibling);
	}

	// Show files here
	if (-1 < folderContent.indexOf('<ul') || -1 < folderContent.indexOf('<li')) {
		showFiles();
	}
	</script>
</body>
</html>
