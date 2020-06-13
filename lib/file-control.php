<?php
require "icecoder.php";

use ICEcoder\ExtraProcesses;
use ICEcoder\Backup;
use ICEcoder\File;
use ICEcoder\FTP;
use ICEcoder\System;
use ICEcoder\URL;

$backupClass = new Backup;
$fileClass = new File;
$ftpClass = new FTP;
$system = new System;

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

// Is this a save as?
$saveAs = isset($_GET['saveType'])
    ? ("saveAs" === $_GET['saveType'])
    : false;

// Is this an autosave while creating a new file?
$newFileAutoSave = isset($_GET['newFileAutoSave'])
    ? ("true" === $_GET['newFileAutoSave'])
    : false;

// Establish the filename/new filename
// New file
if (isset($_POST['newFileName']) && "" != $_POST['newFileName']) {
    $file = $_POST['newFileName'];
// Existing file
} elseif (isset($_REQUEST['file'])) {
    $file = $_REQUEST['file'];
// Error
} else {
    $file = "";
    $finalAction = "nothing";
    $doNext = "";
    $error = true;
    $errorStr = "true";
    $errorMsg = $t['Sorry, bad filename...'];
};

// If we have file(s) to work with...
if (false === $error) {
    $fileClass->check();
    if ("load" === $_GET['action'] && true === $error) {
        die("parent.parent.ICEcoder.message('" . $errorMsg . "');parent.parent.ICEcoder.serverMessage();parent.parent.ICEcoder.serverQueue(\"del\",0);</script>");
    }
}

$doNext = "";
// If we're in FTP mode, start a connection and leave open for FTP actions
if (isset($ftpSite)) {
    $ftpClass->ftpStart();
    // Show user warning if no good connection
    if (!$ftpConn || !$ftpLogin) {
        $doNext .= 'ICEcoder.message("Sorry, no FTP connection to ' . $ftpHost . ' for user ' . $ftpUser . '");';
    }
}

// =============
// LOADING FILES
// =============

// If we're due to open a file...
if (!$error && "load" === $_GET['action']) {
    echo '<script>';
    $fileClass->load();
    echo $fileClass->returnLoadTextScript();
    echo $fileClass->returnLoadImageScript();
    ?>
    parent.parent.ICEcoder.serverMessage(); parent.parent.ICEcoder.serverQueue("del", 0);

    // Finally, switch mode in case we have saved, renamed file etc
    parent.parent.ICEcoder.switchMode();
    </script>
    <?php
    exit;
}

// ============
// SAVING FILES
// ============

