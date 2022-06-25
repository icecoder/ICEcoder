<?php declare(strict_types=1);

namespace ICEcoder;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ICEcoder\System;
use scssc;
use lessc;

class File
{
    private $systemClass;

    public function __construct()
    {
        $this->systemClass = new System();
    }

    public function check() {
        global $file, $fileOrig, $docRoot, $iceRoot, $fileLoc, $fileName, $error, $errorStr, $errorMsg;
        // Replace pipes with slashes, then establish the actual name as we may have HTML entities in filename
        // Infact we may have &amplt; which when decoded is &lt; and decoded again is original < so decoding twice is needed
        $file = html_entity_decode(html_entity_decode(str_replace("|", "/", $file)));

        // Put the original $file var aside for use
        $fileOrig = $file;

        // Trim any +'s or spaces from the end of file
        $file = rtrim(rtrim($file, '+'), ' ');

        // Also remove [NEW] from $file, we can consider $_GET['action'] or $fileOrig to pick that up
        $file = preg_replace('/\[NEW\]$/', '', $file);

        // Make each path in $file a full path (; separated list)
        $allFiles = explode(";", $file);
        for ($i = 0; $i < count($allFiles); $i++) {
            if (false === strpos($allFiles[$i],$docRoot) && "getRemoteFile" !== $_GET['action']) {
                $allFiles[$i] = str_replace("|", "/", $docRoot . $iceRoot . $allFiles[$i]);
            }
        };
        $file = implode(";", $allFiles);

        // Establish the $fileLoc and $fileName (used in single file cases, eg opening. Multiple file cases, eg deleting, is worked out in that loop)
        $fileLoc = substr(str_replace($docRoot, "", $file), 0, strrpos(str_replace($docRoot, "", $file), "/"));
        $fileName = basename($file);

        // Check through all files to make sure they're valid/safe
        $allFiles = explode(";", $file);
        for ($i = 0; $i < count($allFiles); $i++) {

            // Uncomment to alert and console.log the action and file, useful for debugging
            // echo ";alert('" . xssClean($_GET['action'], "html") . " : " . $allFiles[$i] . "');console.log('" . xssClean($_GET['action'], "html") . " : " . $allFiles[$i] . "');";

            $bannedFileFound = false;
            for ($j = 0; $j < count($_SESSION['bannedFiles']); $j++) {
                $thisFile = str_replace("*", "", $_SESSION['bannedFiles'][$j]);
                if ("" != $thisFile && false !== strpos($allFiles[$i], $thisFile)) {
                    $bannedFileFound = true;
                }
            }

            // Die if the file requested isn't something we expect
            if (
                // On the banned file/dir list
                ($bannedFileFound) ||
                // A local folder that isn't the doc root or starts with the doc root
                ("getRemoteFile" !== $_GET['action'] &&
                    rtrim($allFiles[$i], "/") !== rtrim($docRoot, "/") &&
                    true === realpath(rtrim(dirname($allFiles[$i]), "/")) &&
                    0 !== strpos(realpath(rtrim(dirname($allFiles[$i]), "/")), realpath(rtrim($docRoot, "/")))
                ) ||
                // Or a remote URL that doesn't start http
                ("getRemoteFile" === $_GET['action'] && 0 !== strpos($allFiles[$i], "http"))
            ) {
                $error = true;
                $errorStr = "true";
                $errorMsg = "Sorry! - problem with file requested";
            };
        }
    }

    public function updateUI() {
        global $fileLoc, $fileName;

        $doNext = "";
        // Reload file manager, rename tab & remove old file highlighting if it was a new file
        if (isset($_POST['newFileName']) && "" != $_POST['newFileName']) {
            $doNext .= 'ICEcoder.selectedFiles=[];';
            $doNext .= 'ICEcoder.updateFileManagerList(\'add\', \'' . $fileLoc . '\', \'' . $fileName . '\', false, false, false, \'file\');';
            $doNext .= 'ICEcoder.renameTab(ICEcoder.selectedTab, \'' . $fileLoc . "/" . $fileName . '\');';
        }

        return $doNext;
    }

    public function updateFileManager($action, $fileLoc, $fileName, $perms, $oldFile, $uploaded, $fileOrFolder) {
        global $doNext;
        $doNext .= "ICEcoder.updateFileManagerList('" .
            $action . "', '" .
            $fileLoc . "', '" .
            $fileName . "', '" .
            $perms . "', '" .
            $oldFile . "', '" .
            $uploaded . "', '" .
            $fileOrFolder . "');";

        return $doNext;
    }

