<!DOCTYPE html>

<html onMouseDown="top.ICEcoder.mouseDown=true" onMouseUp="top.ICEcoder.mouseDown=false" onMouseMove="if(top.ICEcoder) {top.ICEcoder.getMouseXY(event,'files');top.ICEcoder.canResizeFilesW()}" onContextMenu="top.ICEcoder.rightClickedFile=top.ICEcoder.thisFileFolderLink; return top.ICEcoder.showMenu()" onClick="top.ICEcoder.selectFileFolder()">
<head>
<title>ICEcoder File Manager</title>
<link rel="stylesheet" type="text/css" href="lib/files.css">
<script src="lib/coder.js" type="text/javascript"></script>
</head>

<body onLoad="top.ICEcoder.fileManager()" onDblClick="top.ICEcoder.openFile()" onKeyDown="return top.ICEcoder.interceptKeys('files', event);" onKeyUp="top.ICEcoder.resetKeys(event);">
<div class="refresh" onClick="top.ICEcoder.refreshFileManager()"><img src="images/refresh.png"></div>

<?php
include("lib/settings.php");
$ICEcoder["restrictedFiles"]  = $_SESSION['restrictedFiles'];
$ICEcoder["bannedFiles"]  = $_SESSION['bannedFiles'];
$serverType = strrpos($_SERVER['DOCUMENT_ROOT'],":") ? "Windows" : "Linux";

// Function to sort given values alphabetically
function alphasort($a, $b) {
	return strcasecmp($a->getPathname(), $b->getPathname());
}

// Class to put forward the values for sorting
class SortingIterator implements IteratorAggregate {
	private $iterator = null;
	public function __construct(Traversable $iterator, $callback) {
		$array = iterator_to_array($iterator);
		usort($array, $callback);
		$this->iterator = new ArrayIterator($array);
	}
	public function getIterator() {
		return $this->iterator;
	}
}

// Get a full list of dirs & files and begin sorting using above class & function
$path = $ICEcoder["root"];
$objectList = new SortingIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST), 'alphasort');

// With that done, create arrays for out final ordered list and a temp container of files
$finalArray = $tempArray =  array();

// To start, push folders from object into finalArray, files into tempArray
foreach ($objectList as $objectRef) {
	$fileFolderName = substr($objectRef->getPathname(), strlen($path));
	$canAdd = true;
	for ($i=0;$i<count($ICEcoder["bannedFiles"]);$i++) {
		if(strpos($fileFolderName,$ICEcoder["bannedFiles"][$i])!==false) {$canAdd = false;}
	}
	if ($objectRef->getFilename()!="." && $objectRef->getFilename()!=".." && $fileFolderName[strlen($fileFolderName)-1]!="/" && $canAdd) {
		$fileFolderName!="/" && is_dir($path.$fileFolderName) ? array_push($finalArray,$fileFolderName) : array_push($tempArray,$fileFolderName);
	}
}

// Now push root files onto the end of finalArray and splice from the temp, leaving only files that reside in subdirs
for ($i=0;$i<count($tempArray);$i++) {
	if (count(explode("/",$tempArray[$i]))==2) {
		array_push($finalArray,$tempArray[$i]);
		array_splice($tempArray,$i,1);
		$i--;
	}
}

// Lastly we push remaining files into the right subdirs in finalArray
for ($i=0;$i<count($tempArray);$i++) {
	$insertAt = array_search(dirname($tempArray[$i]),$finalArray)+1;
	for ($j=$insertAt;$j<count($finalArray);$j++) {
		if (	strcasecmp(dirname($finalArray[$j]), dirname($tempArray[$i]))==0 &&
			strcasecmp(basename($finalArray[$j]), basename($tempArray[$i]))<0 ||
			strstr(dirname($finalArray[$j]),dirname($tempArray[$i]))) {
			$insertAt++;
		}
	}
	array_splice($finalArray, $insertAt, 0, $tempArray[$i]);
}