if (!$error && "save" === $_GET['action']) {

    // ====================================
    // NEW FILES AND SAVE AS XHR LOOPAROUND
    // ====================================

    if (0 < strpos($fileOrig, "[NEW]") || true === $saveAs) {
        $finalAction = 0 < strpos($fileOrig, "[NEW]") ? "save as" : "save";
        $fileURL = isset($file) ? $file : "";
        $fileMDTURLPart = isset($_GET["fileMDT"]) && "undefined" !== $_GET["fileMDT"] ? "&fileMDT=" . numClean($_GET['fileMDT']) : "";
        $fileVersionURLPart = isset($_GET["fileVersion"]) && "undefined" !== $_GET["fileVersion"] ? "&fileVersion=" . numClean($_GET['fileVersion']) : "";
        $fileDetails = [
            "docRoot" => $docRoot,
            "fileLoc" => $fileLoc,
            "fileURL" => $fileURL,
            "fileName" => $fileName,
            "fileMDTURLPart" => $fileMDTURLPart,
            "fileVersionURLPart" => $fileVersionURLPart,
            "ftpSite" => true === isset($ftpSite)
        ];
        $doNext .= $fileClass->handleSaveLooparound($fileDetails, $finalAction, $t);

        // ===================
        // FILE CONTENT SAVING
        // ===================

    } elseif (isset($_POST['changes']) || isset($_POST['contents'])) {
        $finalAction = isset($_POST["newFileName"]) ? "save as" : "save";

        // =================
        // FILE IS WRITEABLE
        // =================

        if (!$demoMode && (isset($ftpSite) || (file_exists($file) && is_writable($file)) || isset($_POST['newFileName']) && "" != $_POST['newFileName'])) {

            $filemtime = !isset($ftpSite) && "Linux" === $serverType ? filemtime($file) : "1000000";

            // =======================
            // MDT'S MATCH, WRITE FILE
            // =======================

            if (!(isset($_GET['fileMDT'])) || $filemtime == $_GET['fileMDT']) {

                // FTP Saving
                if (isset($ftpSite)) {
                    $ftpClass->writeFile();
                    // Local saving
                } else {
                    $fileClass->writeFile();
                }

                // Save a version controlled backup source of the file
                if ($ICEcoder["backupsKept"]) {
                    $backupClass->makeBackup($fileLoc, $fileName, $contents);
                }

                $doNext .= $fileClass->updateUI();
                $doNext .= $fileClass->handleMarkdown();
                $doNext .= $fileClass->handleDiffPane();
                $doNext .= $fileClass->finaliseSave();
                $doNext .= $fileClass->compileSass();
                $doNext .= $fileClass->compileLess();

                // Run any extra processes
                $extraProcesses = new ExtraProcesses($fileLoc, $fileName);
                $doNext = $extraProcesses->onFileSave($doNext);

                // ======================================================
                // MDT'S DON'T MATCH, OFFER TO LOAD FILE & SHOW DIFF VIEW
                // ======================================================

            } else {
                $fileClass->loadAndShowDiff();
            }

            // ===================
            // FILE IS UNWRITEABLE
            // ===================

        } else {
            $finalAction = "nothing";
            $doNext .= "ICEcoder.message('" . $t['Sorry, cannot save'] . "\\\\n" . $file . "');";
        }
        $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del", 0);';
    }
};

// ==========
// NEW FOLDER
// ==========

if (!$error && "newFolder" === $_GET['action']) {
    if (!$demoMode && (isset($ftpSite) || is_writable($docRoot.$fileLoc))) {
        // FTP
        if (isset($ftpSite)) {
            $ftpFilepath = ltrim($fileLoc . "/" . $fileName, "/");
            if (!$ftpClass->ftpMkDir($ftpConn, octdec($ICEcoder['newDirPerms']), $ftpFilepath)) {
                $doNext .= 'ICEcoder.message("Sorry, could not create dir '.$ftpFilepath.' at ' . $ftpHost . '");';
            } else {
                $fileClass->updateFileManager('add', $fileLoc, $fileName, '', '', '', 'folder');
            }
            // Local
        } else {
            mkdir($file, octdec($ICEcoder['newDirPerms']));
            $fileClass->updateFileManager('add', $fileLoc, $fileName, '', '', '', 'folder');
        }
        $finalAction = "newFolder";
        // Run any extra processes
        $extraProcesses = new ExtraProcesses($fileLoc, $fileName);
        $doNext = $extraProcesses->onDirNew($doNext);
    } else {
        $doNext .= "ICEcoder.message('" . $t['Sorry, cannot create...'] . "\\\\n" . $fileLoc . "');";
        $finalAction = "nothing";
    }
    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del", 0);';
};

// ================
// MOVE FILE/FOLDER
// ================

