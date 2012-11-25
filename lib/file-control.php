<?php include("settings.php");?>
<?php
// Get the save type if any
$saveType = "";
if (isset($_GET['saveType'])) {$saveType = strClean($_GET['saveType']);};

// Establish the filename/new filename
$file = str_replace("|","/",strClean(
	isset($_POST['newFileName']) && $_POST['newFileName']!=""
	? $_POST['newFileName']
	: $_GET['file']
	));

// Make $file a full path and establish the $fileLoc and $fileName
if (strpos($file,$docRoot)===false) {$file=str_replace("|","/",$docRoot.$iceRoot.$file);};
$fileLoc = substr(str_replace($docRoot,"",$file),0,strrpos(str_replace($docRoot,"",$file),"/"));
$fileName = basename($file);

// If we're due to open a file...
if ($_GET['action']=="load") {
	echo '<script>action="load";</script>';

	// Determine what to do based on mime type
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	if (strpos(finfo_file($finfo, $file),"text")===0) {
		if (file_exists($file)) {
			echo '<script>fileType="text";';
			echo 'top.ICEcoder.shortURL = top.ICEcoder.rightClickedFile = top.ICEcoder.thisFileFolderLink = "'.$fileLoc."/".$fileName.'";';
			echo '</script>';
			$loadedFile = file_get_contents($file);
			echo '<textarea name="loadedFile" id="loadedFile">'.str_replace("</textarea>","<ICEcoder:/:textarea>",htmlentities($loadedFile)).'</textarea>';
		} else {
			echo '<script>fileType="nothing"; top.ICEcoder.message(\'Sorry, '.$fileLoc."/".$fileName.' doesn\\\'t seem to exist on the server\');</script>';
		}
	} else if (strpos(finfo_file($finfo, $file),"image")===0) {
		echo '<script>fileType="image";fileName=\''.$fileLoc."/".$fileName.'\'</script>';
	} else {
		echo '<script>fileType="other";window.open(\'http://'.$_SERVER['SERVER_NAME'].$fileLoc."/".$fileName.'\');</script>';
	};
	finfo_close($finfo);
};

// If we're due to add a new folder...
if ($_GET['action']=="newFolder") {
	if (!$demoMode && is_writable($docRoot.$fileLoc)) {
		mkdir($file, 0705);
		// Reload file manager
		echo '<script>top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'add\',\''.$fileLoc.'\',\''.$fileName.'\');action="newFolder";</script>';
	} else {
		echo "<script>action='nothing'; top.ICEcoder.message('Sorry, cannot create folder at\\n".$fileLoc."')</script>";
	}
	echo '<script>top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);</script>';
}

// If we're due to paste a new file...
if ($_GET['action']=="paste") {
	$source = $file;
	$dest = $docRoot.strClean(str_replace("|","/",$_GET['location']))."/".basename($source);
	if (!$demoMode && is_writable(dirname($dest))) {
		if (is_dir($source)) {
			if (!is_dir($dest)) {
				mkdir($dest, 0705);
			}
			foreach ($iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
				RecursiveIteratorIterator::SELF_FIRST) as $item
				) {
				if ($item->isDir()) {
					mkdir($dest.DIRECTORY_SEPARATOR.$iterator->getSubPathName(), 0705);
				} else {
					copy($item, $dest.DIRECTORY_SEPARATOR.$iterator->getSubPathName());
				}
			}
		} else {
			copy($source, $dest);
		}
		// Reload file manager
		echo '<script>top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'add\',\''.strClean(str_replace("|","/",$_GET['location'])).'\',\''.$fileName.'\');action="pasteFile";</script>';
	} else {
		echo "<script>action='nothing'; top.ICEcoder.message('Sorry, cannot copy \\n".str_replace($docRoot,"",$source)."\\n into \\n".str_replace($docRoot,"",$dest)."')</script>";
	}
	echo '<script>top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);</script>';
}

// If we're due to rename a file/folder...
if ($_GET['action']=="rename") {
	if (!$demoMode && is_writable($docRoot.$iceRoot.str_replace("|","/",strClean($_GET['oldFileName'])))) {
		rename($docRoot.$iceRoot.str_replace("|","/",strClean($_GET['oldFileName'])),$docRoot.$fileLoc."/".$fileName);
		// Reload file manager
		echo '<script>top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'rename\',\''.$fileLoc.'\',\''.$fileName.'\',\'\',\''.str_replace($iceRoot,"",strClean($_GET['oldFileName'])).'\');';
		echo 'action="rename";</script>';
	} else {
		echo "<script>action='nothing'; top.ICEcoder.message('Sorry, cannot rename\\n".strClean($_GET['oldFileName'])."');</script>";
	}
	echo '<script>top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);</script>';
}

