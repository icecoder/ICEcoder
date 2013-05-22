<?php include("settings.php");?>
<?php
// Get the save type if any
$saveType = isset($_GET['saveType']) ? strClean($_GET['saveType']) : "";

// Establish the filename/new filename
$file = str_replace("|","/",strClean(
	isset($_POST['newFileName']) && $_POST['newFileName']!=""
	? $_POST['newFileName']
	: $_GET['file']
	));

// Trim any +'s or spaces from the end of file
$file = rtrim(rtrim($file,'+'),' ');

// Make $file a full path and establish the $fileLoc and $fileName
if (strpos($file,$docRoot)===false && $_GET['action']!="getRemoteFile") {$file=str_replace("|","/",$docRoot.$iceRoot.$file);};
$fileLoc = substr(str_replace($docRoot,"",$file),0,strrpos(str_replace($docRoot,"",$file),"/"));
$fileName = basename($file);

// If we're due to open a file...
if ($_GET['action']=="load") {
	echo '<script>action="load";</script>';

	if (file_exists($file)) {
		$finfo = "";
		// Determine what to do based on mime type
		if (function_exists('finfo_open')) {
			$finfoMIME = finfo_open(FILEINFO_MIME_TYPE);
			$finfo = finfo_file($finfoMIME, $file);
			finfo_close($finfoMIME);
		} else {
			$fileExt = pathinfo($file, PATHINFO_EXTENSION);
			if (array_search($fileExt,array("coffee","css","htm","html","js","less","md","php","py","rb","ruby","txt","xml"))!==false) {$finfo = "text";};
			if (array_search($fileExt,array("gif","jpg","jpeg","png"))!==false) {$finfo = "image";};
		}
		if (strpos($finfo,"text")===0 || strpos($finfo,"empty")!==false) {
			echo '<script>fileType="text";';
			echo 'top.ICEcoder.shortURL = top.ICEcoder.rightClickedFile = top.ICEcoder.thisFileFolderLink = "'.$fileLoc."/".$fileName.'";';
			echo '</script>';
			$loadedFile = toUTF8noBOM(file_get_contents($file),true);
			echo '<textarea name="loadedFile" id="loadedFile">'.str_replace("</textarea>","<ICEcoder:/:textarea>",str_replace("&","&amp;",$loadedFile)).'</textarea>';
		} else if (strpos($finfo,"image")===0) {
			echo '<script>fileType="image";fileName=\''.$fileLoc."/".$fileName.'\'</script>';
		} else {
			echo '<script>fileType="other";window.open(\'http://'.$_SERVER['SERVER_NAME'].$fileLoc."/".$fileName.'\');</script>';
		};
	} else {
		echo '<script>fileType="nothing"; top.ICEcoder.message(\'Sorry, '.$fileLoc."/".$fileName.' doesn\\\'t seem to exist on the server\');</script>';
	}

};

