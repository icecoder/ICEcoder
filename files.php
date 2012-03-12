<?php
function fileManager($directory, $return_link) {
	$code = "";
	// Generates a list of all directories, sub-directories, and files in $directory
	// Remove trailing slash
	if(substr($directory, -1) == "/" ) {$directory = substr($directory, 0, strlen($directory)-1);};
	$code .= fileManager_dir($directory, $return_link);
	return $code;
}

function fileManager_dir($directory, $return_link, $first_call=true) {
	if (!isset($_SESSION['restrictedFiles'])) {include("lib/settings.php");};
	$restrictedFiles  = $_SESSION['restrictedFiles'];
	$bannedFiles  = $_SESSION['bannedFiles'];
	$docRoot = str_replace("\\","/",$_SERVER['DOCUMENT_ROOT']);
	if (strrpos($_SERVER['DOCUMENT_ROOT'],":")) {
		$serverType = "Windows";
	} else {
		$serverType = "Linux";
	}
	// Chop off trailing slash
	if (strrpos($docRoot,"/")==strlen($docRoot)-1) {$docRoot = substr($docRoot,0,strlen($docRoot)-1);};
	$fileManager = "";
	
	// Recursive function called by fileManager() to list directories/files
	// Get and sort directories/files
	if(function_exists("scandir")) {$file = scandir($directory);} else {$file = php4_scandir($directory);};
	natcasesort($file);

	// Make directories first
	$files = $dirs = array();
	foreach($file as $this_file) {
		if(is_dir("$directory/$this_file")) {$dirs[] = $this_file;} else {$files[] = $this_file;};
	}

	$file = array_merge($dirs, $files);
	
	// Filter unwanted files
	if(!empty($bannedFiles)) {
		foreach(array_keys($file) as $key) {
			$fileFolder = $file[$key];
			for ($i=0;$i<count($bannedFiles);$i++) {
				if(strpos($fileFolder,$bannedFiles[$i])!==false) {unset($file[$key]);};
			}
		}
	}
	if(count($file) > 2) { // To ignore . and .. directories
		if($first_call) {
			// Root Directory
			$dirRep = str_replace("\\","/",$directory);
			$link = str_replace("[link]", "$dirRep/", $return_link);
			$link = str_replace("//","/",$link);
			$fileAtts = "";

			if ($serverType=="Linux") {
				$chmodInfo = substr(sprintf('%o', fileperms($link)), -4);
				$fileAtts = '<span style="color: #888888; font-size: 8px">'.$chmodInfo.'</span>';
			}
			$fileManager = "<ul class=\"fileManager\">";
			$fileManager .= "<li class=\"pft-directory\"><a href=\"#\" onMouseOver=\"top.ICEcoder.overFileFolder('folder','$link')\" onMouseOut=\"top.ICEcoder.overFileFolder('folder','')\" style=\"position: relative; left:-22px\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id=\"|\">/ [ROOT]</span> ".$fileAtts."</a>";
			$fileManager .= $fileManager .= fileManager_dir("$directory/", $return_link ,false);
			$fileManager .= "</li>";
			$first_call = false;
		} else {
			$fileManager = "<ul>";
		}
		foreach( $file as $this_file ) {
					$bannedFile=false;
					for ($i=0;$i<count($bannedFiles);$i++) {
						if (strpos($directory,$bannedFiles[$i])!=""||strpos($this_file,$bannedFiles[$i])!="") {
							$bannedFile=true;
						}
					}
			if( $this_file != "." && $this_file != ".." && $bannedFile == false) {
				if( is_dir("$directory/$this_file") ) {
					// Directory
					$dirRep = str_replace("\\","/",$directory);
					$link = str_replace("[link]", "$dirRep/" . urlencode($this_file), $return_link);
					$link = str_replace("//","/",$link);

					$restrictedFile=false;
					for ($i=0;$i<count($restrictedFiles);$i++) {
						if (strpos($link,$restrictedFiles[$i])!="") {
							$restrictedFile=true;
						}
					}

					$fileAtts = "";
					if ($serverType=="Linux") {
						$chmodInfo = substr(sprintf('%o', fileperms($link)), -4);
						$fileAtts = '<span style="color: #888888; font-size: 8px">'.$chmodInfo.'</span>';
					}
					if ($_SESSION['userLevel'] == 10 || ($_SESSION['userLevel'] < 10 && $restrictedFile==false)) {
						$fileManager .= "<li class=\"pft-directory\"><a href=\"#\" onMouseOver=\"top.ICEcoder.overFileFolder('folder','$link')\" onMouseOut=\"top.ICEcoder.overFileFolder('folder','')\" style=\"position: relative; left:-22px\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id=\"".str_replace("/","|",str_replace($docRoot,"",$link))."\">" . htmlspecialchars($this_file) . "</span> ".$fileAtts."</a>";
						$fileManager .= fileManager_dir("$directory/$this_file", $return_link , false);
						$fileManager .= "</li>";
					} else {
						$fileManager .= "<li class=\"pft-directory\" style=\"cursor: default\"><span style=\"position: relative; left:-22px; color: #888888\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [HIDDEN] ".$fileAtts."</span></li>";
					}
				} else {
					// File
					// Get extension (prefix 'ext-' to prevent invalid classes from extensions that begin with numbers)
					$ext = "ext-" . substr($this_file, strrpos($this_file, ".") + 1);
					$dirRep = str_replace("\\","/",$directory);
					$link = str_replace("[link]", "$dirRep/" . urlencode($this_file), $return_link);
					$link = str_replace("//","/",$link);

					$restrictedFile=false;
					for ($i=0;$i<count($restrictedFiles);$i++) {
						if (strpos($link,$restrictedFiles[$i])!="") {
							$restrictedFile=true;
						}
					}
					if ($_SESSION['userLevel'] == 10 || ($_SESSION['userLevel'] < 10 && $restrictedFile==false)) {
						$fileAtts = "";
						if ($serverType=="Linux") {
							$chmodInfo = substr(sprintf('%o', fileperms($link)), -4);
							$fileAtts = '<span style="color: #888888; font-size: 8px">'.$chmodInfo.'</span>';
						}
						$fileManager .= "<li class=\"pft-file " . strtolower($ext) . "\"><a nohref onMouseOver=\"top.ICEcoder.overFileFolder('file','$link')\" onMouseOut=\"top.ICEcoder.overFileFolder('file','')\" style=\"position: relative; left:-22px\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id=\"".str_replace("/","|",str_replace($docRoot,"",$link))."\">" . htmlspecialchars($this_file) . "</span> ".$fileAtts."</a></li>";
					} else {
						$fileAtts = "<img src=\"images/file-manager-icons/padlock.png\" style=\"cursor: pointer\" onClick=\"alert('Sorry, you need higher admin level rights to view.')\">";
						$fileManager .= "<li class=\"pft-file " . strtolower($ext) . "\" style=\"cursor: default\"><span style=\"position: relative; left:-22px; color: #888888\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [HIDDEN] ".$fileAtts."</span></li>";
					}
				}
			}
		}
		$fileManager .= "</ul>";
	}
	return $fileManager;
}

