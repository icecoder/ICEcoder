<?php
// Get dir/file lists (simple and detailed) from FTP detailed rawlist response
function ftpGetList($ftpConn, $directory = '.') {
	$simpleList = $detailedList = array();
	// If we have a FTP rawlist to work with
	if (is_array($rows = @ftp_rawlist($ftpConn, $directory))) {
		foreach ($rows as $row) {
			// Split row up by spaces and set keys on $item array
			$chunks = preg_split("/\s+/", $row);
			list($item['rights'], $item['number'], $item['user'], $item['group'], $item['size'], $item['month'], $item['day'], $item['time']) = $chunks;
			// Also set if this is a dir or file
			$item['type'] = $chunks[0]{0} === 'd' ? 'directory' : 'file';
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

// Get contents over FTP
function ftpGetContents($ftpConn, $filepath, $ftpMode) {
	// Create temp handler, this type needed for extended char set
	$tempHandle = fopen('php://temp', 'r+'); 

	// Get file from FTP assuming that it exists
	ftp_fget($ftpConn, $tempHandle, $filepath, $ftpMode, 0);

	// Return our content
	return stream_get_contents($tempHandle, -1, 0);
}

// Write file contents over FTP
function ftpWriteFile($ftpConn, $filepath, $contents, $ftpMode) {
	// Create temp handler, this type needed for extended char set
	$tempHandle = fopen('php://temp', 'r+'); 

	// Write contents to handle and rewind head
	fwrite($tempHandle, $contents);
	rewind($tempHandle);

	// Write our content and return true/false
	return ftp_fput($ftpConn, $filepath, $tempHandle, $ftpMode, 0);
}

// Make a new dir over FTP
function ftpMkDir($ftpConn, $perms, $dir) {
	// Create the new dir
	if (!ftp_mkdir($ftpConn, $dir)) {
		return false;
	} else {
		// Also then set perms (we must be able to do that if we created dir, so can always return true)
		ftpPerms($ftpConn, $perms, $dir);
		return true;
	}
}

// Change dir/file perms over FTP
function ftpPerms($ftpConn, $perms, $filePath) {
	// Return success status of perms change
	return ftp_chmod($ftpConn, $perms, $filePath);
}
?>