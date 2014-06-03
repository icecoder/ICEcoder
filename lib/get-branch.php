<?php
if (!isset($ICEcoder['root'])) {
	include("headers.php");
	include("settings.php");
}

if (!$_SESSION['loggedIn']) {
	header("Location: ../");
}

// If we're just getting a branch, get that and set as the finalArray
$scanDir = $docRoot.$iceRoot;
$location = "";
if (isset($_GET['location'])) {
	echo '<div id="branch" style="display: none">';
	$location = str_replace("|","/",$_GET['location']);
}
if ($location=="/") {$location = "";};

$dirArray = $filesArray = $finalArray = array();
$finalArray = scanDir($scanDir.$location);
foreach($finalArray as $entry) {
	$canAdd = true;
	for ($i=0;$i<count($_SESSION['bannedFiles']);$i++) {
		if($_SESSION['bannedFiles'][$i] != "" && strpos($entry,$_SESSION['bannedFiles'][$i])!==false) {$canAdd = false;}
	}
	if ("/".$entry == $ICEcoderDir) {
		$canAdd = false;
	}
	if ($entry != "." && $entry != ".." && $canAdd) {
		is_dir($docRoot.$iceRoot.$location."/".$entry)
		? array_push($dirArray,$location."/".$entry)
		: array_push($filesArray,$location."/".$entry);
	}
}
natcasesort($dirArray);
natcasesort($filesArray);

$finalArray = array_merge($dirArray,$filesArray);
for ($i=0;$i<count($finalArray);$i++) {
	$fileFolderName = str_replace("\\","/",$finalArray[$i]);
	$type = is_dir($docRoot.$iceRoot.$fileFolderName) ? "folder" : "file";
	if ($type=="file") {
		// Get extension (prefix 'ext-' to prevent invalid classes from extensions that begin with numbers)
		$ext = "ext-".pathinfo($docRoot.$iceRoot.$fileFolderName, PATHINFO_EXTENSION);
	}
	if ($i==0) {echo "<ul style=\"display: block\">\n";}
	if ($i==count($finalArray)-1 && isset($_GET['location'])) {
		echo "</ul>\n";
	}
	$type == "folder" ? $class = 'pft-directory' : $class = 'pft-file '.strtolower($ext);
	$loadParam = $type == "folder" ? "true" : "false";
	echo "<li class=\"".$class."\" draggable=\"true\" ondrag=\"top.ICEcoder.draggingWithKeyTest(event);if(top.ICEcoder.getcMInstance()){top.ICEcoder.getcMInstance().focus()}\" ondragend=\"top.ICEcoder.dropFile(this)\"><a nohref title=\"$fileFolderName\" onMouseOver=\"top.ICEcoder.overFileFolder('$type',this.childNodes[1].id)\" onMouseOut=\"top.ICEcoder.overFileFolder('$type','')\" onClick=\"if(!event.ctrlKey && !top.ICEcoder.cmdKey) {top.ICEcoder.openCloseDir(this,$loadParam); if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {top.ICEcoder.openFile()}}\" style=\"position: relative; left:-22px\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id=\"".str_replace($docRoot,"",str_replace("/","|",$fileFolderName))."\">".xssClean(basename($fileFolderName),"html")."</span> ";
	echo '<span style="color: #888; font-size: 8px" id="'.str_replace($docRoot,"",str_replace("/","|",$fileFolderName)).'_perms">';
	echo $serverType=="Linux" ? substr(sprintf('%o', fileperms($docRoot.$iceRoot.$fileFolderName)), -3) : '';
	echo "</span></a></li>\n";
}

if (isset($_GET['location'])) {
?>
	</div>
	<script>
	targetElem = top.ICEcoder.filesFrame.contentWindow.document.getElementById('<?php echo $_GET['location'];?>');
	newUL = document.createElement("ul");
	newUL.style = "display: block";
	locNest = targetElem.parentNode.parentNode;
	if(locNest.nextSibling && locNest.nextSibling.tagName=="UL") {
		x = locNest.nextSibling;
		x.parentNode.removeChild(x);
	}
	folderContent = document.getElementById('branch').innerHTML;
	if (folderContent.indexOf('<ul')>-1 || folderContent.indexOf('<li')>-1) {
		// Now animate folders & files into view
		i=0;
		animFolders = setInterval(function() {
			i++;
			showContent = "";
			folderItems = folderContent.split("\n");
			for (j=0; j<=i; j++) {
				showContent += folderItems[j];
				if (j<i) {showContent += "\n";};
			}
			showContent = showContent.slice(28);
			if (j==folderItems.length) {
				clearInterval(animFolders);
				showContent = showContent.slice(0,-2);
				setTimeout(function(){top.ICEcoder.redoTabHighlight(top.ICEcoder.selectedTab);},4);
				if (!top.ICEcoder.fmReady) {top.ICEcoder.fmReady=true;};
			}
			newUL.innerHTML = showContent;
			locNest.parentNode.insertBefore(newUL,locNest.nextSibling);
		},4);
	} else {
		<?php
		$iceGithubLocalPaths = $ICEcoder["githubLocalPaths"];
		$iceGithubRemotePaths = $ICEcoder["githubRemotePaths"];
		$pathPos = array_search($iceRoot,$iceGithubLocalPaths);
		if ($pathPos !== false) {
		?>
			if (top.ICEcoder.ask("Your local folder is empty, would you like to clone <?php echo $iceGithubRemotePaths[$pathPos];?>?")) {
				setTimeout(function() {
					top.ICEcoder.showHide('show',top.get('loadingMask'));
					top.ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href = "lib/github.php?action=clone&csrf="+top.ICEcoder.csrf;
				},4);
			}
		<?php ;}; ?>
	}
	</script>
<?php
;};
?>