// For PHP4 compatibility
function php4_scandir($dir) {
	$dh  = opendir($dir);
	while( false !== ($filename = readdir($dh)) ) {
	    $files[] = $filename;
	}
	sort($files);
	return($files);
}
?>
<!DOCTYPE html>

<html onMouseDown="top.ICEcoder.mouseDown=true" onMouseUp="top.ICEcoder.mouseDown=false" onMouseMove="top.ICEcoder.getMouseXY(event);top.ICEcoder.canResizeFilesW()" onContextMenu="top.ICEcoder.rightClickedFile=top.ICEcoder.thisFileFolderLink; top.ICEcoder.selectFileFolder(); return top.ICEcoder.showMenu()" onClick="top.document.getElementById('fileMenu').style.display='none'">
<head>
<title>ICE Coder File Manager</title>
<link rel="stylesheet" type="text/css" href="lib/files.css">
<script src="lib/coder.js" type="text/javascript"></script>
</head>

<body onLoad="top.ICEcoder.fileManager()" onClick="top.ICEcoder.selectFileFolder()" onDblClick="top.ICEcoder.openFile()" onKeyDown="return top.ICEcoder.interceptKeys('files', event);" onKeyUp="top.ICEcoder.resetKeys(event);">
<?php
	echo fileManager($_SERVER['DOCUMENT_ROOT'], "[link]");
?>

<iframe name="fileControl" style="display: none"></iframe>
		
</body>
	
</html>