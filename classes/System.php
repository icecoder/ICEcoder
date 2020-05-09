<?php declare(strict_types=1);

namespace ICEcoder;

class System
{
    private function createDirIfNotExists($path): void
    {
        if (false === file_exists($path)) {
            mkdir($path);
        }
    }

    public function setErrorHandling(): void
    {
        // Don't display, but log all errors
        ini_set('display_errors', '0');
        ini_set('log_errors', '1');
        $this->createDirIfNotExists(dirname(__FILE__) . '/../data/logs');
        $this->createDirIfNotExists(dirname(__FILE__) . '/../data/logs/error');
        ini_set('error_log', dirname(__FILE__) . '/../data/logs/error/error.log');
        error_reporting(-1);
    }

    public function writeLog($file, $msg): void
    {
        $this->createDirIfNotExists(dirname(__FILE__) . '/../data/logs');
        $this->createDirIfNotExists(dirname(__FILE__) . '/../data/logs/processes');
        $fh = fopen(dirname(__FILE__) . "/../data/logs/processes/{$file}", "a");
        fwrite($fh, $msg);
        fclose($fh);
    }

    public function setTimeZone(): void
    {
        // Set our default timezone and suppress warning with @
        @date_default_timezone_set(date_default_timezone_get());
    }

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
}
