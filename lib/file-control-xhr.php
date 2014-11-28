<?php
include("headers.php");
include("settings.php");
$t = $text['file-control'];

// ===============================
// SET OUR ERROR INFO TO A DEFAULT
// ===============================

$error = false;
$errorStr = "false";
$errorMsg = "None";

// ==============================
// GET CLEANED FILENAMES OR ERROR
// ==============================

// Get the save type if any
$saveType = isset($_GET['saveType']) ? strClean($_GET['saveType']) : "";

// Establish the filename/new filename
$file = str_replace("|","/",strClean(
	isset($_POST['newFileName']) && $_POST['newFileName']!=""
	? $_POST['newFileName']
	: $_REQUEST['file']
	));

// Establish the actual name as we may have HTML entities in filename
$file = html_entity_decode($file);

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
		$error = true;
		$errorStr = "true";
		$errorMsg = "Sorry! - problem with file requested";
	};
}

// ============
// SAVING FILES
// ============

if (!$error && $_GET['action']=="save") {

	// ====================================
	// NEW FILES AND SAVE AS XHR LOOPAROUND
	// ====================================

	if (strpos($fileOrig,"[NEW]")>0||$saveType=="saveAs") {
		$finalAction = strpos($fileOrig,"[NEW]")>0 ? "save as" : "save";
		$fileURL = isset($file) ? $file : "";
		$fileMDTURLPart = isset($_GET["fileMDT"]) && $_GET["fileMDT"]!="undefined" ? "&fileMDT=".numClean($_GET['fileMDT']) : "";
		$doNext = '
			top.ICEcoder.serverMessage();
			fileLoc = "'.$fileLoc.'";
			newFileName = top.ICEcoder.getInput("'.$t['Enter filename to...'].' "+(fileLoc!="" ? fileLoc : "/"),"");
			if (newFileName) {
				if (newFileName.substr(0,1)!="/") {newFileName = "/" + newFileName};
				newFileName = fileLoc + newFileName;
				if (top.document.getElementById("filesFrame").contentWindow.document.getElementById(newFileName.replace(/\\\//g,"|"))) {
					overwriteOK = top.ICEcoder.ask("'.$t['That file exists...'].'");
				}
			};

			if ("undefined" == typeof newFileName || (newFileName && "undefined" == typeof overwriteOK) || ("undefined" != typeof overwriteOK && overwriteOK)) {
				newFileName = "'.$docRoot.'" + newFileName;
				saveURL = "lib/file-control-xhr.php?action=save'.$fileMDTURLPart.'&csrf='.$_GET["csrf"].'";

				var xhr = top.ICEcoder.xhrObj();

				xhr.onreadystatechange=function() {
					if (xhr.readyState==4 && xhr.status==200) {
						/* console.log(xhr.responseText); */
						var statusObj = JSON.parse(xhr.responseText);
						/* Set the actions end time and time taken in JSON object */
						statusObj.action.timeEnd = new Date().getTime();
						statusObj.action.timeTaken = statusObj.action.timeEnd - statusObj.action.timeStart;
						/* console.log(statusObj); */

						if (statusObj.status.error) {
							top.ICEcoder.message(statusObj.status.errorMsg);
						} else {
							eval(statusObj.action.doNext);
						}
						

					}
				};
				/* console.log(\'Calling \'+saveURL+\' via XHR\'); */
				xhr.open("POST",saveURL,true);
				xhr.setRequestHeader(\'Content-type\', \'application/x-www-form-urlencoded\');
				xhr.send(\'timeStart='.$_POST["timeStart"].'&file='.$fileURL.'&newFileName=\'+newFileName+\'&contents=\'+top.document.getElementById(\'saveTemp1\').value);
				top.ICEcoder.serverMessage("<b>'.$t['Saving'].'</b><br>" + "'.($finalAction == "Save" ? "newFileName" : "'".$fileName."'").'");
			} else {
				top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);
			}';

	// ===================
	// FILE CONTENT SAVING
	// ===================

	} elseif (isset($_POST['contents'])) {
		$finalAction = isset($_POST["newFileName"]) ? "save as" : "save";

		// =================
		// FILE IS WRITEABLE
		// =================

		if (!$demoMode && ((file_exists($file) && is_writable($file)) || isset($_POST['newFileName']) && $_POST['newFileName']!="")) {
			$filemtime = $serverType=="Linux" ? filemtime($file) : "1000000";

			// =======================
			// MDT'S MATCH, WRITE FILE
			// =======================

			if (!(isset($_GET['fileMDT']))||$filemtime==$_GET['fileMDT']) {
				// Newly created files have the perms set too
				$setPerms = (!file_exists($file)) ? true : false;
				$fh = fopen($file, 'w') or die($t['Sorry, cannot save']);
				// replace \r\n (Windows), \r (old Mac) and \n (Linux) line endings with whatever we chose to be lineEnding
				$contents = $_POST['contents'];
				$contents = str_replace("\r\n", $ICEcoder["lineEnding"], $contents);
				$contents = str_replace("\r", $ICEcoder["lineEnding"], $contents);
				$contents = str_replace("\n", $ICEcoder["lineEnding"], $contents);
				// Now write that content, close the file and clear the statcache
				fwrite($fh, $contents);
				fclose($fh);
				if ($setPerms) {
					chmod($file,octdec($ICEcoder['newFilePerms']));
				}
				clearstatcache();
				$filemtime = $serverType=="Linux" ? filemtime($file) : "1000000";
				$doNext = 'top.ICEcoder.openFileMDTs[top.ICEcoder.selectedTab-1]="'.$filemtime.'";';
				// Reload file manager, rename tab & remove old file highlighting if it was a new file
				if (isset($_POST['newFileName']) && $_POST['newFileName']!="") {
					$doNext .= 'top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'add\',\''.$fileLoc.'\',\''.$fileName.'\',false,false,false,\'file\');';
					$doNext .= 'top.ICEcoder.renameTab(top.ICEcoder.selectedTab,\''.$fileLoc."/".$fileName.'\');';
					if (!strpos($_REQUEST['file'],"[NEW]")) {
						// We're saving as a new file, so unhighlight the old name in the file manager if visible
						$doNext .= "fileLink = top.ICEcoder.filesFrame.contentWindow.document.getElementById('".str_replace("/","|",$fileLoc)."|".basename($_REQUEST['file'])."');";
						$doNext .= "if (fileLink) {fileLink.style.backgroundColor = top.ICEcoder.tabBGnormal; fileLink.style.color = top.ICEcoder.tabFGnormalFile};";
					}
				}
				// Reload previewWindow window if not a Markdown file
				// In doing this, we check on an interval for the page to be complete and if we last saw it loading
				// When we are done loading, so set the loading status to false and load plugins ontop...		
				$doNext .= 'if (top.ICEcoder.previewWindow.location && top.ICEcoder.previewWindow.location.pathname.indexOf(".md")==-1) {
					top.ICEcoder.previewWindowLoading = false;
					top.ICEcoder.previewWindow.location.reload(true);
					
					top.ICEcoder.checkPreviewWindowLoadingInt = setInterval(function() {
						if (top.ICEcoder.previewWindow.document.readyState != "loading" && top.ICEcoder.previewWindowLoading) {
							top.ICEcoder.previewWindowLoading = false;
							try {top.ICEcoder.doPesticide();} catch(err) {};
							try {top.ICEcoder.doStatsJS(\'save\');} catch(err) {};
							clearInterval(top.ICEcoder.checkPreviewWindowLoadingInt);
						} else {
							top.ICEcoder.previewWindowLoading = top.ICEcoder.previewWindow.document.readyState == "loading" ? true : false;
						}
					},4);
					
				};';

				// Copy over content to diff pane if we have that setting on
				$doNext .= '
					cM = top.ICEcoder.getcMInstance();
					cMdiff = top.ICEcoder.getcMdiffInstance();
					if (top.ICEcoder.updateDiffOnSave) {
						cMdiff.setValue(cM.getValue());
					};
				';

				// Finally, set previous files, indicate changes, set saved points and redo tabs
				$doNext .= '
						top.ICEcoder.setPreviousFiles();
						setTimeout(function(){top.ICEcoder.indicateChanges()},4);
						top.ICEcoder.savedPoints[top.ICEcoder.selectedTab-1] = cM.changeGeneration();
						top.ICEcoder.redoTabHighlight(top.ICEcoder.selectedTab);';

				// Run our custom processes
				include_once("../processes/on-file-save.php");

			// ======================================================
			// MDT'S DON'T MATCH, OFFER TO LOAD FILE & SHOW DIFF VIEW
			// ======================================================

			} else {
				$loadedFile = toUTF8noBOM(file_get_contents($file,false,$context),true);
				$doNext = '
				var loadedFile = document.createElement("textarea");
				loadedFile.value = "'.str_replace('"','\\\"',str_replace("\r","\\\\r",str_replace("\n","\\\\n",str_replace("</textarea>","<ICEcoder:/:textarea>",$loadedFile)))).'";
				var refreshFile = top.ICEcoder.ask("'.$t['Sorry, this file...'].'\\\n'.$file.'\\\n\\\n'.$t['Reload this file...'].'");
				if (refreshFile) {
					var cM = top.ICEcoder.getcMInstance();
					var thisTab = top.ICEcoder.selectedTab;
					var userVersionFile = cM.getValue();
					/* Revert back to original */
					cM.setValue(loadedFile.value);
					top.ICEcoder.savedPoints[thisTab-1] = cM.changeGeneration();
					top.ICEcoder.openFileMDTs[top.ICEcoder.selectedTab-1] = "'.$filemtime.'";
					cM.clearHistory();
					/* Now for the new version in the diff pane */
					top.ICEcoder.setSplitPane(\'on\');
					var cMdiff = top.ICEcoder.getcMdiffInstance();
					cMdiff.setValue(userVersionFile);
				};';
				$finalAction = "nothing";
			}

		// ===================
		// FILE IS UNWRITEABLE
		// ===================

        	} else {
			$finalAction = "nothing";
			$doNext = "top.ICEcoder.message('".$t['Sorry, cannot save']."\\\\n".$file."');";
		}
		$doNext .= 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
	}
};









// ===================
// JSON DATA TO RETURN
// ===================

echo '{
	"file": {
		"absPath": "'.$file.'",
		"relPath": "'.$fileLoc.'/'.$fileName.'",
		"name":	"'.$fileName.'",
		"path": "'.dirname($file).'",
		"bytes": "'.filesize($file).'",
		"modifiedDT": "'.$filemtime.'"
	},
	"action": {
		"initial" : "'.$_GET["action"].'",
		"final" : "'.$finalAction.'",
		"timeStart": '.$_POST["timeStart"].',
		"timeEnd": 0,
		"timeTaken": 0,
		"csrf": "'.$_GET["csrf"].'",
		"doNext" : "'.preg_replace('/\r|\n/','',str_replace('	','',str_replace('"','\"',$doNext))).'top.ICEcoder.switchMode();"
	},
	"status": {
		"error" : '.($error ? 'true' : 'false').',
		"errorStr" : "'.$errorStr.'",
		"errorMsg" : "'.$errorMsg.'"
	}
}';
?>