// Get the contents of a remote URL
if ($_GET['action']=="getRemoteFile") {
	if ($remoteFile = toUTF8noBOM(file_get_contents($file),true)) {
		// replace \r\n (Windows), \r (old Mac) and \n (Linux) line endings with whatever we chose to be lineEnding
		$remoteFile = str_replace("\r\n", $ICEcoder["lineEnding"], $remoteFile);
		$remoteFile = str_replace("\r", $ICEcoder["lineEnding"], $remoteFile);
		$remoteFile = str_replace("\n", $ICEcoder["lineEnding"], $remoteFile);
		echo '<script>top.ICEcoder.newTab();</script>';
		echo '<textarea name="remoteFile" id="remoteFile">'.str_replace("</textarea>","<ICEcoder:/:textarea>",str_replace("&","&amp;",$remoteFile)).'</textarea>';
		echo '<script>top.ICEcoder.getcMInstance().setValue(document.getElementById("remoteFile").value);action="getRemoteFile";</script>';
	} else {
		echo '<script>action="nothing"; top.ICEcoder.message(\'Sorry, could\\\'t get contents of '.$file.'\');</script>';
	}
	echo '<script>top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);</script>';
}

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
	$dest = str_replace("//","/",$docRoot.strClean(str_replace("|","/",$_GET['location']))."/".basename($source));
	if (!$demoMode && is_writable(dirname($dest))) {
		if (is_dir($source)) {
			if (!is_dir($dest)) {
				mkdir($dest, 0705);
			} else {
				for ($i=2; $i<1000000000; $i++) {
					if (!is_dir($dest." (".$i.")")) {
						$dest = $dest." (".$i.")";
						mkdir($dest, 0705);
						$i=1000000000;
					}
				}
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
			if (!file_exists($dest)) {
				copy($source, $dest);
			} else {
				for ($i=2; $i<1000000000; $i++) {
					if (!file_exists($dest." (".$i.")")) {
						$dest = $dest." (".$i.")";
						copy($source, $dest);
						$i=1000000000;
					}
				}
			}
		}
		// Reload file manager
		echo '<script>top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'add\',\''.strClean(str_replace("|","/",$_GET['location'])).'\',\''.basename($dest).'\');action="pasteFile";</script>';
	} else {
		echo "<script>action='nothing'; top.ICEcoder.message('Sorry, cannot copy \\n".str_replace($docRoot,"",$source)."\\n into \\n".str_replace($docRoot,"",$dest)."')</script>";
	}
	echo '<script>top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);</script>';
}

// If we're due to upload files...
if ($_GET['action']=="upload") {
	if (!$demoMode) {
		class fileUploader {  
			public function __construct($uploads) {  
				global $docRoot;
				$uploadDir=$docRoot.$iceRoot.str_replace("..","",str_replace("|","/",strClean($_POST['folder'])."/"));
				foreach($uploads as $current) {  
					$this->uploadFile=$uploadDir.$current->name;
					$fileName = $current->name;
					if ($this->upload($current,$this->uploadFile)) {
						echo '<script>action="upload"; top.ICEcoder.updateFileManagerList(\'add\',top.ICEcoder.rightClickedFile.replace(/\|/g,\'/\'),\''.$fileName.'\',false,false,true); top.ICEcoder.serverMessage("Uploaded file(s) OK");setTimeout(function(){top.ICEcoder.serverMessage();},2000);</script>';
					} else {
						echo "<script>action='nothing'; top.ICEcoder.message('Sorry, cannot upload \\n".$fileName."\\n into \\n'+top.ICEcoder.rightClickedFile.replace(/\|/g,'/'))</script>";
					}
				}  
			}  

			public function upload($current,$uploadFile){ 
				if(move_uploaded_file($current->tmp_name,$uploadFile)){  
					return true;  
				}  
			}  
		}

		function getDetails($fileArr) {
			foreach($fileArr['name'] as $keyee => $info) {
				$uploads[$keyee]->name=$fileArr['name'][$keyee];  
				$uploads[$keyee]->type=$fileArr['type'][$keyee];  
				$uploads[$keyee]->tmp_name=$fileArr['tmp_name'][$keyee];  
				$uploads[$keyee]->error=$fileArr['error'][$keyee];  
			}  
			return $uploads;  
		}

		if($_FILES['filesInput']){  
			$uploads = getDetails($_FILES['filesInput']);
			$fileUploader=new fileUploader($uploads);
		}
	} else {
		echo "<script>action='nothing'; top.ICEcoder.message('Sorry, cannot upload whilst in demo mode');</script>";
	}

	echo "<script>top.ICEcoder.hideFileMenu();top.document.getElementById('fileInput').value='';top.ICEcoder.showHide('hide',top.document.getElementById('loadingMask'));</script>";
}

