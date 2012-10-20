<?php include("lib/settings.php");?>
<!DOCTYPE html>

<html onMouseDown="top.ICEcoder.mouseDown=true" onMouseUp="top.ICEcoder.mouseDown=false" onMouseMove="if(top.ICEcoder) {top.ICEcoder.getMouseXY(event,'files');top.ICEcoder.canResizeFilesW()}" onContextMenu="top.ICEcoder.rightClickedFile=top.ICEcoder.thisFileFolderLink; return top.ICEcoder.showMenu()" onClick="top.ICEcoder.selectFileFolder()">
<head>
<title>ICEcoder v <?php echo $ICEcoder["versionNo"];?> beta file manager</title>
<link rel="stylesheet" type="text/css" href="lib/files.css">
<script src="lib/ice-coder.js" type="text/javascript"></script>
</head>

<body onLoad="top.ICEcoder.fileManager()" onDblClick="top.ICEcoder.openFile()" onKeyDown="return top.ICEcoder.interceptKeys('files', event);" onKeyUp="top.ICEcoder.resetKeys(event);">

<div title="Refresh" onClick="top.ICEcoder.refreshFileManager()" class="refresh"></div>

<?php
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
$objectList = new SortingIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($docRoot.$iceRoot), RecursiveIteratorIterator::SELF_FIRST), 'alphasort');

// With that done, create arrays for out final ordered list and a temp container of files
$finalArray = $tempArray =  array();

// To start, push folders from object into finalArray, files into tempArray
foreach ($objectList as $objectRef) {
	$fileFolderName = substr($objectRef->getPathname(), strlen($docRoot.$iceRoot));
	$canAdd = true;
	for ($i=0;$i<count($_SESSION['bannedFiles']);$i++) {
		if(strpos($fileFolderName,$_SESSION['bannedFiles'][$i])!==false) {$canAdd = false;}
	}
	if ($objectRef->getFilename()!="." && $objectRef->getFilename()!=".." && $fileFolderName[strlen($fileFolderName)-1]!="/" && $canAdd) {
		$fileFolderName!="/" && is_dir($docRoot.$iceRoot.$fileFolderName)
		? array_push($finalArray,$fileFolderName)
		: array_push($tempArray,$fileFolderName);
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
			strpos(dirname($finalArray[$j]),dirname($tempArray[$i]))===0) {
			$insertAt++;
		}
	}
	array_splice($finalArray, $insertAt, 0, $tempArray[$i]);
}

// Finally, we have our ordered list, so display in a UL
$fileAtts = "";
if ($serverType=="Linux") {
	$chmodInfo = substr(sprintf('%o', fileperms($docRoot.$iceRoot)), -3);
	$fileAtts = '<span style="color: #888; font-size: 8px" id="|_perms">'.$chmodInfo.'</span>';
}
?>
<ul class="fileManager">
<li class="pft-directory">
<a nohref title="/" onMouseOver="top.ICEcoder.overFileFolder('folder','/')" onMouseOut="top.ICEcoder.overFileFolder('folder','')" style="position: relative; left:-22px">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<span id="|">/ 
<?php echo $iceRoot == "" ? "[ROOT]" : trim($iceRoot,"/");?>
</span> 
<?php echo $fileAtts;?>
</a>
</li>
<?php
$lastPath="";
$fileCount=0;
$fileBytes=0;
$dirCount=0;
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
	if ($thisDepth > $lastDepth) {echo "<ul>\n";}
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
	echo "<li class=\"".$class."\"><a nohref title=\"$fileFolderName\" onMouseOver=\"top.ICEcoder.overFileFolder('$type','".str_replace($docRoot,"",str_replace("/","|",$fileFolderName))."')\" onMouseOut=\"top.ICEcoder.overFileFolder('$type','')\" style=\"position: relative; left:-22px\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id=\"".str_replace($docRoot,"",str_replace("/","|",$fileFolderName))."\">".basename($fileFolderName)."</span> ".$fileAtts."</a>\n";
	if ($i<count($finalArray)) {echo "</li>\n";}
	$lastPath = $fileFolderName;
}
echo "</ul>\n</ul>\n";

	// Output the JS vars
	echo "<script>\n";
	echo "top.ICEcoder.dirCount=";
	echo $dirCount ? $dirCount : "0";
	echo ";\ntop.ICEcoder.fileCount=";
	echo $fileCount ? $fileCount : "0";
	echo ";\ntop.ICEcoder.fileBytes=";
	echo $fileBytes ? $fileBytes : "0";
	echo ";\n</script>";
?>

<iframe name="fileControl" style="display: none"></iframe>
		
</body>
	
</html>