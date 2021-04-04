<?php declare(strict_types=1);

namespace ICEcoder;

class URL
{
    private $remoteFile;

    /**
     * URL constructor.
     * @param $remoteFile
     */
    public function __construct($remoteFile)
    {
        $this->remoteFile = $remoteFile;
    }

    /**
     * @param string $lineEnding
     * @param int $lineNumber
     * @return string
     */
    public function load($lineEnding = "\n", $lineNumber = 1): string
    {
        // replace \r\n (Windows), \r (old Mac) and \n (Linux) line endings with whatever we chose to be lineEnding
        $this->remoteFile = str_replace("\r\n", $lineEnding, $this->remoteFile);
        $this->remoteFile = str_replace("\r", $lineEnding, $this->remoteFile);
        $this->remoteFile = str_replace("\n", $lineEnding, $this->remoteFile);
        $doNext = 'ICEcoder.newTab(false);';
        $doNext .= 'ICEcoder.getcMInstance().setValue(\'' . str_replace("\r", "", str_replace("\t", "\\\\t", str_replace("\n", "\\\\n", str_replace("'", "\\\\'", str_replace("\\", "\\\\", preg_quote($this->remoteFile)))))) . '\');';
        $doNext .= 'ICEcoder.goToLine(' . $lineNumber . ');';

        return $doNext;
    }
}