// Finally, we have our ordered list, so display in a UL
$fileAtts = "";
if ($serverType=="Linux") {
	$chmodInfo = substr(sprintf('%o', fileperms($path)), -3);
	$fileAtts = '<span style="color: #888; font-size: 8px" id="|_perms">'.$chmodInfo.'</span>';
}
echo "<ul class=\"fileManager\">\n";
echo "<li class=\"pft-directory\">";
echo "<a href=\"#\" title=\"/\" onMouseOver=\"top.ICEcoder.overFileFolder('folder','$path/')\" onMouseOut=\"top.ICEcoder.overFileFolder('folder','')\" style=\"position: relative; left:-22px\">";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";
echo "<span id=\"|\">/ ";
echo $path == $_SERVER['DOCUMENT_ROOT'] ? "[ROOT]" : str_replace($_SERVER['DOCUMENT_ROOT']."/","",$path);
echo "</span> ";
echo $fileAtts;
echo "</a>";
echo "</li>\n";
$lastPath="";
$fileCount=0;
$fileBytes=0;
$dirCount=0;
for ($i=0;$i<count($finalArray);$i++) {
	$fileFolderName = str_replace("\\","/",$finalArray[$i]);
	$type = is_dir($path.$fileFolderName) ? "folder" : "file";
	$type=="folder" ? $dirCount++ : $fileCount++;
	if (!is_dir($path.$fileFolderName)) {
		$fileBytes+=filesize($path.$fileFolderName);
		// Get extension (prefix 'ext-' to prevent invalid classes from extensions that begin with numbers)
		$ext = "ext-".pathinfo($path.$fileFolderName, PATHINFO_EXTENSION);
	}
	$thisDepth = count(explode("/",$fileFolderName));
	$lastDepth = count(explode("/",$lastPath));
	if ($thisDepth > $lastDepth) {echo "<ul>\n";}
	if ($thisDepth < $lastDepth) {
		for ($j=$lastDepth;$j>$thisDepth;$j--) {
			echo "</ul>\n";
		}
	}
	$restrictedFile=false;
	for ($j=0;$j<count($ICEcoder["restrictedFiles"]);$j++) {
		if (strpos($fileFolderName,$ICEcoder["restrictedFiles"][$j])!="") {
			$restrictedFile=true;
		}
	}
	if ($serverType=="Linux") {
		$chmodInfo = substr(sprintf('%o', fileperms($path.$fileFolderName)), -3);
		$fileAtts = '<span style="color: #888; font-size: 8px" id="'.str_replace("/","|",$fileFolderName).'_perms">'.$chmodInfo.'</span>';
	}
	$type == "folder" ? $class = 'pft-directory' : $class = 'pft-file '.strtolower($ext);
	if ($_SESSION['userLevel'] == 10 || ($_SESSION['userLevel'] < 10 && !$restrictedFile)) {
		echo "<li class=\"".$class."\"><a href=\"#\" title=\"$fileFolderName\" onMouseOver=\"top.ICEcoder.overFileFolder('$type','$path$fileFolderName')\" onMouseOut=\"top.ICEcoder.overFileFolder('$type','')\" style=\"position: relative; left:-22px\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id=\"".str_replace("/","|",$fileFolderName)."\">".basename($fileFolderName)."</span> ".$fileAtts."</a>\n";
	} else {
		if ($type == "file") {$fileAtts = "<img src=\"images/padlock.png\" style=\"cursor: pointer\" onClick=\"top.ICEcoder.message('Sorry, you need higher admin level rights to view.')\">";}
		echo "<li class=\"".$class."\" style=\"cursor: default\"><span style=\"position: relative; left:-22px; color: #888\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [HIDDEN] ".$fileAtts."</span>\n";
	}
	if ($i<count($finalArray)) {echo "</li>\n";}
	$lastPath = $fileFolderName;
}
echo "</ul>\n</ul>\n";

	echo "<script>\n";
	$varOutput = "top.ICEcoder.dirCount=";
	$dirCount ? $varOutput .= $dirCount.";\n" : $varOutput .= "0;\n";
	$varOutput .= "top.ICEcoder.fileCount=";
	$fileCount ? $varOutput .= $fileCount.";\n" : $varOutput .= "0;\n";
	$varOutput .= "top.ICEcoder.fileBytes=";
	$fileBytes ? $varOutput .= $fileBytes.";\n" : $varOutput .= "0;\n";
	// Output the JS vars
	echo $varOutput;
	echo "</script>\n";
?>

<iframe name="fileControl" style="display: none"></iframe>
		
</body>
	
</html>