if (!$error && "move" === $_GET['action']) {
    if (isset($ftpSite)) {
        $srcDir = ltrim(str_replace("|", "/", $_GET['oldFileName']), "/");
        $tgtDir = ltrim($fileLoc . "/" . $fileName, "/");
    } else {
        $srcDir = $docRoot . $iceRoot . str_replace("|", "/", $_GET['oldFileName']);
        $tgtDir = $docRoot . $fileLoc . "/" . $fileName;
    }
    if ($srcDir != $tgtDir && $fileLoc != "") {
        if (!$demoMode && (isset($ftpSite) || is_writable($srcDir))) {
            // FTP
            if (isset($ftpSite)) {
                if (!$ftpClass->ftpRename($ftpConn, $srcDir, $tgtDir)) {
                    $doNext .= 'ICEcoder.message("Sorry, could not rename ' . $srcDir . ' to ' . $tgtDir . '");';
                } else {
                    $ftpFileDirInfo = $ftpClass->ftpGetFileInfo($ftpConn, ltrim($fileLoc, "/"), $fileName);
                    $fileOrFolder = "directory" === $ftpFileDirInfo['type'] ? "folder" : "file";
                    $fileClass->updateFileManager('move', $fileLoc, $fileName, '', str_replace($iceRoot, "", str_replace("|", "/", $_GET['oldFileName'])), '', $fileOrFolder);
                }
                // Local
            } else {
                if(rename($srcDir, $tgtDir)) {
                    // Is a dir or file (needed to create new item in file manager)
                    $fileOrFolder = is_dir($docRoot . $fileLoc . "/" . $fileName) ? "folder" : "file";
                    $fileClass->updateFileManager('move', $fileLoc, $fileName, '', str_replace($iceRoot, "", str_replace("|", "/", $_GET['oldFileName'])), '', $fileOrFolder);
                }
            }
            $finalAction = "move";
            // Run any extra processes
            $extraProcesses = new ExtraProcesses($fileLoc, $fileName);
            $doNext = $extraProcesses->onFileDirMove($doNext);
        } else {
            $doNext .= "ICEcoder.message('" . $t['Sorry, cannot move'] . "\\\\n" . str_replace("|", "/", $_GET['oldFileName']) . "\\\\n\\\\n" . $t['Maybe public write...'] . "');";
            $finalAction = "nothing";
        }
    } else {
        $doNext .= "";
        $finalAction = "nothing";
    }
    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del", 0);';
};

// ==================
// RENAME FILE/FOLDER
// ==================

if (!$error && "rename" === $_GET['action']) {
    if (!$demoMode && (isset($ftpSite) || is_writable($docRoot.$iceRoot.str_replace("|", "/", $_GET['oldFileName'])))) {
        // FTP
        if (isset($ftpSite)) {
            $ftpFilepath = ltrim($fileLoc . "/" . $fileName, "/");
            if (!$ftpClass->ftpRename($ftpConn, ltrim($_GET['oldFileName'], "/"), $ftpFilepath)) {
                $doNext .= 'ICEcoder.message("Sorry, could not rename ' . ltrim($_GET['oldFileName'], "/") . ' to ' . $ftpFilepath . '");';
            } else {
                $fileClass->updateFileManager('rename', $fileLoc, $fileName, '', str_replace($iceRoot, "", $_GET['oldFileName']), '', '');
            }
            // Local
        } else {
            rename($docRoot.$iceRoot.str_replace("|", "/", $_GET['oldFileName']), $docRoot . $fileLoc . "/" . $fileName);
            $fileClass->updateFileManager('rename', $fileLoc, $fileName, '', str_replace($iceRoot, "", $_GET['oldFileName']), '', '');
        }

        $finalAction = "rename";
        // Run any extra processes
        $extraProcesses = new ExtraProcesses($fileLoc, $fileName);
        $doNext = $extraProcesses->onFileDirRename($doNext);
    } else {
        $doNext .= "ICEcoder.message('".$t['Sorry, cannot rename'] . "\\\\n" . $_GET['oldFileName'] . "\\\\n\\\\n" . $t['Maybe public write...'] . "');";
        $finalAction = "nothing";
    }
    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del", 0);';
};

// =================
// PASTE FILE/FOLDER
// =================

