<?php
// Purpose:	This file is run when a user saves a file, has $file and $fileLoc strings available to it
// Langs:	PHP only
// Example:
// $fh = fopen(dirname(__FILE__)."/../file-saves.log", 'a');
// fwrite($fh, "save   ".date("D dS M Y h:i:sa").": ".$file."\n");
// fclose($fh);

// Compiling Sass and LESS files (.scss and .less to .css version, with same name, in same dir)

$fileName = basename($file);
$fileNameExtPos = strrpos($fileName,".");
$filePieces = explode(".",$file);
$fileExt = $filePieces[count($filePieces)-1];

// SCSS Compiling if we have SCSSPHP plugin installed
if (strtolower($fileExt) == "scss" && file_exists(dirname(__FILE__)."/../plugins/scssphp/scss.inc.php")) {
	// Load the SCSSPHP lib and start a new instance
	require dirname(__FILE__)."/../plugins/scssphp/scss.inc.php";
	$scss = new scssc();

	// Set the import path and formatting type
	$scss->setImportPaths(dirname($file)."/");
	$scss->setFormatter('scss_formatter_compressed');	// scss_formatter, scss_formatter_nested, scss_formatter_compressed

	try {
		$scssContent = $scss->compile('@import "'.$fileName.'"');
		$fh = fopen(substr($file, 0, -$fileNameExtPos)."css", 'w');
		fwrite($fh, $scssContent);
		fclose($fh);
	} catch (Exception $e) {
		echo ";top.ICEcoder.message('Couldn\'t compile your Sass, error info below:\\n\\n".$e->getMessage()."');";
	}
}

// LESS Compiling if we have LESSPHP plugin installed
if (strtolower($fileExt) == "less" && file_exists(dirname(__FILE__)."/../plugins/lessphp/lessc.inc.php")) {
	// Load the LESSPHP lib and start a new instance
	require dirname(__FILE__)."/../plugins/lessphp/lessc.inc.php";
	$less = new lessc();

	// Set the formatting type and if we want to preserve comments
	$less->setFormatter('lessjs');				// lessjs (same style used in LESS for JS), compressed (no whitespace) or classic (LESSPHP's original formatting)
	$less->setPreserveComments(false);			// true or false

	try {
		$less->checkedCompile($file, substr($file, 0, -$fileNameExtPos)."css"); // Note: Only recompiles if changed
	} catch (Exception $e) {
		echo ";top.ICEcoder.message('Couldn\'t compile your LESS, error info below:\\n\\n".$e->getMessage()."');";
	}
}
?>