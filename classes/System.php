<?php declare(strict_types=1);

namespace ICEcoder;

class System
{
    /**
     * @param $name
     */
    public function functionEnabled($name) {
        return is_callable($name) && false === stripos(ini_get('disable_functions'), $name);
    }

    /**
     * @param $path
     */
    private function createDirIfNotExists($path)
    {
        if (false === file_exists($path)) {
            mkdir($path);
        }
    }

    /**
     * @param $path
     */
    private function createFileIfNotExists($path)
    {
        if (false === file_exists($path)) {
            touch($path);
        }
    }

    /**
     * @return string
     */
    public function getOS() {
        switch (true) {
            case stristr(PHP_OS, 'DAR'): return 'Mac';
            case stristr(PHP_OS, 'WIN'): return 'Windows';
            case stristr(PHP_OS, 'LINUX'): return 'Linux';
            default : return 'Linux';
        }
    }

    public function setErrorHandling()
    {
        // Don't display, but log all errors
        ini_set('display_errors', '0');
        ini_set('log_errors', '1');
        $this->createDirIfNotExists(dirname(__FILE__) . '/../data/logs');
        $this->createDirIfNotExists(dirname(__FILE__) . '/../data/logs/error');
        $this->createFileIfNotExists(dirname(__FILE__) . '/../data/logs/error/error.log');
        ini_set('error_log', dirname(__FILE__) . '/../data/logs/error/error.log');
        error_reporting(-1);
    }

    /**
     * @param $file
     * @param $msg
     */
    public function writeLog($file, $msg)
    {
        $this->createDirIfNotExists(dirname(__FILE__) . '/../data/logs');
        $this->createDirIfNotExists(dirname(__FILE__) . '/../data/logs/processes');
        $fh = fopen(dirname(__FILE__) . "/../data/logs/processes/{$file}", "a");
        fwrite($fh, $msg);
        fclose($fh);
    }

    public function setTimeZone()
    {
        // Set our default timezone and suppress warning with @
        @date_default_timezone_set(date_default_timezone_get());
    }

    /**
     * @return resource
     */
    public function setStreamContext()
    {
        // Set a stream context timeout for file reading
        $context = stream_context_create(array('http'=>
            array(
                'timeout' => 60 // secs
            )
        ));

        return $context;
    }

    /**
     * @param $path
     */
    public function invalidateOPCache($path)
    {
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($path, true);
        }
    }

    /**
     * @param $fileLines
     * @param $changes
     * @return string
     */
    public function stitchChanges($fileLines, $changes) {
        global $ICEcoder;

        // Get our JSON changes from difflib and put into an array
        $changes = json_decode($changes, true);

        // For each of those changes, handle the same requests on file on server to to match client view seen
        for ($i = 0; $i < count($changes); $i++) {
            // Replace line(s)
            if ("replace" === $changes[$i][0]) {
                // Take 1 from end
                for ($j = $changes[$i][1]; $j <= $changes[$i][2] - 1; $j++) {
                    // Clear content of line
                    $fileLines[$j] = "";
                    // If it's the last line in the range
                    if ($j == $changes[$i][2] - 1) {
                        // Replace the line with our replacement
                        // and if the last line, rtrim the new line from JS
                        $fileLines[$j] =
                            $j === count($fileLines) - 1
                                ? rtrim($changes[$i][5], $ICEcoder["lineEnding"])
                                : $changes[$i][5];
                    }
                }
            }
            // Insert line(s)
            if ("insert" === $changes[$i][0]) {
                // Take 1 from start and end
                for ($j = $changes[$i][1] - 1; $j <= $changes[$i][2] - 1; $j++) {
                    // Start of file, insert change and then 1st line afterwards
                    if ($j === -1) {
                        $fileLines[0] = $changes[$i][5].$fileLines[0];
                        // Otherwise, middle or end of file
                    } else {
                        // Replace the line with our replacement
                        // and if the last line, prefix with line return and rtrim the new line from JS
                        $fileLines[$j] .=
                            $j == count($fileLines) - 1
                                ? $ICEcoder["lineEnding"].rtrim($changes[$i][5], $ICEcoder["lineEnding"])
                                : $changes[$i][5];
                    }
                }
            }
            // delete line(s)
            if ("delete" === $changes[$i][0]) {
                // Take 1 from end
                for ($j = $changes[$i][1]; $j <= $changes[$i][2] - 1; $j++) {
                    // Clear content of line
                    $fileLines[$j] = "";
                    // If the last line, clear line returns from it
                    if ($j == count($fileLines) - 1) {
                        $fileLines[$changes[$i][1] - 1] = rtrim(rtrim($fileLines[$changes[$i][1] - 1], "\r"), "\n");
                    }
                }
            }
        }

        // Set and return the newly stitched together content
        $contents = implode("", $fileLines);

        return $contents;
    }

    /**
     * @param $dark
     * @return string
     */
    public function getDemoModeIndicator($dark = false) {
        global $ICEcoder, $text;

        if (true === $ICEcoder["demoMode"]) {
            $darkExtra = true === $dark
                ? '.demoModeIndicator::after {position: absolute; top: 0; width: 0; height: 0; border-style: solid; border-width: 30px 0 0 30px; border-color: transparent transparent transparent #1c1c19; content: \'\'; z-index: 1}'
                : '';
            $content = '
                <style>
                    .demoModeIndicator {position: absolute; top: -30px; right: 0; width: 0; height: 0; border-top: 30px solid transparent; border-bottom: 30px solid transparent; border-right: 30px solid #b00; color: #fff; z-index: 1}
                    .demoModeIndicator svg {position: fixed; display: inline-block; width: 24px; height: 32px; right: 0; margin-right: 0; top: -3px}
                    ' . $darkExtra . '
                </style>
            ';
            $content .= '
                <div class="demoModeIndicator" title="' . $text['demo-mode']['Demo mode'] . '">' . file_get_contents(dirname(__FILE__) . "/../assets/images/icons/lock.svg") .'</div>
            ';
            return $content;
        }
        return "";
    }
}
