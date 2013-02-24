<?php
include_once("lib/settings.php");

if (!$_SESSION['loggedIn']) {
	header("Location: ../");
}

// If we're just getting a branch, get that and set as the finalArray
if ($ICEcoder['treeType'] == "branch" || $ICEcoder['treeType'] == "branchReload") {
	$scanDir = ($docRoot && $iceRoot) ? $docRoot.$iceRoot : $_SERVER['DOCUMENT_ROOT'];
	$location = "";
	if (isset($_GET['location'])) {
		$location = str_replace("|","/",$_GET['location']);
	}

	$dirArray = $filesArray = $finalArray = array();
	$finalArray = scanDir($scanDir.$location);
	foreach($finalArray as $entry) {
		$canAdd = true;
		for ($i=0;$i<count($_SESSION['bannedFiles']);$i++) {
			if(strpos($entry,$_SESSION['bannedFiles'][$i])!==false) {$canAdd = false;}
		}
		if ($entry != "." && $entry != ".." && $canAdd) {
			is_dir($docRoot.$iceRoot."/".$entry)
			? array_push($dirArray,"/".$entry)
			: array_push($filesArray,"/".$entry);
		}
	}
	natcasesort($dirArray);
	natcasesort($filesArray);
	$finalArray = array_merge($dirArray,$filesArray);
}

for ($i=0;$i<count($finalArray);$i++) {
	$fileFolderName = str_replace("\\","/",$finalArray[$i]);
	$type = is_dir($docRoot.$iceRoot.$fileFolderName) ? "folder" : "file";
	$type=="folder" ? $dirCount++ : $fileCount++;
	if ($type=="file") {
		$fileBytes+=filesize($docRoot.$iceRoot.$fileFolderName);
		// Get extension (prefix 'ext-' to prevent invalid classes from extensions that begin with numbers)
		$ext = "ext-".pathinfo($docRoot.$iceRoot.$fileFolderName, PATHINFO_EXTENSION);
	}
	$thisDepth = count(explode("/",$fileFolderName));
	$lastDepth = count(explode("/",$lastPath));
	$ulDisplay = $i==0 ?  ' style="display: block"' : ' style="display: none"';
	if ($thisDepth > $lastDepth) {echo "<ul".$ulDisplay.">\n";}
	if ($thisDepth < $lastDepth) {
		for ($j=$lastDepth;$j>$thisDepth;$j--) {
			echo "</ul>\n";
		}
	}
	if ($serverType=="Linux") {
		$chmodInfo = substr(sprintf('%o', fileperms($docRoot.$iceRoot.$fileFolderName)), -3);
		$fileAtts = '<span style="color: #888; font-size: 8px" id="'.str_replace($docRoot,"",str_replace("/","|",$fileFolderName)).'_perms">'.$chmodInfo.'</span>';
	}
	$type == "folder" ? $class = 'pft-directory' : $class = 'pft-file '.strtolower($ext);
	echo "<li class=\"".$class."\"><a nohref title=\"$fileFolderName\" onMouseOver=\"top.ICEcoder.overFileFolder('$type','".str_replace($docRoot,"",str_replace("/","|",$fileFolderName))."')\" onMouseOut=\"top.ICEcoder.overFileFolder('$type','')\" onClick=\"top.ICEcoder.openCloseDir(this)\" style=\"position: relative; left:-22px\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id=\"".str_replace($docRoot,"",str_replace("/","|",$fileFolderName))."\">".basename($fileFolderName)."</span> ".$fileAtts."</a>\n";
	if ($i<count($finalArray)) {echo "</li>\n";}
	$lastPath = $fileFolderName;
}
?>