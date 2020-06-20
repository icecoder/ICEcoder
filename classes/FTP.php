<?php declare(strict_types=1);

namespace ICEcoder;

use ICEcoder\System;

class FTP
{
    private $systemClass;

    public function __construct()
    {
        $this->systemClass = new System();
    }

    public function writeFile() {
        global $fileLoc, $fileName, $ftpConn, $ftpRoot, $ftpHost, $ftpMode, $ICEcoder, $doNext, $filemtime;

        $ftpFilepath = ltrim($fileLoc . "/" . $fileName, "/");
        if (isset($_POST['changes'])) {
            // Get existing file contents as lines
            $loadedFile = toUTF8noBOM($this->ftpGetContents($ftpConn, $ftpRoot . $fileLoc . "/" . $fileName, $ftpMode), false);
            $fileLines = explode("\n", str_replace("\r", "", $loadedFile));
            // Need to add a new line at the end of each because explode will lose them,
            // want want to end up with same array that 'file($file)' produces for a local file
            // - it keeps the line endings at the end of each array item
            for ($i = 0; $i < count($fileLines); $i++) {
                if ($i < count($fileLines) - 1) {
                    $fileLines[$i] .= $ICEcoder["lineEnding"];
                }
            }
            // Stitch changes onto it
            $contents = $this->systemClass->stitchChanges($fileLines, $_POST['changes']);

            // get old file contents and count stats on usage \n and \r there
            // in this case we can keep line endings, which file had before, without
            // making code version control systems going crazy about line endings change in whole file.
            $unixNewLines = preg_match_all('/[^\r][\n]/u', $loadedFile);
            $windowsNewLines = preg_match_all('/[\r][\n]/u', $loadedFile);
        } else {
            $contents = $_POST['contents'];
        }

        // replace \r\n (Windows), \r (old Mac) and \n (Linux) line endings with whatever we chose to be lineEnding
        $contents = str_replace("\r\n", $ICEcoder["lineEnding"], $contents);
        $contents = str_replace("\r", $ICEcoder["lineEnding"], $contents);
        $contents = str_replace("\n", $ICEcoder["lineEnding"], $contents);
        if (isset($_POST['changes']) && ($unixNewLines > 0) || ($windowsNewLines > 0)) {
            if ($unixNewLines > $windowsNewLines){
                $contents = str_replace($ICEcoder["lineEnding"], "\n", $contents);
            } elseif ($windowsNewLines > $unixNewLines){
                $contents = str_replace($ICEcoder["lineEnding"], "\r\n", $contents);
            }
        }
        // Write our file contents
        if (!$this->ftpWriteFile($ftpConn, $ftpFilepath, $contents, $ftpMode)) {
            $doNext .= 'ICEcoder.message("Sorry, could not write ' . $ftpFilepath . ' at ' . $ftpHost . '");';
        } else {
            $doNext .= 'ICEcoder.openFileMDTs[ICEcoder.selectedTab - 1]="' . $filemtime . '";';
            $doNext .= '(function() {var x = ICEcoder.openFileVersions; var y = ICEcoder.selectedTab-1; x[y] = "undefined" != typeof x[y] ? x[y] + 1 : 1})(); ICEcoder.updateVersionsDisplay();';
        }
    }

    // Start a FTP connection
    function ftpStart()
    {
        global $ftpConn, $ftpLogin, $ftpHost, $ftpUser, $ftpPass, $ftpPasv;

        // Establish connection, login and maybe use pasv
        $ftpConn = ftp_connect($ftpHost);
        $ftpLogin = ftp_login($ftpConn, $ftpUser, $ftpPass);
        if ($ftpPasv) {
            ftp_pasv($ftpConn, true);
        }
    }

    // End a FTP connection
    function ftpEnd()
    {
        global $ftpConn;

        ftp_close($ftpConn);
    }

    // Get dir/file lists (simple and detailed) from FTP detailed rawlist response
    function ftpGetList($ftpConn, $directory = '.')
    {
        $simpleList = $detailedList = array();
        // If we have a FTP rawlist to work with
        if (is_array($rows = @ftp_rawlist($ftpConn, $directory))) {
            foreach ($rows as $row) {
                // Split row up by spaces and set keys on $item array
                $chunks = preg_split("/\s+/", $row);
                list($item['rights'], $item['number'], $item['user'], $item['group'], $item['size'], $item['month'], $item['day'], $item['time']) = $chunks;
                // Also set if this is a dir or file
                $item['type'] = $chunks[0][0] === 'd' ? 'directory' : 'file';
                // Splice the array and finally work out $simpleList and $detailedList
                array_splice($chunks, 0, 8);
                $detailedList[implode(" ", $chunks)] = $item;
                $simpleList[] = implode(" ", $chunks);
            }
            // Return simple array list and detailed items list also
            return array('simpleList' => $simpleList, 'detailedList' => $detailedList);
        }
        return false;
    }

    // Get detailed info on a file from returned info from ftpGetList
    function ftpGetFileInfo($ftpConn, $directory = '.', $fileName)
    {
        // Get both sets of arrays back and get our detailed list
        $ftpListArrays = $this->ftpGetList($ftpConn, $directory);
        $detailedList = $ftpListArrays['detailedList'];

        // Now get the file info for our file
        $fileInfo = $detailedList[$fileName];

        // Return the info
        return $fileInfo;
    }

    // Get contents over FTP
    function ftpGetContents($ftpConn, $filepath, $ftpMode)
    {
        // Create temp handler, this type needed for extended char set
        $tempHandle = fopen('php://temp', 'r+');

        // Get file from FTP assuming that it exists
        ftp_fget($ftpConn, $tempHandle, $filepath, $ftpMode, 0);

        // Return our content
        return stream_get_contents($tempHandle, -1, 0);
    }

    // Write file contents over FTP
    function ftpWriteFile($ftpConn, $filepath, $contents, $ftpMode)
    {
        // Create temp handler, this type needed for extended char set
        $tempHandle = fopen('php://temp', 'r+');

        // Write contents to handle and rewind head
        fwrite($tempHandle, $contents);
        rewind($tempHandle);

        // Write our content and return true/false
        return ftp_fput($ftpConn, $filepath, $tempHandle, $ftpMode, 0);
    }

    // Make a new dir over FTP
    function ftpMkDir($ftpConn, $perms, $dir)
    {
        // Create the new dir
        if (!ftp_mkdir($ftpConn, $dir)) {
            return false;
        } else {
            // Also then set perms (we must be able to do that if we created dir, so can always return true)
            $this->ftpPerms($ftpConn, $perms, $dir);
            return true;
        }
    }

    // Rename a dir/dile over FTP
    function ftpRename($ftpConn, $oldPath, $newPath)
    {
        // Return success status of rename
        return ftp_rename($ftpConn, $oldPath, $newPath);
    }

    // Change dir/file perms over FTP
    function ftpPerms($ftpConn, $perms, $filePath)
    {
        // Return success status of perms change
        return ftp_chmod($ftpConn, $perms, $filePath);
    }

    // Delete dir/file over FTP
    function ftpDelete($ftpConn, $type, $path)
    {
        if ($type == "file") {
            // Delete our file and return true/false
            return ftp_delete($ftpConn, $path);
        } else {
            // Delete our dir and return true/false
            return ftp_rmdir($ftpConn, $path);
        }
    }

}
