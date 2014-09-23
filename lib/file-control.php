<?php
include("headers.php");
include("settings.php");
$t = $text['file-control'];
?>
<script>
<?php
// Get the save type if any
$saveType = isset($_GET['saveType']) ? strClean($_GET['saveType']) : "";

// Establish the filename/new filename
$file = str_replace("|","/",strClean(
	isset($_POST['newFileName']) && $_POST['newFileName']!=""
	? $_POST['newFileName']
	: $_GET['file']
	));

// Put the original $file var aside for use
$fileOrig = $file;

// Trim any +'s or spaces from the end of file
$file = rtrim(rtrim($file,'+'),' ');

// Also remove [NEW] from $file, we can consider $_GET['action'] or $fileOrig to pick that up
$file = rtrim($file,'[NEW]');

// Make each path in $file a full path (; seperated list)
$allFiles = explode(";",$file);
for ($i=0; $i<count($allFiles); $i++) {
	if (strpos($allFiles[$i],$docRoot)===false && $_GET['action']!="getRemoteFile") {
		$allFiles[$i]=str_replace("|","/",$docRoot.$iceRoot.$allFiles[$i]);
	}
};
$file = implode(";",$allFiles);

// Establish the $fileLoc and $fileName (used in single file cases, eg opening. Multiple file cases, eg deleting, is worked out in that loop)
$fileLoc = substr(str_replace($docRoot,"",$file),0,strrpos(str_replace($docRoot,"",$file),"/"));
$fileName = basename($file);

// Check through all files to make sure they're valid/safe
$allFiles = explode(";",$file);
for ($i=0; $i<count($allFiles); $i++) {

	// Uncomment to alert and console.log the action and file, useful for debugging
	// echo ";alert('".xssClean($_GET['action'],"html")." : ".$allFiles[$i]."');console.log('".xssClean($_GET['action'],"html")." : ".$allFiles[$i]."');";

	// Die if the file requested isn't something we expect
	if(
		// A local folder that isn't the doc root or starts with the doc root
		($_GET['action']!="getRemoteFile" &&
			rtrim($allFiles[$i],"/") !== rtrim($docRoot,"/") &&
			strpos(realpath(rtrim(dirname($allFiles[$i]),"/")),realpath(rtrim($docRoot,"/"))) !== 0
		) ||
		// Or a remote URL that doesn't start http
		($_GET['action']=="getRemoteFile" && strpos($allFiles[$i],"http") !== 0)
		) {
		die("alert('Sorry! - problem with file requested');</script>");
	};
}

// If we're due to open a file...
if ($_GET['action']=="load") {
	echo 'action="load";';

	if (file_exists($file)) {
		$finfo = "text";
		// Determine what to do based on mime type
		if (function_exists('finfo_open')) {
			$finfoMIME = finfo_open(FILEINFO_MIME);
			$finfo = finfo_file($finfoMIME, $file);
			finfo_close($finfoMIME);
		} else {
			$fileExt = explode(" ",pathinfo($file, PATHINFO_EXTENSION));
			$fileExt = $fileExt[0];
			if (array_search($fileExt,array("gif","jpg","jpeg","png"))!==false) {$finfo = "image";};
			if (array_search($fileExt,array("doc","docx","ppt","rtf","pdf","zip","tar","gz","swf","asx","asf","midi","mp3","wav","aiff","mov","qt","wmv","mp4","odt","odg","odp"))!==false) {$finfo = "other";};
		}
		if (strpos($finfo,"text")===0 || strpos($finfo,"empty")!==false) {
			echo 'fileType="text";';
			echo 'top.ICEcoder.shortURL = top.ICEcoder.thisFileFolderLink = "'.$fileLoc."/".$fileName.'";';
			$loadedFile = toUTF8noBOM(file_get_contents($file,false,$context),true);
			echo '</script><textarea name="loadedFile" id="loadedFile">'.str_replace("</textarea>","<ICEcoder:/:textarea>",str_replace("&","&amp;",$loadedFile)).'</textarea><script>';
		} else if (strpos($finfo,"image")===0) {
			echo 'fileType="image";fileName=\''.$fileLoc."/".$fileName.'\';';
		} else {
			echo 'fileType="other";window.open(\'http://'.$_SERVER['SERVER_NAME'].$fileLoc."/".$fileName.'\');';
		};
	} else {
		echo 'fileType="nothing"; top.ICEcoder.message(\''.$t['Sorry'].', '.$fileLoc."/".$fileName.' '.$t['does not seem...'].'\');';
	}

};

