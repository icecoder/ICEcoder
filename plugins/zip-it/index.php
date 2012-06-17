<?php
// -----------------------------------------------
// Zip-It! for ICEcoder v0.9.0 by Matt Pass
// Will backup requested files/folders in ICEcoder
// -----------------------------------------------
include("../../lib/settings.php");
?>
<!DOCTYPE html>

<html>
<head>
<title>Zip It! for ICEcoder</title>
</head>

<body>
<?
$zipItSaveLocation = '../../backups/';
if ($_GET['zip']=="|") {$zipItFileName = "root";} else {$zipItFileName = str_replace("|","_",$_GET['zip']);};
$zipItFileName .= '-'.time().'.zip';

if (!is_dir($zipItSaveLocation)) {mkdir($zipItSaveLocation, 0777);}

Class zipIt {
	public function zipFilesUp($zipName='') {
		$zipFiles = array();
		$zipTgt = str_replace("|","/",$_GET['zip']);
		if (strpos($_GET['zip'],"/")!==0) {$zipTgt = "/".$zipTgt;};
		$addItem = $_SERVER['DOCUMENT_ROOT'].$zipTgt;
		if (is_dir($addItem)) {
			$dirStack = array($addItem);
			while (!empty($dirStack)) {
				$currentDir = array_pop($dirStack);
				$dir = dir($currentDir);
				while (false !== ($node = $dir->read())) {
					if (($node == '.') || ($node == '..')) {continue;}
					if (is_dir($currentDir.$node) && !strpos($currentDir.$node,"_coder")) { 
						array_push($dirStack,$currentDir.$node.'/'); 
					}
					if (is_file($currentDir.$node)) {$zipFiles[] = $currentDir.$node;} 
				}
			}
		} else {
			if(file_exists($addItem)) {$zipFiles[] = $addItem;}
		}
		if(count($zipFiles)) {
			$zip = new ZipArchive();
	    		if($zip->open($zipName,ZIPARCHIVE::CREATE)!== true) {return false;}
			foreach($zipFiles as $file) {
				$zip->addFile($file,str_replace($_SERVER['DOCUMENT_ROOT']."/","",$file));
			}
			$zip->close();
			return file_exists($zipName);
		} else {
			return false;
		}
	}
}
if($_SESSION['userLevel']==10) {
	$zipItDoZip = new zipIt();
	echo '<script>top.ICEcoder.serverMessage("<b>Zipping Files</b>");</script>';
	$zipItAddToZip = $zipItDoZip->zipFilesUp($zipItSaveLocation.$zipItFileName);
	if (!$zipItAddToZip) {
		echo '<script>alert("Could not zip files up!");</script>';
	} else {
		echo '<script>setTimeout(function(){top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);},500);</script>';
	}
}
?>
</body>

</html>