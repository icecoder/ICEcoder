<!DOCTYPE html>

<body>
<script>
top.ICEcoder.serverMessage("<b>Zipping Open Files</b>");
</script>
<?php
// ------------------------------------
// backupOpenFiles by Matt Pass
// Will backups open files in ICE coder
// ------------------------------------

// If we have no post data for the 1st item, set a form up ready to post back
if (!isset($_POST['fileName1'])) {
echo '<form name="zipFiles" id="zipFiles" action="index.php" method="POST">'.PHP_EOL;
for ($i=1;$i<=10;$i++) {
	echo '<input type="hidden" name="fileName'.$i.'" id="fName'.$i.'" value="" style="display: none">'.PHP_EOL;
	echo '<textarea name="fileContent'.$i.'" id="fContent'.$i.'" style="display: none"></textarea>'.PHP_EOL;
}
echo '</form>'.PHP_EOL;
?>
<script>
// Populate values for file names & contents
for (i=1;i<=top.ICEcoder.openFiles.length;i++) {
	document.getElementById('fName'+i).value = top.ICEcoder.openFiles[i-1];
	document.getElementById('fContent'+i).value = top.ICEcoder.content.contentWindow['cM'+i].getValue();
}
if (top.ICEcoder.openFiles.length>0) {
	document.zipFiles.submit();
}
</script>
<?php
;} else {
// Folder name & filename of the zip
$zipItSaveLocation = '../../backups/';
$zipItFileName = time().'.zip';

// Make the dir if it doesn't exist
if (!is_dir($zipItSaveLocation)) {
	mkdir($zipItSaveLocation, 0777);
}

// Start a class
Class zipIt {
	public function zipFilesUp($zipName='') {
		// OK, if we've got at least one file to add
		if($_POST['fileName1']!=="") {
			$zip = new ZipArchive();
			// Return if we have a problem creating/overwriting our zip
	    		if($zip->open($zipName,$overwriteZip ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return false;
			}

			// Otherwise, we're OK to add our string into a new file (incl dirs) in the zip, so do that
			for($i=1;$i<=10;$i++) {
				$fName = 'fileName'.$i;
				if($_POST[$fName]!=="") {
					$zip->addFromString(ltrim($_POST[$fName],"/"), $_POST['fileContent'.$i]);
				}
			}

			$zip->close();

			// We've been successful, so return with a positive response
			return file_exists($zipName);

		} else {
			// If we had 0 files, return
			return false;
		}
	}
}

// Trigger the class
$zipItDoZip = new zipIt();
$zipItAddToZip = $zipItDoZip->zipFilesUp($zipItSaveLocation.$zipItFileName);
if (!$zipItAddToZip) {echo '<script>alert("Could not zip files up!");</script>';};
;};
?>

<script>
top.ICEcoder.serverMessage();
top.ICEcoder.serverQueue("del",0);
</script>
</body>

</html>