<?php
require "icecoder.php";

use ICEcoder\ExtraProcesses;
use ICEcoder\Backup;
use ICEcoder\File;
use ICEcoder\System;
use ICEcoder\URL;

$backupClass = new Backup;
$fileClass = new File;
$systemClass = new System;

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

// Is this an autosave while creating a new file?
if (true === isset($_GET['tabNum'])) {
    $tabNum = $_GET['tabNum'];
}

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
        die("parent.parent.ICEcoder.message('" . $errorMsg . "');parent.parent.ICEcoder.serverMessage();parent.parent.ICEcoder.serverQueue(\"del\");</script>");
    }
}

$doNext = "";

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
    parent.parent.ICEcoder.serverMessage(); parent.parent.ICEcoder.serverQueue("del");

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
            "fileVersionURLPart" => $fileVersionURLPart
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

        if (!$demoMode && ((file_exists($file) && is_writable($file)) || isset($_POST['newFileName']) && "" != $_POST['newFileName'])) {

            $filemtime = "Windows" !== $serverType && file_exists($file) ? filemtime($file) : "1000000";

            // ==================================================
            // MDT'S MATCH (OR WE'RE SAVING NEW FILE), WRITE FILE
            // ==================================================
            if (!(isset($_GET['fileMDT'])) || $filemtime == $_GET['fileMDT'] || isset($_POST['newFileName'])) {

                // Write file
                $fileClass->writeFile();

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
                $extraProcessesClass = new ExtraProcesses($fileLoc, $fileName);
                $doNext = $extraProcessesClass->onFileSave($doNext);

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
        $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del");';
    }
};

// ==========
// NEW FOLDER
// ==========

if (!$error && "newFolder" === $_GET['action']) {
    if (!$demoMode && is_writable($docRoot . $fileLoc)) {
        mkdir($file, octdec((string) $ICEcoder['newDirPerms']));
        $fileClass->updateFileManager('add', $fileLoc, $fileName, '', '', '', 'folder');
        $finalAction = "newFolder";
        // Run any extra processes
        $extraProcessesClass = new ExtraProcesses($fileLoc, $fileName);
        $doNext = $extraProcessesClass->onDirNew($doNext);
    } else {
        $doNext .= "ICEcoder.message('" . $t['Sorry, cannot create...'] . "\\\\n" . $fileLoc . "');";
        $finalAction = "nothing";
    }
    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del");';
};

// ================
// MOVE FILE/FOLDER
// ================

if (!$error && "move" === $_GET['action']) {
    $srcDir = $docRoot . $iceRoot . str_replace("|", "/", $_GET['oldFileName']);
    $tgtDir = $docRoot . $fileLoc . "/" . $fileName;
    if ($srcDir != $tgtDir) {
        if (!$demoMode && is_writable($srcDir)) {
            if(rename($srcDir, $tgtDir)) {
                // Is a dir or file (needed to create new item in file manager)
                $fileOrFolder = is_dir($docRoot . $fileLoc . "/" . $fileName) ? "folder" : "file";
                $fileClass->updateFileManager('move', $fileLoc, $fileName, '', str_replace($iceRoot, "", str_replace("|", "/", $_GET['oldFileName'])), '', $fileOrFolder);
                $doNext .= 'tabNum = ICEcoder.openFiles.indexOf(\'' . str_replace("|", "/", $_GET['oldFileName']) . '\') + 1; if (0 < tabNum) {ICEcoder.renameTab(tabNum, \'' . $fileLoc . "/" . $fileName . '\');};';
                $finalAction = "move";
                // Run any extra processes
                $extraProcessesClass = new ExtraProcesses($fileLoc, $fileName);
                $doNext = $extraProcessesClass->onFileDirMove($doNext);
            } else {
                $doNext .= "ICEcoder.message('" . $t['Sorry, cannot move'] . "\\\\n" . str_replace("|", "/", $_GET['oldFileName']) . "\\\\n\\\\n" . $t['Maybe public write...'] . "');";
                $finalAction = "nothing";
            }
        } else {
            $doNext .= "ICEcoder.message('" . $t['Sorry, cannot move'] . "\\\\n" . str_replace("|", "/", $_GET['oldFileName']) . "\\\\n\\\\n" . $t['Maybe public write...'] . "');";
            $finalAction = "nothing";
        }
    } else {
        $doNext .= "";
        $finalAction = "nothing";
    }
    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del");';
};

// ==================
// RENAME FILE/FOLDER
// ==================

if (!$error && "rename" === $_GET['action']) {
    if (!$demoMode && is_writable($docRoot . $iceRoot . str_replace("|", "/", $_GET['oldFileName']))) {
        if (true === file_exists($docRoot . $fileLoc)) {
            rename($docRoot.$iceRoot.str_replace("|", "/", $_GET['oldFileName']), $docRoot . $fileLoc . "/" . $fileName);
            $fileClass->updateFileManager('rename', $fileLoc, $fileName, '', str_replace($iceRoot, "", $_GET['oldFileName']), '', '');
            $doNext .= 'tabNum = ICEcoder.openFiles.indexOf(\'' . str_replace("|", "/", $_GET['oldFileName']) . '\') + 1; if (0 < tabNum) {ICEcoder.renameTab(tabNum, \'' . $fileLoc . "/" . $fileName . '\');};';
            $finalAction = "rename";
            // Run any extra processes
            $extraProcessesClass = new ExtraProcesses($fileLoc, $fileName);
            $doNext = $extraProcessesClass->onFileDirRename($doNext);
        } else {
            $doNext .= "ICEcoder.message('".$t['Sorry, cannot rename'] . "\\\\n" . str_replace("|", "/", $_GET['oldFileName']) . "\\\\n\\\\n" . $t['does not seem...'] . "');";
            $finalAction = "nothing";
        }
    } else {
        $doNext .= "ICEcoder.message('".$t['Sorry, cannot rename'] . "\\\\n" . str_replace("|", "/", $_GET['oldFileName']) . "\\\\n\\\\n" . $t['Maybe public write...'] . "');";
        $finalAction = "nothing";
    }
    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del");';
};

