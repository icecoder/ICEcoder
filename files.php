<?php include("lib/settings.php");?>
<!DOCTYPE html>

<html onMouseDown="top.ICEcoder.mouseDown=true" onMouseUp="top.ICEcoder.mouseDown=false; top.ICEcoder.tabDragEnd()" onMouseMove="if(top.ICEcoder) {top.ICEcoder.getMouseXY(event,'files');top.ICEcoder.canResizeFilesW()}" onContextMenu="top.ICEcoder.rightClickedFile=top.ICEcoder.thisFileFolderLink; return top.ICEcoder.showMenu()" onClick="top.ICEcoder.selectFileFolder()">
<head>
<title>ICEcoder v <?php echo $ICEcoder["versionNo"];?> file manager</title>
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="lib/files.css">
<script src="lib/ice-coder.js" type="text/javascript"></script>
</head>

<body onDblClick="top.ICEcoder.openFile()" onKeyDown="return top.ICEcoder.interceptKeys('files', event);" onKeyUp="top.ICEcoder.resetKeys(event);">

<div title="Refresh" onClick="top.ICEcoder.refreshFileManager()" class="refresh"></div>

<?php
// Function to sort given values alphabetically
function alphasort($a, $b) {
	$specialChars = array("$","-","_",".","+","!","*","'","(",")",",");
	return strcasecmp(str_replace($specialChars,"\\",$a->getPathname()), str_replace($specialChars,"\\",$b->getPathname()));
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

class IgnorantRecursiveDirectoryIterator extends RecursiveDirectoryIterator {
	function getChildren() {
		try {
			if (!isset($GLOBALS['ICEcoder']['bannedPaths']) || 
			!in_array($this->key(), $GLOBALS['ICEcoder']['bannedPaths'])) {
				return parent::getChildren();
			} else {
				return new RecursiveArrayIterator(array());
			}
    		} catch(UnexpectedValueException $e) {
			return new RecursiveArrayIterator(array());
		}
	}
}

// Get a full list of dirs & files and begin sorting using above class & function
$objectList = new SortingIterator(new RecursiveIteratorIterator(new IgnorantRecursiveDirectoryIterator($docRoot.$iceRoot), RecursiveIteratorIterator::SELF_FIRST), 'alphasort');

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
			(strpos(dirname($finalArray[$j]),dirname($tempArray[$i]))===0 && substr($finalArray[$j],strlen(dirname($tempArray[$i])),1)=="/")) {
			$insertAt++;
		}
	}
	array_splice($finalArray, $insertAt, 0, $tempArray[$i]);
}

include("lib/get-tree.php");

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