// If we're due to rename a file/folder...
if ($_GET['action']=="rename") {
	if (!$demoMode && is_writable($docRoot.$iceRoot.str_replace("|","/",strClean($_GET['oldFileName'])))) {
		if(rename($docRoot.$iceRoot.str_replace("|","/",strClean($_GET['oldFileName'])),$docRoot.$fileLoc."/".$fileName)) {
			// Reload file manager
			echo '<script>top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'rename\',\''.$fileLoc.'\',\''.$fileName.'\',\'\',\''.str_replace($iceRoot,"",strClean($_GET['oldFileName'])).'\');';
			echo 'action="rename";</script>';
			$renamed=true;
		} else {
			$renamed=false;
		}
	} else {
		$renamed=false;
	}
	if (!$renamed) {
		echo "<script>action='nothing'; top.ICEcoder.message('Sorry, cannot rename\\n".strClean($_GET['oldFileName'])."\\n\\nMaybe public write permissions needed on this or parent folder?');</script>";
	}
	echo '<script>top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);</script>';
}

// If we're due to replace text in a file...
if ($_GET['action']=="replaceText") {
	if (!$demoMode && is_writable(str_replace("|","/",strClean($_GET['fileRef'])))) {
		$file = str_replace("|","/",strClean($_GET['fileRef']));
		$loadedFile = toUTF8noBOM(file_get_contents($file),true);
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
		$fullPath = str_replace($docRoot,"",$filesArray[$i]);
		$fullPath = str_replace($iceRoot,"",$fullPath);
		$fullPath = $docRoot.$iceRoot.$fullPath;
		if (!$demoMode && is_writable($fullPath)) {
			is_dir($fullPath)
				? rrmdir($fullPath)
				: unlink($fullPath);
			$fileName = basename($fullPath);
			$fileLoc = dirname(str_replace($docRoot,"",$fullPath));
			if ($fileLoc=="" || $fileLoc=="\\") {$fileLoc="/";};
			// Reload file manager
			echo '<script>top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'delete\',\''.$fileLoc.'\',\''.$fileName.'\');';
			echo 'action="delete";</script>';
		} else {
			echo "<script>top.ICEcoder.message('Sorry can\\'t delete\\n".str_replace($docRoot,"",$fullPath)."');</script>";
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
			$filemtime = $serverType=="Linux" ? filemtime($file) : "1000000";
			if (!(isset($_GET['fileMDT']))||$filemtime==$_GET['fileMDT']) {
				$fh = fopen($file, 'w') or die("Sorry, cannot save");
				// replace \r\n (Windows), \r (old Mac) and \n (Linux) line endings with whatever we chose to be lineEnding
				$contents = $_POST['contents'];
				$contents = str_replace("\r\n", $ICEcoder["lineEnding"], $contents);
				$contents = str_replace("\r", $ICEcoder["lineEnding"], $contents);
				$contents = str_replace("\n", $ICEcoder["lineEnding"], $contents);
				// Now write that content, close the file and clear the statcache
				fwrite($fh, $contents);
				fclose($fh);
				clearstatcache();
				$filemtime = $serverType=="Linux" ? filemtime($file) : "1000000";
				echo '<script>top.ICEcoder.openFileMDTs[top.ICEcoder.selectedTab-1]="'.$filemtime.'";</script>';
				// Reload file manager & rename tab if it was a new file
				if (isset($_POST['newFileName']) && $_POST['newFileName']!="") {
					echo '<script>top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'add\',\''.$fileLoc.'\',\''.$fileName.'\');';
					echo 'top.ICEcoder.renameTab(top.ICEcoder.selectedTab,\''.$fileLoc."/".$fileName.'\');</script>';
				}
				// Reload previewWindow window if not a Markdown file
				echo '<script>if (top.ICEcoder.previewWindow.location && top.ICEcoder.previewWindow.location.pathname.indexOf(".md")==-1) {top.ICEcoder.previewWindow.location.reload()};action="doneSave";</script>';
			} else {
				$loadedFile = toUTF8noBOM(file_get_contents($file),true);
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
					top.ICEcoder.openFileMDTs[top.ICEcoder.selectedTab-1] = "<?php echo $filemtime; ?>";
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
			<?php
			if (file_exists($file)) {
			?>
			} else {
				top.ICEcoder.loadingFile = true;
				// Reset the various states back to their initial setting
				selectedTab = top.ICEcoder.openFiles.length;	// The tab that's currently selected

				// Finally, store all data, show tabs etc
				top.ICEcoder.createNewTab();
				top.ICEcoder.cMInstances.push(top.ICEcoder.nextcMInstance);
				top.ICEcoder.setLayout();
				top.ICEcoder.content.contentWindow.createNewCMInstance(top.ICEcoder.nextcMInstance);

				// Set the value & innerHTML of the code textarea to that of our loaded file plus make it visible (it's hidden on ICEcoder's load)
				top.ICEcoder.switchMode();
				cM = top.ICEcoder.getcMInstance();
				cM.setValue(document.getElementById('loadedFile').value);
				top.document.getElementById('content').style.visibility='visible';
				top.ICEcoder.switchTab(top.ICEcoder.selectedTab);
				cM.focus();

				// Then clean it up, set the text cursor, update the display and get the character data
				top.ICEcoder.contentCleanUp();
				top.ICEcoder.content.contentWindow['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].removeLineClass(top.ICEcoder['cMActiveLine'+top.ICEcoder.selectedTab], "background");
				top.ICEcoder['cMActiveLine'+top.ICEcoder.selectedTab] = top.ICEcoder.content.contentWindow['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].addLineClass(0, "background", "cm-s-activeLine");
				top.ICEcoder.nextcMInstance++;
				top.ICEcoder.openFileMDTs.push('<?php echo $serverType=="Linux" ? filemtime($file) : "1000000"; ?>');
				top.ICEcoder.loadingFile = false;
			<?php
			;};
			?>
			}
		},4);
	}

	if (fileType=="image") {
		top.document.getElementById('blackMask').style.visibility = "visible";
		top.document.getElementById('mediaContainer').innerHTML = 
			"<canvas id=\"canvasPicker\" width=\"1\" height=\"1\" style=\"position: absolute; margin: 10px 0 0 10px; cursor: crosshair\" onmouseover=\"top.ICEcoder.overPopup=true\" onmouseout=\"top.ICEcoder.overPopup=false\"></canvas>" + 
			"<img src=\"<?php echo $fileLoc."/".$fileName;?>\" class=\"whiteGlow\" style=\"border: solid 10px #fff; max-width: 700px; max-height: 500px; background-color: #000; background-image: url('images/checkerboard.png')\" onLoad=\"reducedImgMsg = (this.naturalWidth > 700 || this.naturalHeight > 500) ? ', displayed at ' + this.width + ' x ' + this.height : ''; document.getElementById('imgInfo').innerHTML += ' (' + this.naturalWidth + ' x ' + this.naturalHeight + reducedImgMsg + ')'; top.ICEcoder.drawCanvasImage(this)\"><br>" +
			"<div class=\"whiteGlow\" style=\"display: inline-block; margin-top: -10px; border: solid 10px #fff; color: #000; background-color: #fff\" id=\"imgInfo\"  onmouseover=\"top.ICEcoder.overPopup=true\" onmouseout=\"top.ICEcoder.overPopup=false\">" + 
				"<b><?php echo $fileLoc."/".$fileName;?></b>" + 
			"</div><br>" + 
			"<input type=\"text\" id=\"hexMouseXY\" style=\"border: 1px solid #888; border-right: 0; width: 70px\" onmouseover=\"top.ICEcoder.overPopup=true\" onmouseout=\"top.ICEcoder.overPopup=false\"></input>" + 
			"<input type=\"text\" id=\"rgbMouseXY\" style=\"border: 1px solid #888; margin-right: 10px; width: 70px\" onmouseover=\"top.ICEcoder.overPopup=true\" onmouseout=\"top.ICEcoder.overPopup=false\"></input>" + 
			"<input type=\"text\" id=\"hex\" style=\"border: 1px solid #888; border-right: 0; width: 70px\" onmouseover=\"top.ICEcoder.overPopup=true\" onmouseout=\"top.ICEcoder.overPopup=false\"></input>" + 
			"<input type=\"text\" id=\"rgb\" style=\"border: 1px solid #888; width: 70px\" onmouseover=\"top.ICEcoder.overPopup=true\" onmouseout=\"top.ICEcoder.overPopup=false\"></input>";
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
		if (newFileName && newFileName.substr(0,1)!="/") {newFileName = "/" + newFileName}
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