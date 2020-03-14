<?php
include("headers.php");
include("settings.php");
include("ftp-control.php");
$t = $text['file-control'];
?>
<script>
<?php
// Establish the filename/new filename
$file = str_replace("|","/",
	isset($_POST['newFileName']) && $_POST['newFileName']!=""
	    ? $_POST['newFileName']
	    : $_REQUEST['file']
);

// Establish the actual name as we may have HTML entities in filename
$file = html_entity_decode($file);

// Put the original $file var aside for use
$fileOrig = $file;

// Trim any +'s or spaces from the end of file
$file = rtrim(rtrim($file,'+'),' ');

// Also remove [NEW] from $file, we can consider $_GET['action'] or $fileOrig to pick that up
$file = preg_replace('/\[NEW\]$/', '', $file);

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
		($_GET['action']!="getRemoteFile" && !isset($ftpSite) &&
			rtrim($allFiles[$i],"/") !== rtrim($docRoot,"/") &&
			strpos(realpath(rtrim(dirname($allFiles[$i]),"/")),realpath(rtrim($docRoot,"/"))) !== 0
		) ||
		// Or a remote URL that doesn't start http
		($_GET['action']=="getRemoteFile" && strpos($allFiles[$i],"http") !== 0)
		) {
		die("parent.parent.ICEcoder.message('Sorry! - problem with file requested');</script>");
	};
}

// If we're due to open a file...
if ($_GET['action']=="load") {
	echo 'action="load";';
	$lineNumber = max(isset($_REQUEST['lineNumber'])?intval($_REQUEST['lineNumber']):1, 1);
	// Check this file isn't on the banned list at all
	$canOpen = true;
	for ($i=0;$i<count($_SESSION['bannedFiles']);$i++) {
		if(str_replace("*","",$_SESSION['bannedFiles'][$i]) != "" && strpos($file,str_replace("*","",$_SESSION['bannedFiles'][$i]))!==false) {$canOpen = false;}
	}

	if (!$canOpen) {
		echo 'fileType="nothing"; parent.parent.ICEcoder.message(\''.$t['Sorry, could not...'].' '.$fileLoc."/".$fileName.'\');';
	} elseif (isset($ftpSite) || file_exists($file)) {
		$finfo = "text";
		// Determine what to do based on mime type
		if (!isset($ftpSite) && function_exists('finfo_open')) {
			$finfoMIME = finfo_open(FILEINFO_MIME);
			$finfo = finfo_file($finfoMIME, $file);
			finfo_close($finfoMIME);
		} else {
			$fileExt = explode(" ",pathinfo($file, PATHINFO_EXTENSION));
			$fileExt = $fileExt[0];
			if (array_search($fileExt,array("gif","jpg","jpeg","png"))!==false) {$finfo = "image";};
			if (array_search($fileExt,array("doc","docx","ppt","rtf","pdf","zip","tar","gz","swf","asx","asf","midi","mp3","wav","aiff","mov","qt","wmv","mp4","odt","odg","odp"))!==false) {$finfo = "other";};
		}
		if (strpos($finfo,"text")===0 || strpos($finfo, "application/xml")===0 || strpos($finfo,"empty")!==false) {
			echo 'fileType="text";';
			echo 'parent.parent.ICEcoder.shortURL = parent.parent.ICEcoder.thisFileFolderLink = "'.$fileLoc."/".$fileName.'";';

		// Get file over FTP?
		if (isset($ftpSite)) {
			ftpStart();
			// Show user warning if no good connection
			if (!$ftpConn || !$ftpLogin) {
				die('parent.parent.ICEcoder.message("Sorry, no FTP connection to '.$ftpHost.' for user '.$ftpUser.'");parent.parent.ICEcoder.serverMessage();parent.parent.ICEcoder.serverQueue("del",0);</script>');
				exit;
			}
			// Get our file contents and close the FTP connection
			$loadedFile = toUTF8noBOM(ftpGetContents($ftpConn, $ftpRoot.$fileLoc."/".$fileName, $ftpMode),false);
			ftpEnd();
		// Get local file
		} else {
			$loadedFile = toUTF8noBOM(getData($file),true);
		}
			$encoding=ini_get("default_charset");
			if($encoding=="") {
				$encoding="UTF-8";
			}
			// Get content and set HTML entities on it according to encoding
			$loadedFile = htmlentities($loadedFile,ENT_COMPAT,$encoding);
			// Remove \r chars and replace \n with carriage return HTML entity char
			$loadedFile = preg_replace('/\\r/','',$loadedFile);
			$loadedFile = preg_replace('/\\n/','&#13;',$loadedFile);
			echo '</script><textarea name="loadedFile" id="loadedFile">'.$loadedFile.'</textarea><script>';
			// Run our custom processes
			include_once("../processes/on-file-load.php");
		} else if (strpos($finfo,"image")===0) {
			echo 'fileType="image";fileName=\''.$fileLoc."/".$fileName.'\';';
		} else {
			echo 'fileType="other";window.open(\'http://'.$_SERVER['SERVER_NAME'].$fileLoc."/".$fileName.'\');';
		};
	} else {
		echo 'fileType="nothing"; parent.parent.ICEcoder.message(\''.$t['Sorry'].', '.$fileLoc."/".$fileName.' '.$t['does not seem...'].'\');';
	}

};