// Get the contents of a remote URL
if ($_GET['action']=="getRemoteFile") {
	if ($remoteFile = toUTF8noBOM(file_get_contents($file,false,$context),true)) {
		// replace \r\n (Windows), \r (old Mac) and \n (Linux) line endings with whatever we chose to be lineEnding
		$remoteFile = str_replace("\r\n", $ICEcoder["lineEnding"], $remoteFile);
		$remoteFile = str_replace("\r", $ICEcoder["lineEnding"], $remoteFile);
		$remoteFile = str_replace("\n", $ICEcoder["lineEnding"], $remoteFile);
		echo 'top.ICEcoder.newTab();';
		echo '</script><textarea name="remoteFile" id="remoteFile">'.str_replace("</textarea>","<ICEcoder:/:textarea>",str_replace("&","&amp;",$remoteFile)).'</textarea><script>';
		echo 'top.ICEcoder.getcMInstance().setValue(document.getElementById("remoteFile").value);action="getRemoteFile";';
	} else {
		echo 'action="nothing"; top.ICEcoder.message(\''.$t['Sorry, could not...'].' '.$file.'\');';
	}
	echo 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
}

// If we're due to add a new folder...
if ($_GET['action']=="newFolder") {
	if (!$demoMode && is_writable($docRoot.$fileLoc)) {
		mkdir($file, 0705);
		// Reload file manager
		echo 'top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'add\',\''.$fileLoc.'\',\''.$fileName.'\',false,false,false,\'folder\');action="newFolder";';
	} else {
		echo "action='nothing'; top.ICEcoder.message('".$t['Sorry, cannot create...']."\\n".$fileLoc."');";
	}
	echo 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
}