// =================
// PASTE FILE/FOLDER
// =================

if (!$error && "paste" === $_GET['action']) {
    $source = $file;
    $dest = str_replace("//", "/", $docRoot . $iceRoot . str_replace("|", "/", $_GET['location']) . "/" . basename($source));
    if (!$demoMode && is_writable(dirname($dest))) {

        $fileOrFolder = $fileClass->paste();

        // Reload file manager
        $doNext .= 'ICEcoder.updateFileManagerList(\'add\', \'' . str_replace("|", "/", $_GET['location']) . '\', \'' . basename($dest) . '\', false, false, false, \'' . $fileOrFolder . '\');';
        $finalAction = "pasteFile";
        // Run any extra processes
        $extraProcessesClass = new ExtraProcesses($fileLoc, $fileName);
        $doNext = $extraProcessesClass->onFileDirPaste($doNext, $dest);
    } else {
        $doNext .= "ICEcoder.message('" . $t['Sorry, cannot copy'] . " \\\\n" . str_replace($docRoot, "", $source) . "\\\\n " . $t['into'] . " \\\\n" . str_replace($docRoot, "", $dest) . "');";
        $finalAction = "nothing";
    }
    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del");';
};

// ==============
// UPLOAD FILE(S)
// ==============

if (!$error && "upload" === $_GET['action']) {
    if (!$demoMode) {

        if ($_FILES['filesInput']){
            $uploads = $fileClass->getUploadedDetails($_FILES['filesInput']);
            $finalAction = $fileClass->upload($uploads);
        }
        // Run any extra processes
        $extraProcessesClass = new ExtraProcesses($fileLoc, $fileName);
        $doNext = $extraProcessesClass->onFileUpload($doNext, $uploads);
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
    $fileClass->delete();
    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del");';
};

// ======================
// REPLACE TEXT IN A FILE
// ======================

if (!$error && "replaceText" === $_GET['action']) {
    if (!$demoMode && is_writable($file)) {
        $loadedFile = toUTF8noBOM(getData($file), true);
        $find = $_GET['find'];
        $newContent = preg_replace("/($find)/i", $_GET['replace'], $loadedFile);
        $fh = fopen($file, 'w') or die($t['Sorry, cannot save']);
        fwrite($fh, $newContent);
        fclose($fh);
        $finalAction = "replaceText";

        // Run any extra processes
        $extraProcessesClass = new ExtraProcesses($fileLoc, $fileName);
        $doNext = $extraProcessesClass->onFileReplaceText($doNext, $_GET['fileRef']);

    } else {
        $doNext .= "ICEcoder.message('" . $t['Sorry, cannot replace...'] . "\\\\n" . $file . "');";
        $finalAction = "nothing";
    }

    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del");';
};

// ==========================
// GET CONTENTS OF REMOTE URL
// ==========================

if (!$error && "getRemoteFile" === $_GET['action']) {
    $lineNumber = max(isset($_REQUEST['lineNumber']) ? intval($_REQUEST['lineNumber']) : 1, 1);

    if ($remoteFile = toUTF8noBOM(getData($file, 'curl'), true)) {
        // Get URL contents
        $urlClass = new URL($remoteFile);
        $doNext .= $urlClass->load($ICEcoder["lineEnding"], $lineNumber);
        $finalAction = "getRemoteFile";

        // Run any extra processes
        $extraProcessesClass = new ExtraProcesses($fileLoc);
        $doNext = $extraProcessesClass->onGetRemoteFile($doNext);

    } else {
        $finalAction = "nothing";
        $doNext .= 'ICEcoder.message(\'' . $t['Sorry, could not...'] . ' '.$file . '\');';
    }

    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del");';
};

// =======================
// CHANGING FILE/DIR PERMS
// =======================

if (!$error && "perms" === $_GET['action']) {
    if (!$demoMode && is_writable($file)) {
        chmod($file, octdec((string) numClean($_GET['perms'])));
        $fileClass->updateFileManager('chmod', $fileLoc, $fileName, numClean($_GET['perms']), '', '', '');
        $finalAction = "perms";
        // Run any custom processes
        $extraProcessesClass = new ExtraProcesses($fileLoc, $fileName);
        $doNext = $extraProcessesClass->onFileDirPerms($doNext, $_GET['perms']);
    } else {
        $finalAction = "nothing";
        $doNext .= "ICEcoder.message('" . $t['Sorry, cannot change...'] . " \\\\n" . $file . "');";
    }
    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del");';
};

// ====================
// CHECK FOR A FILE/DIR
// ====================

if (!$error && "checkExists" === $_GET['action']) {
    // This action is called under seperate AJAX call and the responseText object stored in ICEcoder.lastFileDirCheckStatusObj
    // Nothing really done here though, we do something with the responseText
    $finalAction = "checkExists";
    $doNext .= 'ICEcoder.serverMessage(); ICEcoder.serverQueue("del");';
};

// ===================
// JSON DATA TO RETURN
// ===================

// No $filemtime yet? Get it now!
if (false === isset($filemtime) && !is_dir($file)) {
    $filemtime = "Windows" !== $serverType && file_exists($file) ? filemtime($file) : 1000000;
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