?>
if (action=="load") {
	if (fileType=="text") {
		setTimeout(function() {
			if (!parent.parent.ICEcoder.content.contentWindow.createNewCMInstance) {
				console.log('<?php echo $t['There was a...']; ?>');
				window.location.reload(true);
			<?php
			if (isset($ftpSite) || file_exists($file)) {
			?>
			} else {
				parent.parent.ICEcoder.loadingFile = true;
				// Reset the various states back to their initial setting
				selectedTab = parent.parent.ICEcoder.openFiles.length;	// The tab that's currently selected

				// Finally, store all data, show tabs etc
				parent.parent.ICEcoder.createNewTab();
				parent.parent.ICEcoder.cMInstances.push(parent.parent.ICEcoder.nextcMInstance);
				parent.parent.ICEcoder.setLayout();
				parent.parent.ICEcoder.content.contentWindow.createNewCMInstance(parent.parent.ICEcoder.nextcMInstance);

				// Set the value & innerHTML of the code textarea to that of our loaded file plus make it visible (it's hidden on ICEcoder's load)
				parent.parent.ICEcoder.switchMode();
				cM = parent.parent.ICEcoder.getcMInstance();
				cM.setValue(document.getElementById('loadedFile').value);
				parent.parent.ICEcoder.savedPoints[parent.parent.ICEcoder.selectedTab-1] = cM.changeGeneration();
				parent.parent.ICEcoder.savedContents[parent.parent.ICEcoder.selectedTab-1] = cM.getValue();
                parent.parent.document.getElementById('content').style.visibility='visible';
				parent.parent.ICEcoder.switchTab(parent.parent.ICEcoder.selectedTab,'noFocus');
				setTimeout(function(){parent.parent.ICEcoder.filesFrame.contentWindow.focus();},0);

				// Then clean it up, set the text cursor, update the display and get the character data
				parent.parent.ICEcoder.contentCleanUp();
				parent.parent.ICEcoder.content.contentWindow['cM'+parent.parent.ICEcoder.cMInstances[parent.parent.ICEcoder.selectedTab-1]].removeLineClass(parent.parent.ICEcoder['cMActiveLinecM'+parent.parent.ICEcoder.cMInstances[parent.parent.ICEcoder.selectedTab-1]], "background");
                parent.parent.ICEcoder['cMActiveLinecM'+parent.parent.ICEcoder.selectedTab] = parent.parent.ICEcoder.content.contentWindow['cM'+parent.parent.ICEcoder.cMInstances[parent.parent.ICEcoder.selectedTab-1]].addLineClass(0, "background", "cm-s-activeLine");
				parent.parent.ICEcoder.nextcMInstance++;
				parent.parent.ICEcoder.openFileMDTs.push('<?php echo $serverType=="Linux" ? filemtime($file) : "1000000"; ?>');
				parent.parent.ICEcoder.openFileVersions.push(<?php
					$fileCountInfo = getVersionsCount($fileLoc,$fileName);
					echo $fileCountInfo['count'];?>);
				parent.parent.ICEcoder.updateVersionsDisplay();

				parent.parent.ICEcoder.goToLine(<?php echo $lineNumber; ?>);
				parent.parent.ICEcoder.loadingFile = false;
			<?php
			;};
			?>
			}
		},4);
	}

	if (fileType=="image") {
        parent.parent.document.getElementById('blackMask').style.visibility = "visible";
        parent.parent.document.getElementById('mediaContainer').innerHTML =
			"<canvas id=\"canvasPicker\" width=\"1\" height=\"1\" style=\"position: absolute; margin: 10px 0 0 10px; cursor: crosshair\" onmouseover=\"parent.parent.ICEcoder.overPopup=true\" onmouseout=\"parent.parent.ICEcoder.overPopup=false\"></canvas>" +
			"<img src=\"<?php echo (isset($ftpSite) ? $ftpSite : "").$fileLoc."/".$fileName."?unique=".microtime(true);?>\" class=\"whiteGlow\" style=\"border: solid 10px #fff; max-width: 700px; max-height: 500px; background-color: #000; background-image: url('"+parent.parent.iceLoc+"/images/checkerboard.png')\" onLoad=\"reducedImgMsg = (this.naturalWidth > 700 || this.naturalHeight > 500) ? ', <?php echo $t['displayed at']; ?> ' + this.width + ' x ' + this.height : ''; document.getElementById('imgInfo').innerHTML += ' (' + this.naturalWidth + ' x ' + this.naturalHeight + reducedImgMsg + ')'; parent.parent.ICEcoder.initCanvasImage(this); parent.parent.ICEcoder.interactCanvasImage(this)\"><br>" +
			"<div class=\"whiteGlow\" style=\"display: inline-block; margin-top: -10px; border: solid 10px #fff; color: #000; background-color: #fff\" id=\"imgInfo\"  onmouseover=\"parent.parent.ICEcoder.overPopup=true\" onmouseout=\"parent.parent.ICEcoder.overPopup=false\">" +
				"<b><?php echo $fileLoc."/".$fileName;?></b>" +
			"</div><br>" +
			"<div id=\"canvasPickerColorInfo\">"+
			"<input type=\"text\" id=\"hexMouseXY\" style=\"border: 1px solid #888; border-right: 0; width: 70px\" onmouseover=\"parent.parent.ICEcoder.overPopup=true\" onmouseout=\"parent.parent.ICEcoder.overPopup=false\"></input>" +
			"<input type=\"text\" id=\"rgbMouseXY\" style=\"border: 1px solid #888; margin-right: 10px; width: 70px\" onmouseover=\"parent.parent.ICEcoder.overPopup=true\" onmouseout=\"parent.parent.ICEcoder.overPopup=false\"></input>" +
			"<input type=\"text\" id=\"hex\" style=\"border: 1px solid #888; border-right: 0; width: 70px\" onmouseover=\"parent.parent.ICEcoder.overPopup=true\" onmouseout=\"parent.parent.ICEcoder.overPopup=false\"></input>" +
			"<input type=\"text\" id=\"rgb\" style=\"border: 1px solid #888; width: 70px\" onmouseover=\"parent.parent.ICEcoder.overPopup=true\" onmouseout=\"parent.parent.ICEcoder.overPopup=false\"></input>"+
			"</div>"+
			"<div id=\"canvasPickerCORSInfo\" style=\"display: none; padding-top: 4px\">CORS not enabled on resource site</div>";
        parent.parent.document.getElementById('floatingContainer').style.background = "#fff url('<?php echo $fileLoc."/".$fileName."?unique=".microtime(true);?>') no-repeat 0 0";
	}

	parent.parent.ICEcoder.serverMessage();parent.parent.ICEcoder.serverQueue("del",0);
}

// Finally, switch mode in case we have saved, renamed file etc
parent.parent.ICEcoder.switchMode();
</script>