// If we're due to paste a new file...
if ($_GET['action']=="paste") {
	$source = $file;
	$dest = str_replace("//","/",$docRoot.$iceRoot.strClean(str_replace("|","/",$_GET['location']))."/".basename($source));
	if (!$demoMode && is_writable(dirname($dest))) {
		if (is_dir($source)) {
			$fileOrFolder = "folder";
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
			$fileOrFolder = "file";
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
		echo 'top.ICEcoder.updateFileManagerList(\'add\',\''.strClean(str_replace("|","/",$_GET['location'])).'\',\''.basename($dest).'\',false,false,false,\''.$fileOrFolder.'\');action="pasteFile";';
	} else {
		echo "action='nothing'; top.ICEcoder.message('".$t['Sorry, cannot copy']." \\n".str_replace($docRoot,"",$source)."\\n ".$t['into']." \\n".str_replace($docRoot,"",$dest)."');";
	}
	echo 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
}

// If we're due to upload files...
if ($_GET['action']=="upload") {
	if (!$demoMode) {
		class fileUploader {  
			public function __construct($uploads) {
				global $docRoot,$iceRoot;
				$uploadDir=$docRoot.$iceRoot.str_replace("..","",str_replace("|","/",strClean($_POST['folder'])."/"));
				foreach($uploads as $current) {  
					$this->uploadFile=$uploadDir.$current->name;
					$fileName = $current->name;
					if ($this->upload($current,$this->uploadFile)) {
						echo 'action="upload"; top.ICEcoder.updateFileManagerList(\'add\',top.ICEcoder.selectedFiles[top.ICEcoder.selectedFiles.length-1].replace(/\|/g,\'/\'),\''.str_replace("'","\'",$fileName).'\',false,false,true,\'file\'); top.ICEcoder.serverMessage("'.$t['Uploaded file(s) OK'].'");setTimeout(function(){top.ICEcoder.serverMessage();},2000);';
					} else {
						echo "action='nothing'; top.ICEcoder.message('".$t['Sorry, cannot upload']." \\n".$fileName."\\n ".$t['into']." \\n'+top.ICEcoder.selectedFiles[top.ICEcoder.selectedFiles.length-1].replace(/\|/g,'/'));";
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
		echo "action='nothing'; top.ICEcoder.message('".$t['Sorry, cannot upload...']."');";
	}

	echo "top.ICEcoder.hideFileMenu();top.document.getElementById('fileInput').value='';top.ICEcoder.showHide('hide',top.document.getElementById('loadingMask'));";
}

// If we're due to rename a file/folder...
if ($_GET['action']=="rename") {
	$renamed=false;
	if (!$demoMode && is_writable($docRoot.$iceRoot.str_replace("|","/",strClean($_GET['oldFileName'])))) {
		if(rename($docRoot.$iceRoot.str_replace("|","/",strClean($_GET['oldFileName'])),$docRoot.$fileLoc."/".$fileName)) {
			// Reload file manager
			echo 'top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'rename\',\''.$fileLoc.'\',\''.$fileName.'\',\'\',\''.str_replace($iceRoot,"",strClean($_GET['oldFileName'])).'\');';
			echo 'action="rename";';
			$renamed=true;
		}
	}
	if (!$renamed) {
		echo "action='nothing'; top.ICEcoder.message('".$t['Sorry, cannot rename']."\\n".strClean($_GET['oldFileName'])."\\n\\n".$t['Maybe public write...']."');";
	}
	echo 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
}

// If we're due to move a file/folder...
if ($_GET['action']=="move") {
	$moved=false;
	$srcDir = $docRoot.$iceRoot.str_replace("|","/",strClean($_GET['oldFileName']));
	$tgtDir = $docRoot.$fileLoc."/".$fileName;
	if ($srcDir != $tgtDir && $fileLoc != "") {
		if (!$demoMode && is_writable($srcDir)) {
			if(rename($srcDir,$tgtDir)) {
				// Reload file manager
				$fileOrFolder = is_dir($docRoot.$fileLoc."/".$fileName) ? "folder" : "file";
				echo 'top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'move\',\''.$fileLoc.'\',\''.$fileName.'\',\'\',\''.str_replace($iceRoot,"",strClean($_GET['oldFileName'])).'\',false,\''.$fileOrFolder.'\');';
				echo 'action="move";';
				$moved=true;
			}
		}
		if (!$moved) {
			echo "action='nothing'; top.ICEcoder.message('".$t['Sorry, cannot move']."\\n".strClean($_GET['oldFileName'])."\\n\\n".$t['Maybe public write...']."');";
		}
	} else {
		echo "action='nothing';";
	}
	echo 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
}

// If we're due to replace text in a file...
if ($_GET['action']=="replaceText") {
	if (!$demoMode && is_writable(str_replace("|","/",strClean($_GET['fileRef'])))) {
		$file = str_replace("|","/",strClean($_GET['fileRef']));
		$loadedFile = toUTF8noBOM(file_get_contents($file,false,$context),true);
		$newContent = str_replace(strClean($_GET['find']),strClean($_GET['replace']),$loadedFile);
		$fh = fopen($file, 'w') or die($t['Sorry, cannot save']);
		fwrite($fh, $newContent);
		fclose($fh);
		echo 'action="replaceText";';
	} else {
		echo "action='nothing'; top.ICEcoder.message('".$t['Sorry, cannot replace...']."\\n".strClean($_GET['fileRef'])."');";
	}
	echo 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
}

// If we're due to change permissions on a file/folder...
if ($_GET['action']=="perms") {
	if (!$demoMode && is_writable($file)) {
		chmod($file,octdec(numClean($_GET['perms'])));
		// Reload file manager
		echo 'top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'chmod\',\''.$fileLoc.'\',\''.$fileName.'\',\''.numClean($_GET['perms']).'\');';
		echo 'action="perms";';
	} else {
		echo "action='nothing'; top.ICEcoder.message('".$t['Sorry, cannot change...']." \\n".strClean($file)."');";
	}
	echo 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
}

// If we're due to delete a file...
if ($_GET['action']=="delete") {
	$filesArray = explode(";",$file); // May contain more than one file here
	for ($i=0;$i<count($filesArray);$i++) {
		$fullPath = str_replace($docRoot,"",$filesArray[$i]);
		$fullPath = str_replace($iceRoot,"",$fullPath);
		$fullPath = $docRoot.$iceRoot.$fullPath;

		if (rtrim($fullPath,"/") == rtrim($docRoot,"/")) {
			echo "top.ICEcoder.message('".$t['Sorry, cannot delete...']."');";
		} else if (!$demoMode && is_writable($fullPath)) {
			is_dir($fullPath)
				? rrmdir($fullPath)
				: unlink($fullPath);
			$fileName = basename($fullPath);
			$fileLoc = dirname(str_replace($docRoot,"",$fullPath));
			if ($fileLoc=="" || $fileLoc=="\\") {$fileLoc="/";};
			// Reload file manager
			echo 'top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'delete\',\''.$fileLoc.'\',\''.$fileName.'\');';
			echo 'action="delete";';
		} else {
			echo "top.ICEcoder.message('".$t['Sorry, cannot delete']."\\n".str_replace($docRoot,"",$fullPath)."');";
		}
		echo 'action="nothing";';
	}
	echo 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
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
	echo 'action="save";';
	// on the form posting via a reload, save the file
	if (isset($_POST['contents'])) {
		if (!$demoMode && ((file_exists($file) && is_writable($file)) || isset($_POST['newFileName']) && $_POST['newFileName']!="")) {
			$filemtime = $serverType=="Linux" ? filemtime($file) : "1000000";
			if (!(isset($_GET['fileMDT']))||$filemtime==$_GET['fileMDT']) {
				$fh = fopen($file, 'w') or die($t['Sorry, cannot save']);
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
				echo 'top.ICEcoder.openFileMDTs[top.ICEcoder.selectedTab-1]="'.$filemtime.'";';
				// Reload file manager, rename tab & remove old file highlighting if it was a new file
				if (isset($_POST['newFileName']) && $_POST['newFileName']!="") {
					echo 'top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'add\',\''.$fileLoc.'\',\''.$fileName.'\',false,false,false,\'file\');';
					echo 'top.ICEcoder.renameTab(top.ICEcoder.selectedTab,\''.$fileLoc."/".$fileName.'\');';
					if (!strpos($_GET['file'],"[NEW]")) {
						// We're saving as a new file, so unhighlight the old name in the file manager if visible
						echo "fileLink = top.ICEcoder.filesFrame.contentWindow.document.getElementById('".str_replace("/","|",$fileLoc)."|".basename($_GET['file'])."');";
						echo "if (fileLink) {fileLink.style.backgroundColor = top.ICEcoder.tabBGnormal; fileLink.style.color = top.ICEcoder.tabFGnormalFile};";
					}
				}
				// Reload previewWindow window if not a Markdown file
				echo 'if (top.ICEcoder.previewWindow.location && top.ICEcoder.previewWindow.location.pathname.indexOf(".md")==-1) {
					top.ICEcoder.previewWindow.location.reload();
					// Do the pesticide plugin if it exists
					try {top.ICEcoder.doPesticide();} catch(err) {};
				};';
				echo 'top.ICEcoder.setPreviousFiles();setTimeout(function(){top.ICEcoder.indicateChanges()},4);action="doneSave";';
				// Run our custom processes
				include_once("../processes/on-file-save.php");
			} else {
				$loadedFile = toUTF8noBOM(file_get_contents($file,false,$context),true);
				echo '</script><textarea name="loadedFile" id="loadedFile">'.str_replace("</textarea>","<ICEcoder:/:textarea>",htmlentities($loadedFile)).'</textarea>';
				echo '<textarea name="userVersionFile" id="userVersionFile"></textarea><script>';
				?>
				var refreshFile = top.ICEcoder.ask('<?php echo $t['Sorry, this file...']."\n".$file."\n\n".$t['Reload this file...'];?>');
				if (refreshFile) {
					var cM = top.ICEcoder.getcMInstance();
					var thisTab = top.ICEcoder.selectedTab;
					document.getElementById('userVersionFile').value = cM.getValue();
					// Revert back to original
					cM.setValue(document.getElementById('loadedFile').value);
					top.ICEcoder.savedPoints[thisTab-1] = cM.changeGeneration();
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
				<?php
			}
        	} else {
			echo "action='nothing';top.ICEcoder.message('".$t['Sorry, cannot save']."\\n".$file."');";
		}
	echo 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
	}
};
?>
if (action=="load") {
	if (fileType=="text") {
		setTimeout(function() {
			if (!top.ICEcoder.content.contentWindow.createNewCMInstance) {
				console.log('<?php echo $t['There was a...']; ?>');
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
				top.ICEcoder.savedPoints[top.ICEcoder.selectedTab-1] = cM.changeGeneration();
				top.document.getElementById('content').style.visibility='visible';
				top.ICEcoder.switchTab(top.ICEcoder.selectedTab);
				top.ICEcoder.focus();

				// Then clean it up, set the text cursor, update the display and get the character data
				top.ICEcoder.contentCleanUp();
				top.ICEcoder.content.contentWindow['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].removeLineClass(top.ICEcoder['cMActiveLine'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]], "background");
				top.ICEcoder['cMActiveLine'+top.ICEcoder.selectedTab] = top.ICEcoder.content.contentWindow['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].addLineClass(0, "background", "cm-s-activeLine");
				top.ICEcoder.nextcMInstance++;
				top.ICEcoder.openFileMDTs.push('<?php echo $serverType=="Linux" ? filemtime($file) : "1000000"; ?>');
				for (var i=0; i<cM.lineCount(); i++) {
					top.ICEcoder.content.contentWindow.CodeMirror.doFold(cM.getLine(i).indexOf("{")>-1?"brace":"xml",null,"+","-",true)(cM, i);
				}
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
			"<img src=\"<?php echo $fileLoc."/".$fileName;?>\" class=\"whiteGlow\" style=\"border: solid 10px #fff; max-width: 700px; max-height: 500px; background-color: #000; background-image: url('images/checkerboard.png')\" onLoad=\"reducedImgMsg = (this.naturalWidth > 700 || this.naturalHeight > 500) ? ', <?php echo $t['displayed at']; ?> ' + this.width + ' x ' + this.height : ''; document.getElementById('imgInfo').innerHTML += ' (' + this.naturalWidth + ' x ' + this.naturalHeight + reducedImgMsg + ')'; top.ICEcoder.drawCanvasImage(this)\"><br>" +
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
	<input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
</form>

<script>
if (action=="save") {
	<?php
	if (strpos($fileOrig,"[NEW]")>0||$saveType=="saveAs") {
	?>
		fileLoc = '<?php echo $fileLoc;?>';
		newFileName = top.ICEcoder.getInput('<?php echo $t['Enter filename to...']; ?> '+(fileLoc!='' ? fileLoc : '/'),'');
		if (newFileName) {
			if (newFileName.substr(0,1)!="/") {newFileName = "/" + newFileName}
			newFileName = fileLoc + newFileName;
			if (top.document.getElementById('filesFrame').contentWindow.document.getElementById(newFileName.replace(/\//g,"|"))) {
				overwriteOK = top.ICEcoder.ask('<?php echo $t['That file exists...']; ?>');
			}
		}
		document.saveFile.newFileName.value = '<?php echo $docRoot; ?>' + newFileName;
	<?php ;};?>
	if ("undefined" == typeof newFileName || (newFileName && "undefined" == typeof overwriteOK) || ("undefined" != typeof overwriteOK && overwriteOK)) {
		top.ICEcoder.serverMessage('<b><?php echo $t['Saving']; ?></b><br>'+ <?php echo strpos($fileOrig,"[NEW]")>0 ? "newFileName" : "'$file'"; ?>);
		document.saveFile.contents.value = top.document.getElementById('saveTemp1').value;
		document.saveFile.submit();
	} else {
		top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);
		action=="nothing";
	}
}

if (action=="doneSave") {
	cM = top.ICEcoder.getcMInstance();
	top.ICEcoder.savedPoints[top.ICEcoder.selectedTab-1] = cM.changeGeneration();
	top.ICEcoder.redoTabHighlight(top.ICEcoder.selectedTab);
}

// Finally, switch mode in case we have saved, renamed file etc
top.ICEcoder.switchMode();
</script>