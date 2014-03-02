<?php
// ------------------------------------------------------------------
// BugTail for ICEcoder v1.0 by Last Mile Synergy, LLC
// Provides the class for tailing log files
// ------------------------------------------------------------------
class BugTail {

	 public function __construct($ICEcoder) {
		 $this->config = $ICEcoder;
		 
		 //temp
		 $this->config["bugFilePaths"] = "/var/log/apache2/access.log,/var/log/apache2/error.log";
		 $this->config["bugFileSizes"] = "133663,1389153";
		 $this->config["bugFileMaxLines"] = "10";
	}

	public function bugCheck() {
		$this->tailFiles();
		return($this->getTails());
	}

	private function tailFiles() {
		$files = explode(",", $this->config["bugFilePaths"]);
		$sizes = explode(",", $this->config["bugFileSizes"]);

		$i = 0;
		foreach ($files as $file) {
			$size = $sizes[$i];

			clearstatcache();
        	$currentSize = filesize($file);

			if ($currentSize > $size) {
				$content = "/n" . $file . "/n";
				$content .= file_get_contents($file, NULL, NULL, $size);

				file_put_contents($this->config["docRoot"] . "/icecoder/tmp/log.log", $content, FILE_APPEND);
				$sizes[$i] = $currentSize;
			}
			$i++;
		}
		$this->saveFileSizes(implode(",", $sizes));
	}

	private function saveFileSizes($sizes) {
		
	}
	
	private function getTails() {
		//Lorenzo Stanco's tail-by-row-number solution
		//https://gist.github.com/lorenzos/1711e81a9162320fde20
 
		$filepath = $this->config["docRoot"] . "/icecoder/tmp/log.log";
		$lines = $this->config["bugFileMaxLines"];
		$adaptive = true;

		// Open file
		$f = @fopen($filepath, "rb");
		if ($f === false) return false;
 
		// Sets buffer size
		if (!$adaptive) $buffer = 4096;
		else $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));
 
		// Jump to last character
		fseek($f, -1, SEEK_END);
 
		// Read it and adjust line number if necessary
		// (Otherwise the result would be wrong if file doesn't end with a blank line)
		if (fread($f, 1) != "\n") $lines -= 1;
		
		// Start reading
		$output = '';
		$chunk = '';
 
		// While we would like more
		while (ftell($f) > 0 && $lines >= 0) {
 
			// Figure out how far back we should jump
			$seek = min(ftell($f), $buffer);
 
			// Do the jump (backwards, relative to where we are)
			fseek($f, -$seek, SEEK_CUR);
 
			// Read a chunk and prepend it to our output
			$output = ($chunk = fread($f, $seek)) . $output;
 
			// Jump back to where we started reading
			fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
 
			// Decrease our line counter
			$lines -= substr_count($chunk, "\n");
 
		}
 
		// While we have too many lines
		// (Because of buffer size we might have read too many)
		while ($lines++ < 0) {
 
			// Find first newline and remove all text before that
			$output = substr($output, strpos($output, "\n") + 1);
 
		}
 
		// Close file and return
		fclose($f);
		return trim($output);
	}
}
?>