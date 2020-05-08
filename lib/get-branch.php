<?php
if (!isset($ICEcoder['root'])) {
	include "headers.php";
	include "settings.php";
	include "ftp-control.php";
}

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
<title>ICEcoder v <?php echo $ICEcoder["versionNo"];?> get branch</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
</head>

<body>
<?php
// If we're just getting a branch, get that and set as the finalArray
$scanDir = $docRoot . $iceRoot;
$location = "";
echo '<div id="branch" style="display: none">';
$location = str_replace("|", "/", xssClean($_GET['location'], "html"));
if ("/" === $location) {
    $location = "";
};

$dirArray = $filesArray = $finalArray = [];

// Get dir/file list over FTP
if (isset($ftpSite)) {
	ftpStart();
	// Show user warning if no good connection
	if (!$ftpConn || !$ftpLogin) {
		die('<script>parent.parent.ICEcoder.message("Sorry, no FTP connection to ' . $ftpHost . ' for user ' . $ftpUser . '");</script>');
		exit;
	}
	// Get our simple and detailed lists and close the FTP connection
	$ftpList = ftpGetList($ftpConn, $ftpRoot . $location);
	$finalArray = $ftpList['simpleList'];
	$ftpItems = $ftpList['detailedList'];
	ftpEnd();
// or get local list
} else {
	$finalArray = scanDir($scanDir . $location);
}

foreach($finalArray as $entry) {
	$canAdd = true;
	for ($i = 0; $i < count($_SESSION['bannedFiles']); $i++) {
		if ("" != str_replace("*", "", $_SESSION['bannedFiles'][$i]) && false !== strpos($entry, str_replace("*", "", $_SESSION['bannedFiles'][$i]))) {
		    $canAdd = false;
		}
	}
	// Only applicable for local dir, ignoring ICEcoder's dir
	if (!isset($ftpSite) && $docRoot . $iceRoot . $location . "/" . $entry === $docRoot . $ICEcoderDir) {
		$canAdd = false;
	}
	if ("." !== $entry && ".." !== $entry && $canAdd) {
		if (!isset($ftpSite)) {
			is_dir($docRoot . $iceRoot . $location . "/".$entry)
			? array_push($dirArray, $location . "/" . $entry)
			: array_push($filesArray, $location . "/" . $entry);
		} else {
            "directory" === $ftpItems[$entry]['type']
			? array_push($dirArray, $location . "/" . $entry)
			: array_push($filesArray, $location. "/" . $entry);
		}
	}
}
natcasesort($dirArray);
natcasesort($filesArray);

$finalArray = array_merge($dirArray, $filesArray);
for ($i = 0; $i < count($finalArray); $i++) {
	$fileFolderName = str_replace("\\", "/", $finalArray[$i]);
	if (!isset($ftpSite)) {
		$type = is_dir($docRoot . $iceRoot . $fileFolderName) ? "folder" : "file";
	} else {
		$type = "directory" === $ftpItems[basename($fileFolderName)]['type'] ? "folder" : "file";
	}
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
	echo "<li class=\"" . $class . "\" draggable=\"false\" ondragstart=\"parent.ICEcoder.addDefaultDragData(this,event)\" ondrag=\"parent.ICEcoder.draggingWithKeyTest(event);if(parent.ICEcoder.getcMInstance()){parent.ICEcoder.editorFocusInstance.indexOf('diff') == -1 ? parent.ICEcoder.getcMInstance().focus() : parent.ICEcoder.getcMdiffInstance().focus()}\" ondragover=\"parent.ICEcoder.setDragCursor(event,".($type == "folder" ? "'folder'" : "'file'").")\" ondragend=\"parent.ICEcoder.dropFile(this)\"><a nohref title=\"$fileFolderName\" onMouseOver=\"parentNode.draggable=true;parent.ICEcoder.overFileFolder('$type',this.childNodes[1].id)\" onMouseOut=\"parentNode.draggable=false;parent.ICEcoder.overFileFolder('$type','')\" ".

	(("folder" === $type) ? "ondragover=\"if(parentNode.nextSibling && parentNode.nextSibling.tagName != 'UL' && parent.parent.ICEcoder.thisFileFolderLink != this.childNodes[1].id) {parent.ICEcoder.openCloseDir(this,true);}\"" : "") .

	" onClick=\"if(!event.ctrlKey && !parent.ICEcoder.cmdKey) {".

	(("folder" === $type) ? " parent.ICEcoder.openCloseDir(this,$loadParam);" : "") .

	" if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {parent.parent.ICEcoder.openFile()}}\" style=\"position: relative; left:-22px\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id=\"".str_replace($docRoot,"",str_replace("/","|",$fileFolderName))."\">".xssClean(basename($fileFolderName),"html")."</span> ";
	if (!isset($ftpSite)) {
		$thisPermVal = "Linux" === $serverType ? substr(sprintf('%o', fileperms($docRoot . $iceRoot . $fileFolderName)), -3) : '';
	} else {
		// Work out perms value
		$thisPermVal = 0;
		$r = $ftpItems[basename($fileFolderName)]['rights'];
		// Owner
		$thisPermVal += "r" === substr($r,1,1) ? 400 : 0;
		$thisPermVal += "w" === substr($r,2,1) ? 200 : 0;
		$thisPermVal += "x" === substr($r,3,1) ? 100 : 0;
		// Group
		$thisPermVal += "r" === substr($r,4,1) ? 40 : 0;
		$thisPermVal += "w" === substr($r,5,1) ? 20 : 0;
		$thisPermVal += "x" === substr($r,6,1) ? 10 : 0;
		// Public
		$thisPermVal += "r" === substr($r,7,1) ? 4 : 0;
		$thisPermVal += "w" === substr($r,8,1) ? 2 : 0;
		$thisPermVal += "x" === substr($r,9,1) ? 1 : 0;
	}
	$permColors = 777 === $thisPermVal ? 'background: #800; color: #eee' : 'color: #888';
	echo '<span style="' . $permColors . '; font-size: 8px" id="' . str_replace($docRoot, "", str_replace("/", "|", $fileFolderName)) . '_perms">';
	echo $thisPermVal;
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
