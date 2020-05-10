<?php declare(strict_types=1);

namespace ICEcoder;

use ICEcoder\System;

class FTP
{
    private $system;

    public function __construct()
    {
        $this->system = new System();
    }

    public function writeFile() {
        global $fileLoc, $fileName, $ftpConn, $ftpRoot, $ftpHost, $ftpMode, $ICEcoder, $doNext, $filemtime;

        $ftpFilepath = ltrim($fileLoc . "/" . $fileName, "/");
        if (isset($_POST['changes'])) {
            // Get existing file contents as lines
            $loadedFile = toUTF8noBOM(ftpGetContents($ftpConn, $ftpRoot . $fileLoc . "/" . $fileName, $ftpMode), false);
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
            $contents = $this->system->stitchChanges($fileLines, $_POST['changes']);

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
        if (!ftpWriteFile($ftpConn, $ftpFilepath, $contents, $ftpMode)) {
            $doNext .= 'ICEcoder.message("Sorry, could not write ' . $ftpFilepath . ' at ' . $ftpHost . '");';
        } else {
            $doNext .= 'ICEcoder.openFileMDTs[ICEcoder.selectedTab - 1]="' . $filemtime . '";';
            $doNext .= '(function() {var x = ICEcoder.openFileVersions; var y = ICEcoder.selectedTab-1; x[y] = "undefined" != typeof x[y] ? x[y] + 1 : 1})(); ICEcoder.updateVersionsDisplay();';
        }
    }
}