    public function load() {
        global $file, $fileLoc, $fileName, $t, $lineNumber;
        echo 'action="load";';
        $lineNumber = max(isset($_GET['lineNumber']) ? intval($_GET['lineNumber']) : 1, 1);
        // Check this file isn't on the banned list at all
        $canOpen = true;
        for ($i = 0; $i < count($_SESSION['bannedFiles']); $i++) {
            if ("" !== str_replace("*", "", $_SESSION['bannedFiles'][$i]) && false !== strpos($file, str_replace("*", "", $_SESSION['bannedFiles'][$i]))) {
                $canOpen = false;
            }
        }

        if (false === $canOpen) {
            echo 'fileType="nothing"; parent.parent.ICEcoder.message(\'' . $t['Sorry, could not...'] . ' ' . $fileLoc . "/" . $fileName . '\');';
        } elseif (file_exists($file)) {
            $finfo = "text";
            // Determine what to do based on mime type
            if (function_exists('finfo_open')) {
                $finfoMIME = finfo_open(FILEINFO_MIME);
                $finfo = finfo_file($finfoMIME, $file);
                finfo_close($finfoMIME);
            } else {
                $fileExt = explode(" ", pathinfo($file, PATHINFO_EXTENSION));
                $fileExt = $fileExt[0];
                if (false !== array_search($fileExt, ["gif", "jpg", "jpeg", "png"])) {
                    $finfo = "image";
                };
                if (false !== array_search($fileExt, ["doc", "docx", "ppt", "rtf", "pdf", "zip", "tar", "gz", "swf", "asx", "asf", "midi", "mp3", "wav", "aiff", "mov", "qt", "wmv", "mp4", "odt", "odg", "odp"])) {
                    $finfo = "other";
                };
            }
            if (0 === strpos($finfo, "text") || 0 === strpos($finfo, "application/json") || 0 === strpos($finfo, "application/xml") || false !== strpos($finfo, "empty")) {
                echo 'fileType="text";';

                // Get data from file
                $loadedFile = toUTF8noBOM(getData($file), true);

                $encoding = ini_get("default_charset");
                if ("" == $encoding) {
                    $encoding = "UTF-8";
                }
                // Get content and set HTML entities on it according to encoding
                $loadedFile = htmlentities($loadedFile, ENT_COMPAT, $encoding);
                // Remove \r chars and replace \n with carriage return HTML entity char
                $loadedFile = preg_replace('/\\r/', '', $loadedFile);
                $loadedFile = preg_replace('/\\n/', '&#13;', $loadedFile);
                echo '</script><textarea name="loadedFile" id="loadedFile">' . $loadedFile . '</textarea><script>';
                // Run our custom processes
                $extraProcessesClass = new ExtraProcesses($fileLoc, $fileName);
                $extraProcessesClass->onFileLoad();
            } else if (0 === strpos($finfo, "image")) {
                echo 'fileType="image";fileName=\'' . $fileLoc . "/" . $fileName . '\';';
            } else {
                echo 'fileType="other";window.open(\'http://' . $_SERVER['SERVER_NAME'] . $fileLoc . "/" . $fileName . '\');';
            };
        } else {
            echo 'fileType="nothing"; parent.parent.ICEcoder.message(\'' . $t['Sorry'] . ', ' . $fileLoc . "/" . $fileName . ' ' . $t['does not seem...'] . '\');';
        }
    }

