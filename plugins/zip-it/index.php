<?php
// -----------------------------------------------
// Zip-It! for ICEcoder v0.9.4 by Matt Pass
// Will backup requested files/folders in ICEcoder
// and remove old backups older than $keepLastDays
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
if ($_GET['zip']=="|") {$zipItFileName = "root";} else {$zipItFileName = str_replace("|","_",strClean($_GET['zip']));};
$zipItFileName .= '-'.time().'.zip';
$keepLastDays = 7;

if (!is_dir($zipItSaveLocation)) {mkdir($zipItSaveLocation, 0777);}
Class zipIt {
	public function zipFilesUp($zipDir,$zipFile,$keepLastDays,$docRoot) {
		$zipName = $zipDir.$zipFile;
		$zipFiles = array();
		$_GET['zip']=="|" ? $zipTgt = "" : $zipTgt = str_replace("|","/",strClean($_GET['zip']));
		if (strpos($_GET['zip'],"/")!==0) {$zipTgt = "/".$zipTgt;};
		$addItem = $docRoot.$zipTgt;

		if (is_dir($addItem)) {
			$dirStack = array($addItem);
			while (!empty($dirStack)) {
				$currentDir = array_pop($dirStack);
				$dir = dir($currentDir);
				while (false !== ($node = $dir->read())) {
					if (($node == '.') || ($node == '..')) {continue;}
					if (is_dir($currentDir.$node) && !strpos($currentDir.$node,"_coder") && !strpos($currentDir.$node,"ICEcoder")) { 
						array_push($dirStack,$currentDir.$node.'/'); 
					}
					if (is_file($currentDir.$node)) {$zipFiles[] = $currentDir.$node;} 
				}
			}
		} else {
			if(file_exists($addItem)) {$zipFiles[] = $addItem;}
		}
		if ($backupsDir = opendir($zipDir)) {
			$keepTime = $keepLastDays*60*60*24;
			while (false !== ($backup = readdir($backupsDir))) {
				if ($backup != "." && $backup != "..") {
					if ((time()-filemtime($zipDir.$backup)) > $keepTime) {
						chmod($zipDir.$backup, 0777);
						unlink($zipDir.$backup) or DIE("couldn't delete $zipDir$backup<br>");
					}
				}
			}
			closedir($backupsDir);
		}
		if(count($zipFiles)) {
			$zip = new ZipArchive();
	    		if($zip->open($zipName,ZIPARCHIVE::CREATE)!== true) {return false;}
			$excludeFilesFolders = explode("*",strClean($_GET['exclude']));
			foreach($zipFiles as $file) {
				$canAdd=true;
				for ($i=0;$i<count($excludeFilesFolders);$i++) {
					if(strpos($file,$excludeFilesFolders[$i])!==false) {$canAdd=false;};
				}
				if ($canAdd==true) {
					$zip->addFile($file,str_replace($docRoot."/","",$file));
				}
			}
			$zip->close();
			chmod($zipName, 0777);
			return file_exists($zipName);
		} else {
			return false;
		}
	}
}
if($_SESSION['userLevel']==10) {
	$zipItDoZip = new zipIt();
	echo '<script>top.ICEcoder.serverMessage("<b>Zipping Files</b>");</script>';
	$zipItAddToZip = $zipItDoZip->zipFilesUp($zipItSaveLocation,$zipItFileName,$keepLastDays,$docRoot);
	if (!$zipItAddToZip) {
		echo '<script>top.ICEcoder.message("Could not zip files up!");</script>';
	} else {
		echo '<script>setTimeout(function(){top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);},500);</script>';
	}
}
?>
</body>
</html>