if (!isset($ftpSite) && !$error && "paste" === $_GET['action']) {
    $source = $file;
    $dest = str_replace("//", "/", $docRoot . $iceRoot . str_replace("|", "/", $_GET['location']) . "/" . basename($source));
    if (!$demoMode && is_writable(dirname($dest))) {

        $fileOrFolder = $fileClass->paste();

        // Reload file manager
        $doNext .= 'ICEcoder.updateFileManagerList(\'add\', \'' . str_replace("|", "/", $_GET['location']) . '\', \'' . basename($dest) . '\', false, false, false, \'' . $fileOrFolder . '\');';
        $finalAction = "pasteFile";
        // Run any extra processes
        $extraProcesses = new ExtraProcesses($fileLoc, $fileName);
        $doNext = $extraProcesses->onFileDirPaste($doNext, $dest);
    } else {
        $doNext .= "ICEcoder.message('" . $t['Sorry, cannot copy'] . " \\\\n" . str_replace($docRoot, "", $source) . "\\\\n " . $t['into'] . " \\\\n" . str_replace($docRoot, "", $dest) . "');";
        $finalAction = "nothing";
    }
    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del", 0);';
};

// ==============
// UPLOAD FILE(S)
// ==============

if (!isset($ftpSite) && !$error && "upload" === $_GET['action']) {
    if (!$demoMode) {

        if ($_FILES['filesInput']){
            $uploads = $fileClass->getUploadedDetails($_FILES['filesInput']);
            $finalAction = $fileClass->upload($uploads);
        }
        // Run any extra processes
        $extraProcesses = new ExtraProcesses($fileLoc, $fileName);
        $doNext = $extraProcesses->onFileUpload($doNext, $uploads);
    } else {
        $doNext .= "parent.parent.ICEcoder.message('" . $t['Sorry, cannot upload...'] . "');";
        $finalAction = "nothing";
    }

    $doNext .= "parent.parent.ICEcoder.hideFileMenu(); parent.parent.document.getElementById('fileInput').value = ''; parent.parent.ICEcoder.showHide('hide', parent.parent.document.getElementById('loadingMask'));";

    // Upload is not handled by XHR methods, but form post, so we need to manually trigger $doNext in a script tag
    echo "<script>".$doNext."</script>";
};

// ========================
// DELETE FILE(S)/FOLDER(S)
// ========================

if (!$error && "delete" === $_GET['action']) {
    $filesArray = explode(";", $file); // May contain more than one file here
    // FTP
    if (isset($ftpSite)) {
        if (1 === count($filesArray)) {
            $ftpFileDirInfo = $ftpClass->ftpGetFileInfo($ftpConn, ltrim($fileLoc, "/"), $fileName);
            $itemType = "directory" === $ftpFileDirInfo['type'] ? "dir" : "file";
            $itemPath = ltrim($fileLoc . "/" . $fileName, "/");
            if (!$demoMode && $ftpClass->ftpDelete($ftpConn, $itemType, $itemPath)) {
                if ($fileLoc=="" || "\\" === $fileLoc) {
                    $fileLoc = "/";
                };
                // Reload file manager
                $doNext .= 'ICEcoder.selectedFiles = []; ICEcoder.updateFileManagerList(\'delete\', \'' . $fileLoc . '\', \'' . $fileName . '\');';
                $finalAction = "delete";
                // Run any extra processes
                $extraProcesses = new ExtraProcesses($fileLoc, $fileName);
                $doNext = $extraProcesses->onFileDirDelete($doNext);
            } else {
                $doNext .= "ICEcoder.message('" . $t['Sorry, cannot delete'] . "\\\\n" . $fileLoc . "/" . $fileName . "');";
                $finalAction = "nothing";
            }
        } else {
            $doNext .= "ICEcoder.message('" . $t['Sorry, cannot delete more...'] . "');";
            $finalAction = "nothing";
        }
        // Local
    } else {
        $fileClass->delete();
    }
    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del", 0);';
};

// ======================
// REPLACE TEXT IN A FILE
// ======================