    public function returnLoadTextScript() {
        global $t, $file, $fileLoc, $fileName, $lineNumber, $serverType;

        $script = 'if ("text" === fileType) {';

        if (file_exists($file)) {
            $script .= '
            setTimeout(function() {
                if (!parent.parent.ICEcoder.content.contentWindow.createNewCMInstance) {
                    console.log(\'' .$t['There was a...'] . '\');
                    window.location.reload(true);
                } else {
                    parent.parent.ICEcoder.loadingFile = true;
                    // Reset the various states back to their initial setting
                    selectedTab = parent.parent.ICEcoder.openFiles.length;	// The tab that\'s currently selected
                    // Finally, store all data, show tabs etc
                    parent.parent.ICEcoder.createNewTab(false, \'' . $fileLoc . '/' . $fileName . '\');
                    parent.parent.ICEcoder.cMInstances.push(parent.parent.ICEcoder.nextcMInstance);
                    parent.parent.ICEcoder.setLayout();
                    parent.parent.ICEcoder.content.contentWindow.createNewCMInstance(parent.parent.ICEcoder.nextcMInstance);
    
                    // Set the value & innerHTML of the code textarea to that of our loaded file plus make it visible (it\'s hidden on ICEcoder\'s load)
                    parent.parent.ICEcoder.switchMode();
                    cM = parent.parent.ICEcoder.getcMInstance();
                    cM.setValue(document.getElementById(\'loadedFile\').value);
                    parent.parent.ICEcoder.savedPoints[parent.parent.ICEcoder.selectedTab - 1] = cM.changeGeneration();
                    parent.parent.ICEcoder.savedContents[parent.parent.ICEcoder.selectedTab - 1] = cM.getValue();
                    parent.parent.document.getElementById(\'content\').style.visibility = \'visible\';
                    parent.parent.ICEcoder.switchTab(parent.parent.ICEcoder.selectedTab, \'noFocus\');
                    setTimeout(function(){parent.parent.ICEcoder.filesFrame.contentWindow.focus();}, 0);
    
                    // Then clean it up, set the text cursor, update the display and get the character data
                    parent.parent.ICEcoder.contentCleanUp();
                    parent.parent.ICEcoder.content.contentWindow[\'cM\' + parent.parent.ICEcoder.cMInstances[parent.parent.ICEcoder.selectedTab - 1]].removeLineClass(parent.parent.ICEcoder[\'cMActiveLinecM\' + parent.parent.ICEcoder.cMInstances[parent.parent.ICEcoder.selectedTab - 1]], "background");
                    parent.parent.ICEcoder[\'cMActiveLinecM\'+parent.parent.ICEcoder.selectedTab] = parent.parent.ICEcoder.content.contentWindow[\'cM\' + parent.parent.ICEcoder.cMInstances[parent.parent.ICEcoder.selectedTab - 1]].addLineClass(0, "background", "cm-s-activeLine");
                    parent.parent.ICEcoder.nextcMInstance++;
                    parent.parent.ICEcoder.openFileMDTs.push(\'' . ("Windows" !== $serverType ? filemtime($file) : "1000000") . '\');
                    parent.parent.ICEcoder.openFileVersions.push(' . getVersionsCount($fileLoc, $fileName)['count'] .');
                    parent.parent.ICEcoder.updateVersionsDisplay();
    
                    parent.parent.ICEcoder.goToLine(' . $lineNumber . ');
                    parent.parent.ICEcoder.loadingFile = false;
                }
            }, 4);';
        } else {
            $script .= '
            setTimeout(function() {
                if (!parent.parent.ICEcoder.content.contentWindow.createNewCMInstance) {
                    console.log(\'' .$t['There was a...'] . '\');
                    window.location.reload(true);
                }
            }, 4);';
        }

        $script .= "}";

        return $script;
    }

    public function returnLoadImageScript() {
        global $fileLoc, $fileName, $t;
        $script = '
        if ("image" === fileType) {
            parent.parent.document.getElementById(\'blackMask\').style.visibility = "visible";
            parent.parent.document.getElementById(\'mediaContainer\').innerHTML =
                "<canvas id=\"canvasPicker\" width=\"1\" height=\"1\" style=\"position: absolute; margin: 10px 0 0 10px; cursor: crosshair\"></canvas>" +
                "<img src=\"' . $fileLoc . "/" . $fileName . "?unique=" . microtime(true) .'\" class=\"imgDisplay\" onLoad=\"reducedImgMsg = (this.naturalWidth > 700 || this.naturalHeight > 500) ? \', ' .$t['displayed at'] . '\' + this.width + \' x \' + this.height : \'\'; document.getElementById(\'imgInfo\').innerHTML += \' (\' + this.naturalWidth + \' x \' + this.naturalHeight + reducedImgMsg + \')\'; ICEcoder.initCanvasImage(this); ICEcoder.interactCanvasImage(this)\"><br>" +
            "<div style=\"display: inline-block; margin-top: -10px; border: solid 10px #fff; color: #000; background-color: #fff\" id=\"imgInfo\"  onmouseover=\"parent.parent.ICEcoder.overPopup=true\" onmouseout=\"parent.parent.ICEcoder.overPopup=false\">" +
            "<b>' . $fileLoc . "/" . $fileName . '</b>" +
            "</div><br>" +
            "<div id=\"canvasPickerColorInfo\">"+
            "<input type=\"text\" id=\"hexMouseXY\" style=\"border: 1px solid #888; border-right: 0; width: 70px\" onmouseover=\"parent.parent.ICEcoder.overPopup=true\" onmouseout=\"parent.parent.ICEcoder.overPopup=false\"></input>" +
            "<input type=\"text\" id=\"rgbMouseXY\" style=\"border: 1px solid #888; margin-right: 10px; width: 70px\" onmouseover=\"parent.parent.ICEcoder.overPopup=true\" onmouseout=\"parent.parent.ICEcoder.overPopup=false\"></input>" +
            "<input type=\"text\" id=\"hex\" style=\"border: 1px solid #888; border-right: 0; width: 70px\" onmouseover=\"parent.parent.ICEcoder.overPopup=true\" onmouseout=\"parent.parent.ICEcoder.overPopup=false\"></input>" +
            "<input type=\"text\" id=\"rgb\" style=\"border: 1px solid #888; width: 70px\" onmouseover=\"parent.parent.ICEcoder.overPopup=true\" onmouseout=\"parent.parent.ICEcoder.overPopup=false\"></input>"+
            "</div>"+
            "<div id=\"canvasPickerCORSInfo\" style=\"display: none; padding-top: 4px\">CORS not enabled on resource site</div>";
            parent.parent.document.getElementById(\'floatingContainer\').style.background = "#fff url(\'' .($fileLoc . "/" . $fileName . "?unique=" . microtime(true)) .'\') no-repeat 0 0";
        }';

        return $script;
    }

    public function handleSaveLooparound($fileDetails, $finalAction, $t) {
        global $newFileAutoSave, $tabNum;

        $docRoot = $fileDetails['docRoot'];
        $fileLoc = $fileDetails['fileLoc'];
        $fileURL = $fileDetails['fileURL'];
        $fileName = $fileDetails['fileName'];
        $fileMDTURLPart = $fileDetails['fileMDTURLPart'];
        $fileVersionURLPart = $fileDetails['fileVersionURLPart'];

        $doNext = '
			ICEcoder.serverMessage();
			fileLoc = "' . $fileLoc . '";
			overwriteOK = false;
			noConflictSave = false;
			newFileName = ICEcoder.getInput("' . $t['Enter filename to...'] . ' " + (fileLoc!="" ? fileLoc : "/"), "");
			if (newFileName) {
				if ("/" !== newFileName.substr(0,1)) {newFileName = "/" + newFileName};
				newFileName = fileLoc + newFileName;

				/* Check if file/dir exists */
				ICEcoder.lastFileDirCheckStatusObj = false;
				ICEcoder.checkExists(newFileName);
				var thisInt = setInterval(function() {
					if (false != ICEcoder.lastFileDirCheckStatusObj) {
						clearInterval(thisInt);

						if (ICEcoder.lastFileDirCheckStatusObj.file && ICEcoder.lastFileDirCheckStatusObj.file.exists) {
							overwriteOK = ICEcoder.ask("' . $t['That file exists...'] . '");
						} else {
							noConflictSave = true;
						};

						/* Saving under conditions: Confirmation of overwrite or there is no filename conflict, it is a new file, in either case we can save */
						if (overwriteOK || noConflictSave) {
							newFileName = "' . $docRoot . '" + newFileName;
							saveURL = "lib/file-control.php?action=save' . $fileMDTURLPart . $fileVersionURLPart . '&csrf=' . $_GET["csrf"] . '";

							var xhr = ICEcoder.xhrObj();

							xhr.onreadystatechange=function() {
								if (4 === xhr.readyState && 200 === xhr.status) {
									/* console.log(xhr.responseText); */
									var statusObj = JSON.parse(xhr.responseText);
									/* Set the actions end time and time taken in JSON object */
									statusObj.action.timeEnd = new Date().getTime();
									statusObj.action.timeTaken = statusObj.action.timeEnd - statusObj.action.timeStart;
									/* console.log(statusObj); */

									if (statusObj.status.error) {
										ICEcoder.message(statusObj.status.errorMsg);
									} else {
										eval(statusObj.action.doNext);
									}
								}
							};

							/* console.log(\'Calling \' + saveURL + \' via XHR\'); */
							xhr.open("POST",saveURL,true);
							xhr.setRequestHeader(\'Content-type\', \'application/x-www-form-urlencoded\');
							xhr.send(\'timeStart=' . numClean($_POST["timeStart"]) . '&file=' . $fileURL . '&newFileName=\' + newFileName.replace(/\\\+/g, "%2B") + \'&contents=\' + encodeURIComponent(ICEcoder.saveAsContent));
							ICEcoder.serverMessage("<b>' . $t['Saving'] . '</b> " + "'.("Save" === $finalAction ? "newFileName" : "'" . $fileName . "'") . '.replace(/^\/|/g, \'\')");
						}
					}
				}, 10);' .
            ($newFileAutoSave
                ? '} else {ICEcoder.closeTab(' . ($tabNum ?? 'ICEcoder.selectedTab') . ', "dontSetPV", "dontAsk");'
                : ''
            ) .
			'};

			/* UI dialog cancelling and saving contents for save as looparound */
			if (!newFileName || newFileName && !overwriteOK) {
				ICEcoder.saveAsContent = document.getElementById(\'saveTemp1\').value;
				ICEcoder.serverMessage();ICEcoder.serverQueue("del");
			}
		';

        return $doNext;
    }

    public function writeFile() {
        global $file, $t, $ICEcoder, $serverType, $doNext, $contents, $systemClass, $tabNum;
        if (isset($_POST['changes'])) {
            // Get existing file contents as lines and stitch changes onto it
            $fileLines = file($file);
            $contents = $this->systemClass->stitchChanges($fileLines, $_POST['changes']);

            // Get old file contents, and count stats on usage \n and \r\n
            // We use this info shortly to standardise the file to the same line endings
            // throughout, whichever is greater
            $oldContents = file_exists($file) ? getData($file) : '';
            $unixNewLines = preg_match_all('/[^\r][\n]/u', $oldContents);
            $windowsNewLines = preg_match_all('/[\r][\n]/u', $oldContents);
        } else {
            $contents = $_POST['contents'];
        }

        // Newly created files have the perms set too
        $setPerms = (!file_exists($file)) ? true : false;
        $systemClass->invalidateOPCache($file);
        $fh = fopen($file, 'w') or die($t['Sorry, cannot save']);

        // Replace \r\n (Windows), \r (old Mac) and \n (Linux) line endings with whatever we chose to be lineEnding
        $contents = str_replace("\r\n", $ICEcoder["lineEnding"], $contents);
        $contents = str_replace("\r", $ICEcoder["lineEnding"], $contents);
        $contents = str_replace("\n", $ICEcoder["lineEnding"], $contents);
        // Finally, replace the line endings with whatever what greatest in the file before
        // (We do this to help avoid a huge number of unnecessary changes simply on line endings)
        if (isset($_POST['changes']) && (0 < $unixNewLines || 0 < $windowsNewLines)) {
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
            chmod($file, octdec((string) $ICEcoder['newFilePerms']));
        }
        clearstatcache();
        $filemtime = "Windows" !== $serverType ? filemtime($file) : "1000000";
        $doNext .= 'ICEcoder.openFileMDTs[' . ($tabNum ?? 'ICEcoder.selectedTab') .' - 1] = "' . $filemtime . '";';
        $doNext .= '(function() {var x = ICEcoder.openFileVersions; var y = ' . ($tabNum ?? 'ICEcoder.selectedTab') .' - 1; x[y] = "undefined" != typeof x[y] ? x[y] + 1 : 1})(); ICEcoder.updateVersionsDisplay();';
    }

    /**
     * @param $filePath
     */
    public function download($filePath)
    {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header('Content-Description: File Transfer');
        header("Content-Type: application/octet-stream");
        header('Content-Disposition: attachment; filename=' . basename($filePath));
        // header("Content-Transfer-Encoding: binary");
        header('Content-Length: ' . filesize($filePath));
        ob_clean();
        flush();
        readfile($filePath);
    }

    public function delete() {
        global $filesArray, $docRoot, $iceRoot, $doNext, $t, $demoMode, $ICEcoder, $finalAction;

        for ($i = 0;$i < count($filesArray); $i++) {
            $fullPath = str_replace($docRoot, "", $filesArray[$i]);
            $fullPath = str_replace($iceRoot, "", $fullPath);
            $fullPath = $docRoot . $iceRoot . $fullPath;

            if (rtrim($fullPath, "/") === rtrim($docRoot, "/")) {
                $doNext .= "ICEcoder.message('" . $t['Sorry, cannot delete...'] . "');";
            } else if (!$demoMode && is_writable($fullPath)) {
                $fileOrFolder = is_dir($fullPath) ? "folder" : "file";
                if (is_dir($fullPath)) {
                    $actionedOK = $this->rrmdir($fullPath);
                } else {
                    // Delete file to tmp dir or full delete
                    $actionedOK = $ICEcoder['deleteToTmp']
                        ? rename($fullPath, str_replace("\\", "/", dirname(__FILE__)) . "/../tmp/." . str_replace(":", "_", str_replace("/", "_", $fullPath)))
                        : unlink($fullPath);
                }
                if (true === $actionedOK) {
                    $fileName = basename($fullPath);
                    $fileLoc = dirname(str_replace($docRoot, "", $fullPath));
                    if ($fileLoc=="" || "\\" === $fileLoc) {
                        $fileLoc="/";
                    };

                    // Reload file manager
                    $doNext .= 'ICEcoder.selectedFiles = []; ICEcoder.updateFileManagerList(\'delete\', \'' . $fileLoc . '\', \'' . $fileName . '\', false, false, false, \'' . $fileOrFolder . '\');';
                    $finalAction = "delete";

                    // Run any extra processes
                    $extraProcessesClass = new ExtraProcesses($fileLoc, $fileName);
                    $doNext = $extraProcessesClass->onFileDirDelete($doNext);
                } else {
                    $doNext .= "ICEcoder.message('" . $t['Sorry, cannot delete'] . "\\\\n" . str_replace($docRoot, "", $fullPath) . "');";
                    $finalAction = "nothing";
                }
            } else {
                $doNext .= "ICEcoder.message('" . $t['Sorry, cannot delete'] . "\\\\n" . str_replace($docRoot, "", $fullPath) . "');";
                $finalAction = "nothing";
            }
        }
    }

    /**
     * @param $dir
     * @return bool
     */
    public function rrmdir($dir): bool {
        global $ICEcoder;

        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ("." !== $object && ".." !== $object) {
                    if ("dir" === filetype($dir . "/" . $object)) {
                        $this->rrmdir($dir . "/" . $object);
                    } else {
                        $ICEcoder['deleteToTmp']
                            ? rename($dir . "/" . $object, str_replace("\\", "/", dirname(__FILE__)) . "/../tmp/." . str_replace(":", "_", str_replace("/", "_", $dir)) . "_" . $object)
                            : unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            // Remove now empty dir
            if (false === rmdir($dir)) {
                return false;
            }
            return true;
        }
    }

    public function paste() {
        global $source, $dest, $ICEcoder;

        if (is_dir($source)) {
            $fileOrFolder = "folder";
            if (!is_dir($dest)) {
                mkdir($dest, octdec((string) $ICEcoder['newDirPerms']));
            } else {
                for ($i = 2; $i < 1000000000; $i++) {
                    if (!is_dir($dest . " (" . $i . ")")) {
                        $dest = $dest." (" . $i . ")";
                        mkdir($dest, octdec((string) $ICEcoder['newDirPerms']));
                        $i = 1000000000;
                    }
                }
            }
            foreach ($iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST) as $item
            ) {
                if ($item->isDir()) {
                    mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName(), octdec((string) $ICEcoder['newDirPerms']));
                } else {
                    copy($item->getPathName(), $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
                }
            }
        } else {
            $fileOrFolder = "file";
            if (!file_exists($dest)) {
                copy($source, $dest);
            } else {
                for ($i = 2; $i < 1000000000; $i++) {
                    if (!file_exists($dest . " (" . $i . ")")) {
                        $dest = $dest . " (" . $i . ")";
                        copy($source, $dest);
                        $i = 1000000000;
                    }
                }
            }
        }

        return $fileOrFolder;
    }

    public function loadAndShowDiff() {
        global $file, $fileLoc, $fileName, $doNext, $filemtime, $finalAction, $t;
        // Only applicable for local files
        $loadedFile = toUTF8noBOM(getData($file), true);
        $fileCountInfo = getVersionsCount($fileLoc, $fileName);
        $doNext .= '
				var loadedFile = document.createElement("textarea");
				loadedFile.value = "' . str_replace('"', '\\\"', str_replace("\r", "\\\\r", str_replace("\n", "\\\\n", str_replace("</textarea>", "<ICEcoder:/:textarea>", $loadedFile)))).'";
				var refreshFile = ICEcoder.ask("' . $t['Sorry, this file...'] . '\\\n' . $file . '\\\n\\\n' . $t['Reload this file...'] . '");
				if (refreshFile) {
					var cM = ICEcoder.getcMInstance();
					var thisTab = ICEcoder.selectedTab;
					var userVersionFile = cM.getValue();
					/* Revert back to original */
					cM.setValue(loadedFile.value);
					ICEcoder.savedPoints[thisTab - 1] = cM.changeGeneration();
					ICEcoder.savedContents[thisTab - 1] = cM.getValue();
					ICEcoder.openFileMDTs[ICEcoder.selectedTab - 1] = "' . $filemtime . '";
					ICEcoder.openFileVersions[ICEcoder.selectedTab - 1] = "' . $fileCountInfo['count'] . '";
					cM.clearHistory();
					/* Now for the new version in the diff pane */
					ICEcoder.setSplitPane(\'on\');
					var cMdiff = ICEcoder.getcMdiffInstance();
					cMdiff.setValue(userVersionFile);
				};';
        $finalAction = "nothing";
    }

    public function upload($uploads) {
        global $docRoot, $iceRoot, $ICEcoder, $doNext, $t;

        $uploadDir = $docRoot . $iceRoot . str_replace("..", "", str_replace("|", "/", $_POST['folder'] . "/"));
        foreach($uploads as $current) {
            $uploadedFile = $uploadDir . $current['name'];
            $fileName = $current['name'];
            // Get & set existing perms for existing files, or set to newFilePerms setting for new files
            if (file_exists($uploadedFile)) {
                $chmodInfo = substr(sprintf('%o', fileperms($uploadedFile)), -4);
                $setPerms = substr($chmodInfo, 1, 3); // reduces 0755 down to 755
            } else {
                $setPerms = $ICEcoder['newFilePerms'];
            }
            if ($this->uploadThisFile($current, $uploadedFile, $setPerms)) {
                $doNext .= 'parent.parent.ICEcoder.updateFileManagerList(\'add\', parent.parent.ICEcoder.selectedFiles[parent.parent.ICEcoder.selectedFiles.length - 1].replace(/\|/g, \'/\'), \'' . str_replace("'", "\'", $fileName) . '\', false, false, true, \'file\'); parent.parent.ICEcoder.serverMessage("' . $t['Uploaded file(s) OK'] . '");setTimeout(function(){parent.parent.ICEcoder.serverMessage();}, 2000);';
                $finalAction = "upload";
            } else {
                $doNext .= "parent.parent.ICEcoder.message('" . $t['Sorry, cannot upload'] . " \\\\n" . $fileName . "\\\\n " . $t['into'] . " \\\\n' + parent.parent.ICEcoder.selectedFiles[parent.parent.ICEcoder.selectedFiles.length - 1].replace(/\|/g, '/'));";
                $finalAction = "nothing";
            }
        }

        return $finalAction;
    }

    private function uploadThisFile($current, $uploadFile, $setPerms){
        if (move_uploaded_file($current['tmp_name'], $uploadFile)){
            chmod($uploadFile, octdec((string) $setPerms));
            return true;
        }
    }


    public function getUploadedDetails($fileArr) {
        $uploads = [];
        foreach($fileArr['name'] as $keyee => $info) {
            $uploads[$keyee] = [];
            $uploads[$keyee]['name'] = xssClean($fileArr['name'][$keyee], "html");
            $uploads[$keyee]['type'] = $fileArr['type'][$keyee];
            $uploads[$keyee]['tmp_name'] = $fileArr['tmp_name'][$keyee];
            $uploads[$keyee]['error'] = $fileArr['error'][$keyee];
        }
        return $uploads;
    }

    public function handleMarkdown() {
        // Reload previewWindow window if not a Markdown file
        // In doing this, we check on an interval for the page to be complete and if we last saw it loading
        // When we are done loading, so set the loading status to false and load plugins on..
        $doNext = 'if (ICEcoder.previewWindow.location && ICEcoder.previewWindow.location.pathname && -1 === ICEcoder.previewWindow.location.pathname.indexOf(".md")) {
					ICEcoder.previewWindowLoading = false;
					ICEcoder.previewWindow.location.reload(true);

					ICEcoder.checkPreviewWindowLoadingInt = setInterval(function() {
						if ("loading" !== ICEcoder.previewWindow.document.readyState && ICEcoder.previewWindowLoading) {
							ICEcoder.previewWindowLoading = false;
							try {ICEcoder.doPesticide();} catch(err) {};
							try {ICEcoder.doStatsJS(\'save\');} catch(err) {};
							try {ICEcoder.doResponsive();} catch(err) {};
							clearInterval(ICEcoder.checkPreviewWindowLoadingInt);
						} else {
							ICEcoder.previewWindowLoading = "loading" === ICEcoder.previewWindow.document.readyState ? true : false;
						}
					}, 4);

				};';

        return $doNext;
    }

    public function handleDiffPane() {
        global $tabNum;
        // Copy over content to diff pane if we have that setting on
        $doNext = '
					cM = ICEcoder.getcMInstance('. ($tabNum ?? 'ICEcoder.selectedTab') .');
					cMdiff = ICEcoder.getcMdiffInstance('. ($tabNum ?? 'ICEcoder.selectedTab') .');
					if (ICEcoder.updateDiffOnSave) {
						cMdiff.setValue(cM.getValue());
					};
				';

        return $doNext;
    }

    public function finaliseSave() {
        global $tabNum;

        // Finally, set previous files, indicate changes, set saved points and redo tabs
        $doNext = '
						ICEcoder.setPreviousFiles();
						setTimeout(function(){ICEcoder.indicateChanges()}, 4);
						ICEcoder.savedPoints[' . ($tabNum ?? 'ICEcoder.selectedTab') .' - 1] = cM.changeGeneration();
						ICEcoder.savedContents[' . ($tabNum ?? 'ICEcoder.selectedTab') .' - 1] = cM.getValue();
						ICEcoder.redoTabHighlight(' . ($tabNum ?? 'ICEcoder.selectedTab') .');
						ICEcoder.switchTab(ICEcoder.selectedTab);';

        return $doNext;
    }

    public function compileSass() {
        global $docRoot, $fileLoc, $fileName, $systemClass;

        $doNext = "";

        // Compiling Sass files (.scss to .css, with same name, in same dir)
        $filePieces = explode(".", $fileName);
        $fileExt = $filePieces[count($filePieces) - 1];

        // SCSS Compiling if we have SCSSPHP plugin installed
        if (strtolower($fileExt) == "scss" && file_exists(dirname(__FILE__) . "/../plugins/scssphp/scss.inc.php")) {
            // Load the SCSSPHP lib and start a new instance
            require dirname(__FILE__) . "/../plugins/scssphp/scss.inc.php";
            $scss = new scssc();

            // Set the import path and formatting type
            $scss->setImportPaths($docRoot . $fileLoc . "/");
            $scss->setFormatter('scss_formatter_compressed'); // scss_formatter, scss_formatter_nested, scss_formatter_compressed

            if (true === is_writable($docRoot . $fileLoc)) {
                $scssContent = $scss->compile('@import "' . $fileName . '"');
                $systemClass->invalidateOPCache($docRoot . $fileLoc . "/" . substr($fileName, 0, -4) . "css");
                $fh = fopen($docRoot . $fileLoc . "/" . substr($fileName, 0, -4) . "css", 'w');
                fwrite($fh, $scssContent);
                fclose($fh);
            } else {
                $doNext .= ";ICEcoder.message('Could not compile your Sass, dir not writable.');";
            }
        }

        return $doNext;
    }

    public function compileLess() {
        global $docRoot, $fileLoc, $fileName;

        $doNext = "";

        // Compiling LESS files (.less to .css, with same name, in same dir)
        $filePieces = explode(".", $fileName);
        $fileExt = $filePieces[count($filePieces) - 1];

        // Less Compiling if we have LESSPHP plugin installed
        if (strtolower($fileExt) == "less" && file_exists(dirname(__FILE__) . "/../plugins/lessphp/lessc.inc.php")) {
            // Load the LESSPHP lib and start a new instance
            require dirname(__FILE__) . "/../plugins/lessphp/lessc.inc.php";
            $less = new lessc();

            // Set the formatting type and if we want to preserve comments
            $less->setFormatter('lessjs'); // lessjs (same style used in LESS for JS), compressed (no whitespace) or classic (LESSPHP's original formatting)
            $less->setPreserveComments(false); // true or false

            if (true === is_writable($docRoot . $fileLoc)) {
                $less->checkedCompile($docRoot . $fileLoc . "/" . $fileName, $docRoot . $fileLoc . "/" . substr($fileName, 0, -4) . "css"); // Note: Only recompiles if changed
            } else {
                $doNext .= ";ICEcoder.message('Could not compile your LESS, dir not writable.');";
            }
        }

        return $doNext;
    }

    public function returnJSON() {
        global $fileLoc, $fileName, $file, $filemtime, $finalAction, $timeStart, $error, $errorStr, $errorMsg, $doNext;

        $itemAbsPath = $file;
        $itemPath = dirname($file);
        $itemBytes = is_dir($file) || !file_exists($file) ? null : filesize($file);
        $itemType = (file_exists($file) ? (is_dir($file) ? "dir" : "file") : "unknown");
        $itemExists = (file_exists($file) ? "true" : "false");

        return '{
            "file": {
                "absPath": "' . $itemAbsPath . '",
                "relPath": "' . $fileLoc . '/' . $fileName . '",
                "name":	"' . $fileName . '",
                "path": "' . $itemPath . '",
                "bytes": "' . $itemBytes . '",
                "modifiedDT": "' . $filemtime . '",
                "type": "' . $itemType . '",
                "exists": ' . $itemExists . '
            },
            "action": {
                "initial" : "' . xssClean($_GET['action'], "html") . '",
                "final" : "' . $finalAction . '",
                "timeStart": ' . $timeStart . ',
                "timeEnd": 0,
                "timeTaken": 0,
                "csrf": "' . xssClean($_GET['csrf'], "html") . '",
                "doNext" : "' . preg_replace('/\r|\n/', '', str_replace('	', '', str_replace('"', '\"', $doNext))) . 'ICEcoder.switchMode();"
            },
            "status": {
                "error" : ' . ($error ? 'true' : 'false') . ',
                "errorStr" : "' . $errorStr . '",
                "errorMsg" : "' . $errorMsg . '"
            }
        }';
    }
}