// If we're due to replace text in a file...
if ($_GET['action']=="replaceText") {
	if (!$demoMode && is_writable(str_replace("|","/",strClean($_GET['fileRef'])))) {
		$file = str_replace("|","/",strClean($_GET['fileRef']));
		$loadedFile = file_get_contents($file);
		$newContent = str_replace(strClean($_GET['find']),strClean($_GET['replace']),$loadedFile);
		$fh = fopen($file, 'w') or die("Sorry, cannot save");
		fwrite($fh, $newContent);
		fclose($fh);
		echo '<script>action="replaceText";</script>';
	} else {
		echo "<script>action='nothing'; top.ICEcoder.message('Sorry, cannot replace text in\\n".strClean($_GET['fileRef'])."');</script>";
	}
	echo '<script>top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);</script>';
}

// If we're due to change permissions on a file/folder...
if ($_GET['action']=="perms") {
	if (!$demoMode && is_writable($file)) {
		chmod($file,octdec(numClean($_GET['perms'])));
		// Reload file manager
		echo '<script>top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'chmod\',\''.$fileLoc.'\',\''.$fileName.'\',\''.numClean($_GET['perms']).'\');';
		echo 'action="perms";</script>';
	} else {
		echo "<script>action='nothing'; top.ICEcoder.message('Sorry, cannot change permissions on \\n".strClean($file)."');</script>";
	}
	echo '<script>top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);</script>';
}

// If we're due to delete a file...
if ($_GET['action']=="delete") {
	$filesArray = explode(";",$file); // May contain more than one file here
	for ($i=0;$i<=count($filesArray)-1;$i++) {
		if (!$demoMode && is_writable($iceRoot.$filesArray[$i])) {
			is_dir($iceRoot.$filesArray[$i])
				? rrmdir($iceRoot.$filesArray[$i])
				: unlink($iceRoot.$filesArray[$i]);
			// Reload file manager
			echo '<script>top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'delete\',\''.$fileLoc.'\',\''.$fileName.'\');';
			echo 'action="delete";</script>';
		} else {
			echo "<script>top.ICEcoder.message('Sorry can\\'t delete\\n".$filesArray[$i]."');</script>";
		}
		echo '<script>action="nothing";</script>';
	}
	echo '<script>top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);</script>';
}

// The function to recursively remove folders & files
function rrmdir($dir) { 
	if (is_dir($dir)) { 
		$objects = scandir($dir); 
		foreach ($objects as $object) { 
			if ($object != "." && $object != "..") { 
				filetype($dir."/".$object) == "dir" 
					? rrmdir($dir."/".$object)
					: unlink($dir."/".$object); 
			} 
		} 
		reset($objects); 
	rmdir($dir); 
	} 
} 