if (!isset($ftpSite) && !$error && "replaceText" === $_GET['action']) {
    if (!$demoMode && is_writable($file)) {
        $loadedFile = toUTF8noBOM(getData($file), true);
        $newContent = str_replace($_GET['find'], $_GET['replace'], $loadedFile);
        $fh = fopen($file, 'w') or die($t['Sorry, cannot save']);
        fwrite($fh, $newContent);
        fclose($fh);
        $finalAction = "replaceText";

        // Run any extra processes
        $extraProcesses = new ExtraProcesses($fileLoc, $fileName);
        $doNext = $extraProcesses->onFileReplaceText($doNext, $_GET['fileRef']);

    } else {
        $doNext .= "ICEcoder.message('" . $t['Sorry, cannot replace...'] . "\\\\n" . $file . "');";
        $finalAction = "nothing";
    }

    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del", 0);';
};

// ==========================
// GET CONTENTS OF REMOTE URL
// ==========================

if (!isset($ftpSite) && !$error && "getRemoteFile" === $_GET['action']) {
    $lineNumber = max(isset($_REQUEST['lineNumber']) ? intval($_REQUEST['lineNumber']) : 1, 1);

    if ($remoteFile = toUTF8noBOM(getData($file, 'curl'), true)) {
        // Get URL contents
        $url = new URL($remoteFile);
        $doNext .= $url->load($ICEcoder["lineEnding"], $lineNumber);
        $finalAction = "getRemoteFile";

        // Run any extra processes
        $extraProcesses = new ExtraProcesses($fileLoc);
        $doNext = $extraProcesses->onGetRemoteFile($doNext);

    } else {
        $finalAction = "nothing";
        $doNext .= 'ICEcoder.message(\'' . $t['Sorry, could not...'] . ' '.$file . '\');';
    }

    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del", 0);';
};

// =======================
// CHANGING FILE/DIR PERMS
// =======================

if (!$error && "perms" === $_GET['action']) {
    if (!$demoMode && (isset($ftpSite) || is_writable($file))) {
        // FTP
        if (isset($ftpSite)) {
            $ftpFilepath = ltrim($fileLoc . "/" . $fileName, "/");
            if (!$ftpClass->ftpPerms($ftpConn, octdec(numClean($_GET['perms'])), $ftpFilepath)) {
                $doNext .= 'ICEcoder.message("Sorry, could not set perms on ' . $ftpFilepath . ' at ' . $ftpHost . '");';
            } else {
                $fileClass->updateFileManager('chmod', $fileLoc, $fileName, numClean($_GET['perms']), '', '', '');
            }
            // Local
        } else {
            chmod($file, octdec(numClean($_GET['perms'])));
            $fileClass->updateFileManager('chmod', $fileLoc, $fileName, numClean($_GET['perms']), '', '', '');
        }
        $finalAction = "perms";
        // Run any custom processes
        $extraProcesses = new ExtraProcesses($fileLoc, $fileName);
        $doNext = $extraProcesses->onFileDirPerms($doNext, $_GET['perms']);
    } else {
        $finalAction = "nothing";
        $doNext .= "ICEcoder.message('" . $t['Sorry, cannot change...'] . " \\n" . $file . "');";
    }
    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del", 0);';
};

// ====================
// CHECK FOR A FILE/DIR
// ====================

if (!isset($ftpSite) && !$error && "checkExists" === $_GET['action']) {
    // This action is called under seperate AJAX call and the responseText object stored in ICEcoder.lastFileDirCheckStatusObj
    // Nothing really done here though, we do something with the responseText
    $finalAction = "checkExists";
    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del", 0);';
};

// ===================
// JSON DATA TO RETURN
// ===================

// No $filemtime yet? Get it now!
if (false === isset($filemtime) && !is_dir($file)) {
    $filemtime = "Linux" === $serverType ? filemtime($file) : 1000000;
}
if (false === isset($filemtime)) {
    $filemtime = 1000000;
}

// Set $timeStart, use 0 if not available
$timeStart = isset($_POST["timeStart"]) ? numClean($_POST["timeStart"]) : 0;
if ($timeStart == "") {
    $timeStart = 0;
}

echo $fileClass->returnJSON();

// Set timestamp of last index to 0 to force a re-index next time we index
requireReIndexNextTime();
