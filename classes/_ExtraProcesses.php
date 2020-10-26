<?php declare(strict_types=1);

namespace ICEcoder;

use ICEcoder\System;

class ExtraProcesses
{
    private $fileLoc;
    private $fileName;
    private $username;
    private $systemClass;

    /**
     * ExtraProcesses constructor.
     * @param string $fileLoc
     * @param string $fileName
     */
    public function __construct($fileLoc = "", $fileName = "")
    {
        $this->fileLoc = $fileLoc;
        $this->fileName = $fileName;
        $this->username = $_SESSION['username'];
        $this->systemClass= new System;
    }

    /**
     * @param $action
     * @param string $msg
     */
    private function writeLog($action, $msg = "")
    {
        $username = "" !== $this->username ? $this->username : "default-user";

        $this->systemClass->writeLog(
            "{$username}.log",
            "{$action} >>> " . date("D dS M Y h:i:sa") . " : " . ($this->fileLoc) . "/" . ($this->fileName) . ("" !== $msg ? " : " . $msg : "") . "\n"
        );
    }

    /**
     * @param string $doNext
     * @return string
     */
    public function onLoad($doNext = ""): string
    {
        // PHP example:
        // $this->writeLog("LOAD");

        // JS example:
        // $doNext .= "alert('Loaded');";

        return $doNext;
    }

    /**
     *
     */
    public function onFileLoad()
    {
        // PHP example:
        // $this->writeLog("FILE LOAD");
    }

    /**
     * @param string $doNext
     * @return string
     */
    public function onFileSave($doNext = ""): string
    {
        // PHP example:
        // $this->writeLog("SAVE");

        // JS example:
        // $doNext .= "alert('Saved');";

        return $doNext;
    }

    /**
     * @param string $doNext
     * @return string
     */
    public function onGetRemoteFile($doNext = ""): string
    {
        // PHP example:
        // $this->writeLog("GET REMOTE FILE");

        // JS example:
        // $doNext .= "alert('Got remote file');";

        return $doNext;

    }

    /**
     * @param string $doNext
     * @param $uploads
     * @return string
     */
    public function onFileUpload($doNext = "", $uploads = []): string
    {
        // PHP example:
        // foreach ($uploads as $upload) {
        //    $this->writeLog("UPLOAD FILE", $upload->name);
        // }

        // JS example:
        // $doNext .= "alert('Uploaded');";

        return $doNext;

    }

    /**
     * @param string $doNext
     * @param string $file
     * @return string
     */
    public function onFileReplaceText($doNext = "", $file = ""): string
    {
        // PHP example:
        // $this->writeLog("REPLACE TEXT IN FILE", $file);

        // JS example:
        // $doNext .= "alert('Replaced text in file');";

        return $doNext;
    }


    /**
     * @param string $doNext
     * @return string
     */
    public function onDirNew($doNext = ""): string
    {
        // PHP example:
        // $this->writeLog("NEW DIR");

        // JS example:
        // $doNext .= "alert('new dir');";

        return $doNext;

    }

    /**
     * @param string $doNext
     * @return string
     */
    public function onFileDirDelete($doNext = ""): string
    {
        // PHP example:
        // $this->writeLog("DELETE FILE/DIR");

        // JS example:
        // $doNext .= "alert('Deleted file/dir');";

        return $doNext;
    }

    /**
     * @param string $doNext
     * @param string $file
     * @return string
     */
    public function onFileDirPaste($doNext = "", $file = ""): string
    {
        // PHP example:
        // $this->writeLog("PASTE FILE/DIR");

        // JS example:
        // $doNext .= "alert('Pasted file/dir');";

        return $doNext;
    }

    /**
     * @param string $doNext
     * @return string
     */
    public function onFileDirMove($doNext = ""): string
    {
        // PHP example:
        // $this->writeLog("MOVE FILE/DIR");

        // JS example:
        // $doNext .= "alert('Moved');";

        return $doNext;
    }

    /**
     * @param string $doNext
     * @return string
     */
    public function onFileDirRename($doNext = ""): string
    {
        // PHP example:
        // $this->writeLog("RENAME FILE/DIR");

        // JS example:
        // $doNext .= "alert('Renamed file/dir');";

        return $doNext;
    }

    /**
     * @param string $doNext
     * @param string $perms
     * @return string
     */
    public function onFileDirPerms($doNext = "", $perms = "unknown"): string
    {
        // PHP example:
        // $this->writeLog("PERMS", $perms);

        // JS example:
        // $doNext .= "alert('Perms changed to ' + $perms);";

        return $doNext;
    }

    /**
     * @param string $username
     */
    public function onUserNew($username = "")
    {
        // PHP example:
        // $this->writeLog("USER NEW", $username ?? "");
    }

    /**
     * @param string $username
     */
    public function onUserLogin($username = "")
    {
        // PHP example:
        // $this->writeLog("USER LOGIN", $username ?? "");
    }

    /**
     * @param string $username
     */
    public function onUserLogout($username = "")
    {
        // PHP example:
        // $this->writeLog("USER LOGOUT", $username ?? "");
    }

    /**
     * @param string $username
     */
    public function onUserLoginFail($username = "")
    {
        // PHP example:
        // $this->writeLog("USER LOGIN FAIL", $username ?? "");
    }

    /**
     * @param string $result
     * @param string $status
     */
    public function onBugCheckResult($result = "", $status = "")
    {
        // PHP example:
        // $this->writeLog("BUG CHECK", $result . " : ". var_export($status, true));
    }
}