if ($_GET['action']=="save") {
	echo '<script>action="save";</script>';
	// on the form posting via a reload, save the file
	if (isset($_POST['contents'])) {
		if (!$demoMode && ((file_exists($file) && is_writable($file)) || isset($_POST['newFileName']) && $_POST['newFileName']!="")) {
			if (filemtime($file)==$_GET['fileMDT']||!(isset($_GET['fileMDT']))) {
				$fh = fopen($file, 'w') or die("Sorry, cannot save");
				fwrite($fh, $_POST['contents']);
				fclose($fh);
				clearstatcache();
				echo '<script>top.ICEcoder.openFileMDTs[top.ICEcoder.selectedTab-1]="'.filemtime($file).'";</script>';
				// Reload file manager & rename tab if it was a new file
				if (isset($_POST['newFileName']) && $_POST['newFileName']!="") {
					echo '<script>top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'add\',\''.$fileLoc.'\',\''.$fileName.'\');';
					echo 'top.ICEcoder.renameTab(top.ICEcoder.selectedTab,\''.$fileLoc."/".$fileName.'\');</script>';
				}
				// Reload stickytab window
				echo '<script>if (top.ICEcoder.stickyTab.location) {top.ICEcoder.stickyTab.location.reload()};action="doneSave";</script>';
			} else {
				$loadedFile = file_get_contents($file);
				echo '<textarea name="loadedFile" id="loadedFile">'.str_replace("</textarea>","<ICEcoder:/:textarea>",htmlentities($loadedFile)).'</textarea>';
				echo '<textarea name="userVersionFile" id="userVersionFile"></textarea>';
				?>
				<script>
				var refreshFile = top.ICEcoder.ask('Sorry, this file has changed, cannot save\n<?php echo $file;?>\n\nReload this file and copy your version to a new document?');
				if (refreshFile) {
					var cM = top.ICEcoder.getcMInstance();
					var thisTab = top.ICEcoder.selectedTab;
					document.getElementById('userVersionFile').value = cM.getValue();
					// Revert back to original
					cM.setValue(document.getElementById('loadedFile').value);
					top.ICEcoder.changedContent[thisTab-1] = 0;
					top.ICEcoder.openFileMDTs[top.ICEcoder.selectedTab-1] = "<?php echo filemtime($file); ?>";
					cM.clearHistory();
					// Now for the new file
					top.ICEcoder.newTab();
					cM = top.ICEcoder.getcMInstance();
					cM.setValue(document.getElementById('userVersionFile').value);
					cM.clearHistory();
					// Finally, switch back to original tab
					top.ICEcoder.switchTab(thisTab);
				}
				action='nothing';
				</script>
				<?php
			}
        	} else {
			echo "<script>action='nothing';top.ICEcoder.message('Sorry, cannot write\\n".$file."')</script>";
		}
	echo '<script>top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);</script>';
	}
};
?>
<script>
if (action=="load") {
	if (fileType=="text") {
		setTimeout(function() {
			if (!top.ICEcoder.content.contentWindow.createNewCMInstance) {
				console.log('There was tech hiccup, likely something wasn\'t quite ready. So ICEcoder reloaded it\'s file control again.');
				window.location.reload();
			} else {
				top.ICEcoder.loadingFile = true;
				// Reset the various states back to their initial setting
				selectedTab = top.ICEcoder.openFiles.length;	// The tab that's currently selected

				// Finally, store all data, show tabs etc
				top.ICEcoder.createNewTab();
				top.ICEcoder.cMInstances.push(top.ICEcoder.nextcMInstance);
				top.ICEcoder.setLayout();
				top.ICEcoder.content.contentWindow.createNewCMInstance(top.ICEcoder.nextcMInstance);

				// Set the value & innerHTML of the code textarea to that of our loaded file plus make it visible (it's hidden on _coder's load)
				top.ICEcoder.switchMode();
				cM = top.ICEcoder.getcMInstance();
				cM.setValue(document.getElementById('loadedFile').value);
				top.document.getElementById('content').style.visibility='visible';
				top.ICEcoder.switchTab(top.ICEcoder.selectedTab);
				cM.focus();

				// Then clean it up, set the text cursor, update the display and get the character data
				top.ICEcoder.contentCleanUp();
				top.ICEcoder.content.contentWindow['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].setLineClass(top.ICEcoder['cMActiveLine'+top.ICEcoder.selectedTab], null);
				top.ICEcoder['cMActiveLine'+top.ICEcoder.selectedTab] = top.ICEcoder.content.contentWindow['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].setLineClass(0, "cm-s-activeLine");
				top.ICEcoder.nextcMInstance++;
				top.ICEcoder.openFileMDTs.push('<?php echo filemtime($file); ?>');
				top.ICEcoder.loadingFile = false;
			}
		},4);
	}

	if (fileType=="image") {
		top.document.getElementById('blackMask').style.visibility = "visible";
		top.document.getElementById('mediaContainer').innerHTML = "<img src=\"<?php echo $fileLoc."/".$fileName;?>\" class=\"whiteGlow\" style=\"border: solid 10px #fff; max-width: 700px; max-height: 500px\" onClick=\"return false\"><br><span class=\"whiteGlow\" style=\"border: solid 10px #fff; color: #000; background-color: #fff\" onClick=\"return false\"><?php echo $fileLoc."/".$fileName;?></span>";
	}

	top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);
}
</script>

<form name="saveFile" action="file-control.php?action=save&file=<?php if (isset($file)) {echo $file;}; if (isset($_GET['fileMDT']) && $_GET['fileMDT']!="undefined") {echo "&fileMDT=".numClean($_GET['fileMDT']);};?>" method="POST">
	<textarea name="contents"></textarea>
	<input type="hidden" name="newFileName" value="">
</form>

<script>
if (action=="save") {
	<?php
	if (strpos($file,"[NEW]")>0||$saveType=="saveAs") {
		if (strpos($fileName,"[NEW]")>0) {echo "fileLoc = '".$fileLoc."';";} else {echo "fileLoc = '';";};
	?>
		newFileName = top.ICEcoder.getInput(fileLoc != ""
			? 'Enter filename to save at '+fileLoc
			: 'Enter filename (including path, prefixed with /)'
			,'');
		if (newFileName.substr(0,1)!="/") {newFileName = "/" + newFileName}
		if (newFileName) {
			newFileName = fileLoc == "" ? newFileName : fileLoc + "/" + fileName;
		}
		if (newFileName && top.document.getElementById('filesFrame').contentWindow.document.getElementById(newFileName.replace(/\//g,"|"))) {
			overwriteOK = top.ICEcoder.ask('That file exists already, overwrite?');
		}
		document.saveFile.newFileName.value = '<?php echo $docRoot; ?>' + newFileName;
	<?php ;};?>
	if ("undefined" == typeof newFileName || (newFileName && "undefined" == typeof overwriteOK) || ("undefined" != typeof overwriteOK && overwriteOK)) {
		top.ICEcoder.serverMessage('<b>Saving</b><br>'+ <?php echo strpos($file,"[NEW]")>0 ? "newFileName" : "'$file'"; ?>);
		document.saveFile.contents.value = top.document.getElementById('saveTemp1').value;
		document.saveFile.submit();
	} else {
		top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);
		action=="nothing";
	}
}
</script>

<script>
if (action=="doneSave") {
	top.ICEcoder.changedContent[top.ICEcoder.selectedTab-1] = 0;
	top.ICEcoder.redoTabHighlight(top.ICEcoder.selectedTab);
}
</script>