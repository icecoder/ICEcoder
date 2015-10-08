<?php
include("headers.php");
include("settings.php");
include("ftp-control.php");
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
if (isset($_POST['newFileName']) && $_POST['newFileName']!="") {
	$file = $_POST['newFileName'];	// New file
} elseif (isset($_REQUEST['file'])) {
	$file = $_REQUEST['file'];	// Existing file
} else {
	$file = "";			// Error
	$finalAction = "nothing";
	$doNext = "";
	$error = true;
	$errorStr = "true";
	$errorMsg = $t['Sorry, bad filename...'];
};

// If we have file(s) to work with...
if (!$error) {
	// Replace pipes with slashes, after cleaning the chars
	$file = str_replace("|","/",strClean($file));

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
			($_GET['action']!="getRemoteFile" && !isset($ftpSite) && 
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
}

$doNext = "";
// If we're in FTP mode, start a connection and leave open for FTP actions
if (isset($ftpSite)) {
	ftpStart();
	// Show user warning if no good connection
	if (!$ftpConn || !$ftpLogin) {
		$doNext .= 'top.ICEcoder.message("Sorry, no FTP connection to '.$ftpHost.' for user '.$ftpUser.'");';
	}
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
		$fileVersionURLPart = isset($_GET["fileVersion"]) && $_GET["fileVersion"]!="undefined" ? "&fileVersion=".numClean($_GET['fileVersion']) : "";
		$doNext .= '
			top.ICEcoder.serverMessage();
			fileLoc = "'.$fileLoc.'";
			overwriteOK = false;
			noConflictSave = false;
			newFileName = top.ICEcoder.getInput("'.$t['Enter filename to...'].' "+(fileLoc!="" ? fileLoc : "/"),"");
			if (newFileName) {
				if (newFileName.substr(0,1)!="/") {newFileName = "/" + newFileName};
				newFileName = fileLoc + newFileName;

				/* Check if file/dir exists */
				top.ICEcoder.lastFileDirCheckStatusObj = false;
				top.ICEcoder.checkExists(newFileName);
				var thisInt = setInterval(function() {
					if (top.ICEcoder.lastFileDirCheckStatusObj != false) {
						clearInterval(thisInt);

						if (top.ICEcoder.lastFileDirCheckStatusObj.file && top.ICEcoder.lastFileDirCheckStatusObj.file.exists) {			
							overwriteOK = top.ICEcoder.ask("'.$t['That file exists...'].'");
						} else {
							noConflictSave = true;
						};
						
						/* Saving under conditions: Confirmation of overwrite or there is no filename conflict, it is a new file, in either case we can save */
						if (overwriteOK || noConflictSave) {
							newFileName = "'.(isset($ftpSite) ? "" : $docRoot).'" + newFileName;
							saveURL = "lib/file-control-xhr.php?action=save'.$fileMDTURLPart.$fileVersionURLPart.'&csrf='.$_GET["csrf"].'";

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
							xhr.send(\'timeStart='.$_POST["timeStart"].'&file='.$fileURL.'&newFileName=\'+newFileName+\'&contents=\'+top.ICEcoder.saveAsContent);
							top.ICEcoder.serverMessage("<b>'.$t['Saving'].'</b><br>" + "'.($finalAction == "Save" ? "newFileName" : "'".$fileName."'").'");
						}
					}
				},10);
			};

			/* UI dialog cancelling and saving contents for save as looparound */
			if (!newFileName || newFileName && !overwriteOK) {
				top.ICEcoder.saveAsContent = top.document.getElementById(\'saveTemp1\').value;
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

		if (!$demoMode && (isset($ftpSite) || (file_exists($file) && is_writable($file)) || isset($_POST['newFileName']) && $_POST['newFileName']!="")) {

			$filemtime = !isset($ftpSite) && $serverType=="Linux" ? filemtime($file) : "1000000";

			// =======================
			// MDT'S MATCH, WRITE FILE
			// =======================

			if (!(isset($_GET['fileMDT']))||$filemtime==$_GET['fileMDT']) {

				// FTP Saving
				if (isset($ftpSite)) {
					// Write our file contents
					$ftpFilepath = ltrim($fileLoc."/".$fileName,"/");
					// replace \r\n (Windows), \r (old Mac) and \n (Linux) line endings with whatever we chose to be lineEnding
					$contents = $_POST['contents'];
					$contents = str_replace("\r\n", $ICEcoder["lineEnding"], $contents);
					$contents = str_replace("\r", $ICEcoder["lineEnding"], $contents);
					$contents = str_replace("\n", $ICEcoder["lineEnding"], $contents);
					if (!ftpWriteFile($ftpConn, $ftpFilepath, $contents, $ftpMode)) {
						$doNext .= 'top.ICEcoder.message("Sorry, could not write '.$ftpFilepath.' at '.$ftpHost.'");';
					}
					$doNext .= 'top.ICEcoder.openFileMDTs[top.ICEcoder.selectedTab-1]="'.$filemtime.'";';
					$doNext .= '(function() {var x=top.ICEcoder.openFileVersions; var y=top.ICEcoder.selectedTab-1; x[y] = "undefined" != typeof x[y] ? x[y]+1 : 1})();top.ICEcoder.updateVersionsDisplay();';
				// Local saving
				} else {
					// Newly created files have the perms set too
					$setPerms = (!file_exists($file)) ? true : false;
					// get old file contents, if file exists, and count stats on usage \n and \r there
					// in this case we can keep line endings, which file had before, without
					// making code version control systems going crazy about line endings change in whole file. 
					$oldContents = file_exists($file)?file_get_contents($file):'';
					$unixNewLines = preg_match_all('/[^\r][\n]/u', $oldContents);
					$windowsNewLines = preg_match_all('/[\r][\n]/u', $oldContents);
					$fh = fopen($file, 'w') or die($t['Sorry, cannot save']);
					// replace \r\n (Windows), \r (old Mac) and \n (Linux) line endings with whatever we chose to be lineEnding
					$contents = $_POST['contents'];
					$contents = str_replace("\r\n", $ICEcoder["lineEnding"], $contents);
					$contents = str_replace("\r", $ICEcoder["lineEnding"], $contents);
					$contents = str_replace("\n", $ICEcoder["lineEnding"], $contents);
					if (($unixNewLines > 0) || ($windowsNewLines > 0)){
						if ($unixNewLines > $windowsNewLines){
							$contents = str_replace($ICEcoder["lineEnding"], "\n", $contents);
						} elseif ($windowsNewLines > $unixNewLines){
							$contents = str_replace($ICEcoder["lineEnding"], "\r\n", $contents);
						}
					}
					// Now write that content, close the file and clear the statcache
					fwrite($fh, $contents);
					fclose($fh);

					if ($setPerms) {
						chmod($file,octdec($ICEcoder['newFilePerms']));
					}
					clearstatcache();
					$filemtime = $serverType=="Linux" ? filemtime($file) : "1000000";
					$doNext .= 'top.ICEcoder.openFileMDTs[top.ICEcoder.selectedTab-1]="'.$filemtime.'";';
					$doNext .= '(function() {var x=top.ICEcoder.openFileVersions; var y=top.ICEcoder.selectedTab-1; x[y] = "undefined" != typeof x[y] ? x[y]+1 : 1})();top.ICEcoder.updateVersionsDisplay();';
				}

				// Save a version controlled backup source of the file
				if ($ICEcoder["backupsKept"]) {
					$backupDirFormat = "Y-m-d";

					// Establish the base, host and date dir parts...
					$backupDirBase = str_replace("\\","/",dirname(__FILE__))."/../backups/";
					$backupDirHost = isset($ftpSite) ? parse_url($ftpSite,PHP_URL_HOST) : "localhost";
					$backupDirDate = date($backupDirFormat);

					// Establish an array of dirs from base to our file location
					$subDirsArray = explode("/",ltrim($fileLoc,"/"));
					array_unshift($subDirsArray,$backupDirHost,$backupDirDate);
					// Make any dirs that don't exist if full path isn't there
					if (!is_dir($backupDirBase.implode("/",$subDirsArray))) {
						$pathIncr = "";
						for ($i=0; $i<count($subDirsArray); $i++) {
							$pathIncr .= $subDirsArray[$i]."/";
							// If this subdir isn't there, make it
							if (!is_dir($backupDirBase.$pathIncr)) {
								mkdir($backupDirBase.$pathIncr);
							}
						}
					}
					// We should have our dir path now so set that
					$backupDir = $backupDirBase.implode("/",$subDirsArray);
					// Work out an available filename (we postfix a number in parens)
					for ($i=1; $i<1000000000; $i++) {
						if (!file_exists($backupDir.'/'.$fileName." (".$i.")")) {
							$backupFileName = $fileName." (".$i.")";
							$backupFileNum = $i;
							$i=1000000000;
						}
					}

					// Now save within that backup dir and clear the statcache
					$fh = fopen($backupDir."/".$backupFileName, "w") or die($t['Sorry, cannot save...']);
					fwrite($fh, $contents);
					fclose($fh);
					clearstatcache();

					// Log the version count in an index file, which contains saved version counts
					$backupIndex = $backupDirBase.$backupDirHost."/".$backupDirDate."/.versions-index";
					// Have a version index already? Update contents
					if (file_exists($backupIndex)) {
						$versionsInfo = "";
						$versionsInfoOrig = file_get_contents($backupIndex,false,$context);
						$versionsInfoOrig = explode("\n",$versionsInfoOrig);
						$replacedLine = false;
						// For each line, either re-set number or simply include the line
						for ($i=0; $i<count($versionsInfoOrig); $i++) {
							if (strpos($versionsInfoOrig[$i],$fileLoc."/".$fileName." = ") === 0) {
								$versionsInfo .= $fileLoc."/".$fileName." = ".$backupFileNum.PHP_EOL;
								$replacedLine = true;
							} else {
								$versionsInfo .= $versionsInfoOrig[$i].PHP_EOL;
							}
						}
						// Didn't find our line in the file? Add it to the end
						if (!$replacedLine) {
							$versionsInfo .= $fileLoc."/".$fileName." = ".$backupFileNum.PHP_EOL;
						}
					// No version file yet, set the first line
					} else {
						$versionsInfo = $fileLoc."/".$fileName." = ".$backupFileNum.PHP_EOL;
					}
					$versionsInfo = rtrim($versionsInfo,PHP_EOL);
					$fh = fopen($backupIndex, 'w') or die($t['Sorry, cannot save...']);
					fwrite($fh, $versionsInfo);
					fclose($fh);
					clearstatcache();

					// Finally, clear any old backup dirs than user set X days (inclusive)
					$backupDirsList = scandir($backupDirBase.$backupDirHost);
					$backupDirsKeep = array();
					for ($i=0; $i<=$ICEcoder["backupsDays"]; $i++) {
						$backupDirsKeep[] = date($backupDirFormat, strtotime('-'.$i.' day',strtotime($backupDirDate)));
					}
					for ($i=0; $i<count($backupDirsList); $i++) {
						if ($backupDirsList[$i] != "." && $backupDirsList[$i] != ".." && !in_array($backupDirsList[$i],$backupDirsKeep)) {
							rrmdir($backupDirBase.$backupDirHost."/".$backupDirsList[$i]);
						}
					}
				}
				
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
							try {top.ICEcoder.doResponsive();} catch(err) {};
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
				// Only applicable for local files
				$loadedFile = toUTF8noBOM(file_get_contents($file,false,$context),true);
				$fileCountInfo = getVersionsCount($fileLoc,$fileName);
				$doNext .= '
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
					top.ICEcoder.openFileVersions[top.ICEcoder.selectedTab-1] = "'.$fileCountInfo['count'].'";
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
			$doNext .= "top.ICEcoder.message('".$t['Sorry, cannot save']."\\\\n".$file."');";
		}
		$doNext .= 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
	}
};

// ==========
// NEW FOLDER
// ==========

if (!$error && $_GET['action']=="newFolder") {
	if (!$demoMode && ($ftpSite || is_writable($docRoot.$fileLoc))) {
		$updateFM = false;
		// FTP
		if (isset($ftpSite)) {
			$ftpFilepath = ltrim($fileLoc."/".$fileName,"/");
			if (!ftpMkDir($ftpConn, octdec($ICEcoder['newDirPerms']), $ftpFilepath)) {
				$doNext .= 'top.ICEcoder.message("Sorry, could not create dir '.$ftpFilepath.' at '.$ftpHost.'");';
			} else {
				$updateFM = true;
			}
		// Local
		} else {
			mkdir($file, octdec($ICEcoder['newDirPerms']));
			// Reload file manager
			$updateFM = true;
		}
		// Update file manager on success
		if ($updateFM) {
			$doNext .= 'top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'add\',\''.$fileLoc.'\',\''.$fileName.'\',false,false,false,\'folder\');';
		}
		$finalAction = "newFolder";
		// Run our custom processes
		include_once("../processes/on-new-dir.php");
	} else {
		$doNext .= "top.ICEcoder.message('".$t['Sorry, cannot create...']."\\\\n".$fileLoc."');";
		$finalAction = "nothing";
	}
	$doNext .= 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
};

// ================
// MOVE FILE/FOLDER
// ================

if (!$error && $_GET['action']=="move") {
	if (isset($ftpSite)) {
		$srcDir = ltrim(str_replace("|","/",strClean($_GET['oldFileName'])),"/");
		$tgtDir = ltrim($fileLoc."/".$fileName,"/");
	} else {
		$srcDir = $docRoot.$iceRoot.str_replace("|","/",strClean($_GET['oldFileName']));
		$tgtDir = $docRoot.$fileLoc."/".$fileName;
	}
	if ($srcDir != $tgtDir && $fileLoc != "") {
		if (!$demoMode && ($ftpSite || is_writable($srcDir))) {
			$updateFM = false;
			// FTP
			if (isset($ftpSite)) {
				if (!ftpRename($ftpConn, $srcDir, $tgtDir)) {
					$doNext .= 'top.ICEcoder.message("Sorry, could not rename '.$srcDir.' to '.$tgtDir.'");';
				} else {
					$ftpFileDirInfo = ftpGetFileInfo($ftpConn, ltrim($fileLoc,"/"), $fileName);
					$fileOrFolder = $ftpFileDirInfo['type'] == "directory" ? "folder" : "file";
					$updateFM = true;
				}
			// Local
			} else {
				if(rename($srcDir,$tgtDir)) {
					// Is a dir or file (needed to create new item in file manager)
					$fileOrFolder = is_dir($docRoot.$fileLoc."/".$fileName) ? "folder" : "file";
					$updateFM = true;
				}
			}
			// Update file manager on success
			if ($updateFM) {
				$doNext .= 'top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'move\',\''.$fileLoc.'\',\''.$fileName.'\',\'\',\''.str_replace($iceRoot,"",strClean(str_replace("|","/",$_GET['oldFileName']))).'\',false,$fileOrFolder);';
			}
			$finalAction = "move";
			// Run our custom processes
			include_once("../processes/on-file-dir-move.php");
		} else {
			$doNext .= "top.ICEcoder.message('".$t['Sorry, cannot move']."\\\\n".str_replace("|","/",strClean($_GET['oldFileName']))."\\\\n\\\\n".$t['Maybe public write...']."');";
			$finalAction = "nothing";
		}
	} else {
		$doNext .= "";
		$finalAction = "nothing";
	}
	$doNext .= 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
};

// ==================
// RENAME FILE/FOLDER
// ==================

if (!$error && $_GET['action']=="rename") {
	if (!$demoMode && ($ftpSite || is_writable($docRoot.$iceRoot.str_replace("|","/",strClean($_GET['oldFileName']))))) {
		$updateFM = false;
		// FTP
		if (isset($ftpSite)) {
			$ftpFilepath = ltrim($fileLoc."/".$fileName,"/");
			if (!ftpRename($ftpConn, ltrim(strClean($_GET['oldFileName']),"/"), $ftpFilepath)) {
				$doNext .= 'top.ICEcoder.message("Sorry, could not rename '.ltrim(strClean($_GET['oldFileName']),"/").' to '.$ftpFilepath.'");';
			} else {
				$updateFM = true;
			}
		// Local
		} else {
			rename($docRoot.$iceRoot.str_replace("|","/",strClean($_GET['oldFileName'])),$docRoot.$fileLoc."/".$fileName);
			$updateFM = true;
		}
		// Update file manager on success
		if ($updateFM) {
			$doNext .= 'top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'rename\',\''.$fileLoc.'\',\''.$fileName.'\',\'\',\''.str_replace($iceRoot,"",strClean($_GET['oldFileName'])).'\');';
		}
		$finalAction = "rename";
		// Run our custom processes
		include_once("../processes/on-file-dir-rename.php");
	} else {
		$doNext .= "top.ICEcoder.message('".$t['Sorry, cannot rename']."\\\\n".strClean($_GET['oldFileName'])."\\\\n\\\\n".$t['Maybe public write...']."');";
		$finalAction = "nothing";
	}
	$doNext .= 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
};

// =================
// PASTE FILE/FOLDER
// =================

if (!isset($ftpSite) && !$error && $_GET['action']=="paste") {
	$source = $file;
	$dest = str_replace("//","/",$docRoot.$iceRoot.strClean(str_replace("|","/",$_GET['location']))."/".basename($source));
	if (!$demoMode && is_writable(dirname($dest))) {
		if (is_dir($source)) {
			$fileOrFolder = "folder";
			if (!is_dir($dest)) {
				mkdir($dest, octdec($ICEcoder['newDirPerms']));
			} else {
				for ($i=2; $i<1000000000; $i++) {
					if (!is_dir($dest." (".$i.")")) {
						$dest = $dest." (".$i.")";
						mkdir($dest, octdec($ICEcoder['newDirPerms']));
						$i=1000000000;
					}
				}
			}
			foreach ($iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
				RecursiveIteratorIterator::SELF_FIRST) as $item
				) {
				if ($item->isDir()) {
					mkdir($dest.DIRECTORY_SEPARATOR.$iterator->getSubPathName(), octdec($ICEcoder['newDirPerms']));
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
		$doNext .= 'top.ICEcoder.updateFileManagerList(\'add\',\''.strClean(str_replace("|","/",$_GET['location'])).'\',\''.basename($dest).'\',false,false,false,\''.$fileOrFolder.'\');';
		$finalAction = "pasteFile";
		// Run our custom processes
		include_once("../processes/on-file-dir-paste.php");
	} else {
		$doNext .= "top.ICEcoder.message('".$t['Sorry, cannot copy']." \\\\n".str_replace($docRoot,"",$source)."\\\\n ".$t['into']." \\\\n".str_replace($docRoot,"",$dest)."');";
		$finalAction = "nothing";
	}
	$doNext .= 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
};

// ==============
// UPLOAD FILE(S)
// ==============

if (!isset($ftpSite) && !$error && $_GET['action']=="upload") {
	if (!$demoMode) {
		class fileUploader {  
			public function __construct($uploads) {
				global $docRoot,$iceRoot,$ICEcoder,$doNext;
				$uploadDir=$docRoot.$iceRoot.str_replace("..","",str_replace("|","/",strClean($_POST['folder'])."/"));
				foreach($uploads as $current) {  
					$this->uploadFile=$uploadDir.$current->name;
					$fileName = $current->name;
					// Get & set existing perms for existing files, or set to newFilePerms setting for new files
                                        if (file_exists($this->uploadFile)) {
                                                $chmodInfo = substr(sprintf('%o', fileperms($this->uploadFile)), -4);
                                                $setPerms = substr($chmodInfo,1,3); // reduces 0755 down to 755
                                        } else {
                                                $setPerms = $ICEcoder['newFilePerms'];
                                        }
					if ($this->upload($current,$this->uploadFile,$setPerms)) {
						$doNext .= 'top.ICEcoder.updateFileManagerList(\'add\',top.ICEcoder.selectedFiles[top.ICEcoder.selectedFiles.length-1].replace(/\|/g,\'/\'),\''.str_replace("'","\'",$fileName).'\',false,false,true,\'file\'); top.ICEcoder.serverMessage("'.$t['Uploaded file(s) OK'].'");setTimeout(function(){top.ICEcoder.serverMessage();},2000);';
						$finalAction = "upload";
					} else {
						$doNext .= "top.ICEcoder.message('".$t['Sorry, cannot upload']." \\\\n".$fileName."\\\\n ".$t['into']." \\\\n'+top.ICEcoder.selectedFiles[top.ICEcoder.selectedFiles.length-1].replace(/\|/g,'/'));";
						$finalAction = "nothing";
					}
				}  
			}  

			public function upload($current,$uploadFile,$setPerms){ 
				if(move_uploaded_file($current->tmp_name,$uploadFile)){
					chmod($uploadFile,octdec($setPerms));
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
		// Run our custom processes
		include_once("../processes/on-file-upload.php");
	} else {
		$doNext .= "top.ICEcoder.message('".$t['Sorry, cannot upload...']."');";
		$finalAction = "nothing";
	}

	$doNext .= "top.ICEcoder.hideFileMenu();top.document.getElementById('fileInput').value='';top.ICEcoder.showHide('hide',top.document.getElementById('loadingMask'));";

	// Upload is not handled by XHR methods, but form post, so we need to manually trigger $doNext in a script tag
	echo "<script>".$doNext."</script>";
};

// ========================
// DELETE FILE(S)/FOLDER(S)
// ========================

if (!$error && $_GET['action']=="delete") {
	$filesArray = explode(";",$file); // May contain more than one file here
	// FTP
	if (isset($ftpSite)) {
		if (count($filesArray) == 1) {
			$ftpFileDirInfo = ftpGetFileInfo($ftpConn, ltrim($fileLoc,"/"), $fileName);
			$itemType = $ftpFileDirInfo['type'] == "directory" ? "dir" : "file";
			$itemPath = ltrim($fileLoc."/".$fileName,"/");
			if (!$demoMode && ftpDelete($ftpConn,$itemType,$itemPath)) {
				if ($fileLoc=="" || $fileLoc=="\\") {$fileLoc="/";};
				// Reload file manager
				$doNext .= 'top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'delete\',\''.$fileLoc.'\',\''.$fileName.'\');';
				$finalAction = "delete";
				// Run our custom processes
				include_once("../processes/on-file-dir-delete.php");
			} else {
				$doNext .= "top.ICEcoder.message('".$t['Sorry, cannot delete']."\\\\n".$fileLoc."/".$fileName."');";
				$finalAction = "nothing";
			}
		} else {
			$doNext .= "top.ICEcoder.message('".$t['Sorry, cannot delete more...']."');";
			$finalAction = "nothing";
		}
	// Local
	} else {
		for ($i=0;$i<count($filesArray);$i++) {
			$fullPath = str_replace($docRoot,"",$filesArray[$i]);
			$fullPath = str_replace($iceRoot,"",$fullPath);
			$fullPath = $docRoot.$iceRoot.$fullPath;

			if (rtrim($fullPath,"/") == rtrim($docRoot,"/")) {
				$doNext .= "top.ICEcoder.message('".$t['Sorry, cannot delete...']."');";
			} else if (!$demoMode && is_writable($fullPath)) {
				is_dir($fullPath)
					? rrmdir($fullPath)
					: unlink($fullPath);
				$fileName = basename($fullPath);
				$fileLoc = dirname(str_replace($docRoot,"",$fullPath));
				if ($fileLoc=="" || $fileLoc=="\\") {$fileLoc="/";};
				// Reload file manager
				$doNext .= 'top.ICEcoder.selectedFiles=[];top.ICEcoder.updateFileManagerList(\'delete\',\''.$fileLoc.'\',\''.$fileName.'\');';
				$finalAction = "delete";
				// Run our custom processes
				include_once("../processes/on-file-dir-delete.php");
			} else {
				$doNext .= "top.ICEcoder.message('".$t['Sorry, cannot delete']."\\\\n".str_replace($docRoot,"",$fullPath)."');";
				$finalAction = "nothing";
			}
		}
	}
	$doNext .= 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
};

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
};

// ======================
// REPLACE TEXT IN A FILE
// ======================

if (!isset($ftpSite) && !$error && $_GET['action']=="replaceText") {
	if (!$demoMode && is_writable($file)) {
		$loadedFile = toUTF8noBOM(file_get_contents($file,false,$context),true);
		$newContent = str_replace(strClean($_GET['find']),strClean($_GET['replace']),$loadedFile);
		$fh = fopen($file, 'w') or die($t['Sorry, cannot save']);
		fwrite($fh, $newContent);
		fclose($fh);
		$finalAction = "replaceText";
		// Run our custom processes
		include_once("../processes/on-file-replace-text.php");
	} else {
		$doNext .= "top.ICEcoder.message('".$t['Sorry, cannot replace...']."\\\\n".$file."');";
		$finalAction = "nothing";
	}
	$doNext .= 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
};

// ==========================
// GET CONTENTS OF REMOTE URL
// ==========================

if (!isset($ftpSite) && !$error && $_GET['action']=="getRemoteFile") {
	$lineNumber = max(isset($_REQUEST['lineNumber'])?intval($_REQUEST['lineNumber']):1, 1);
	if ($remoteFile = toUTF8noBOM(file_get_contents($file,false,$context),true)) {
		// replace \r\n (Windows), \r (old Mac) and \n (Linux) line endings with whatever we chose to be lineEnding
		$remoteFile = str_replace("\r\n", $ICEcoder["lineEnding"], $remoteFile);
		$remoteFile = str_replace("\r", $ICEcoder["lineEnding"], $remoteFile);
		$remoteFile = str_replace("\n", $ICEcoder["lineEnding"], $remoteFile);
		$doNext .= 'top.ICEcoder.newTab();';
		$doNext .= 'top.ICEcoder.getcMInstance().setValue(\''.str_replace("\r","",str_replace("\t","\\\\t",str_replace("\n","\\\\n",str_replace("'","\\\\'",str_replace("\\","\\\\",preg_quote($remoteFile)))))).'\');';
		$doNext .= 'top.ICEcoder.goToLine('.$lineNumber.');';
		$finalAction = "getRemoteFile";
		// Run our custom processes
		include_once("../processes/on-get-remote-file.php");
	} else {
		$finalAction = "nothing";
		$doNext .= 'top.ICEcoder.message(\''.$t['Sorry, could not...'].' '.$file.'\');';
	}
	$doNext .= 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
};

// =======================
// CHANGING FILE/DIR PERMS
// =======================

if (!$error && $_GET['action']=="perms") {
	if (!$demoMode && ($ftpSite || is_writable($file))) {
		$updateFM = false;
		// FTP
		if (isset($ftpSite)) {
			$ftpFilepath = ltrim($fileLoc."/".$fileName,"/");
			if (!ftpPerms($ftpConn, octdec(numClean($_GET['perms'])), $ftpFilepath)) {
				$doNext .= 'top.ICEcoder.message("Sorry, could not set perms on '.$ftpFilepath.' at '.$ftpHost.'");';
			} else {
				$updateFM = true;
			}
		// Local
		} else {
			chmod($file,octdec(numClean($_GET['perms'])));
			// Reload file manager
			$updateFM = true;
		}
		// Update file manager on success
		if ($updateFM) {
			$doNext .= 'top.ICEcoder.updateFileManagerList(\'chmod\',\''.$fileLoc.'\',\''.$fileName.'\',\''.numClean($_GET['perms']).'\');';
		}
		$finalAction = "perms";
		// Run our custom processes
		include_once("../processes/on-file-dir-perms.php");
	} else {
		$finalAction = "nothing";
		$doNext .= "top.ICEcoder.message('".$t['Sorry, cannot change...']." \\n".strClean($file)."');";
	}
	$doNext .= 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
};

// ====================
// CHECK FOR A FILE/DIR
// ====================

if (!isset($ftpSite) && !$error && $_GET['action']=="checkExists") {
	// This action is called under seperate AJAX call and the responseText object stored in top.ICEcoder.lastFileDirCheckStatusObj
	// Nothing really done here though, we do something with the responseText
	$finalAction = "checkExists";
	$doNext .= 'top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);';
};

// ===================
// JSON DATA TO RETURN
// ===================

// No $filemtime yet? Get it now!
if (!isset($filemtime)) {
	$filemtime = $serverType=="Linux" ? filemtime($file) : "1000000";
}
// Set $timeStart, use 0 if not available
$timeStart = isset($_POST["timeStart"]) ? $_POST["timeStart"] : 0;

if (isset($ftpSite)) {
	// Get info on dir/file now
	$ftpFileDirInfo = ftpGetFileInfo($ftpConn, ltrim($fileLoc,"/"), $fileName);
	// End the connection
	ftpEnd();
	// Then set info
	$itemAbsPath = $ftpRoot.$fileLoc.'/'.$fileName;
	$itemPath = dirname($ftpRoot.$fileLoc.'/'.$fileName);
	$itemBytes = $ftpFileDirInfo['size'];
	$itemType = (isset($ftpFileDirInfo['type']) ? ($ftpFileDirInfo['type'] == "directory" ? "dir" : "file") : "unknown");
	$itemExists = (isset($ftpFileDirInfo['type']) ? "true" : "false");
} else {
	$itemAbsPath = $file;
	$itemPath = dirname($file);
	$itemBytes = filesize($file);
	$itemType = (file_exists($file) ? (is_dir($file) ? "dir" : "file") : "unknown");
	$itemExists = (file_exists($file) ? "true" : "false");
}

echo '{
	"file": {
		"absPath": "'.$itemAbsPath.'",
		"relPath": "'.$fileLoc.'/'.$fileName.'",
		"name":	"'.$fileName.'",
		"path": "'.$itemPath.'",
		"bytes": "'.$itemBytes.'",
		"modifiedDT": "'.$filemtime.'",
		"type": "'.$itemType.'",
		"exists": '.$itemExists.'
	},
	"action": {
		"initial" : "'.$_GET["action"].'",
		"final" : "'.$finalAction.'",
		"timeStart": '.$timeStart.',
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