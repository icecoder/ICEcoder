<?php
include("headers.php");
include("settings.php");
$t = $text['plugins-manager'];

// Set the plugin data source
$pluginsDataSrc = "https://icecoder.net/plugin-data?format=JSON";

// Now get our plugin data and put into a PHP array
if (ini_get('allow_url_fopen')) {
	$pluginsDataJS = @file_get_contents($pluginsDataSrc, false, $context);
	if (!$pluginsDataJS) {
		$pluginsDataJS = file_get_contents(str_replace("https:","http:",$pluginsDataSrc), false, $context);
	}
} elseif (function_exists('curl_init')) {
	$pDSrc = curl_init($pluginsDataSrc);
	curl_setopt($pDSrc, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($pDSrc, CURLOPT_RETURNTRANSFER, true);
	$pluginsDataJS = curl_exec($pDSrc);
}
$pluginsData = json_decode($pluginsDataJS, true);

// If we have an action to perform
if (!$demoMode && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && isset($_GET['action'])) {

	// Get our old plugin & user settings
	$oldPlugins = $ICEcoder["plugins"];
	$settingsContents = file_get_contents($settingsFile,false,$context);

	// ==========
	// INSTALLING
	// ==========

	if ($_GET['action']=="install") {

		// Store the plugin zip to the tmp dir
		$target = '../plugins/';
		$zipURL = $pluginsData[strClean($_GET['plugin'])]['zipURL'];
	    	$zipFile = "../tmp/".basename($zipURL);
		if (ini_get('allow_url_fopen')) {
			$fileData = file_get_contents($zipURL, false, $context);
		} elseif (function_exists('curl_init')) {
    			$client = curl_init($zipURL);
		    	curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);  //fixed this line
			$fileData = curl_exec($client);
		}
		file_put_contents($zipFile, $fileData);

		// Now unpack the zip
		$zip = new ZipArchive;
		$zip->open($zipFile);

		// Create all files & dirs, in 1kb chunks
		for($i=0; $i<$zip->numFiles; $i++) {
			$name = $zip->getNameIndex($i);

			// Determine output filename
			$file = $target.$name;

			// Create the directories if necessary
			$dir = dirname($file);
			if (!is_dir($dir)) mkdir($dir, 0777, true);

			// Read from zip and write to disk
			$fpr = $zip->getStream($name);
			if (!is_dir($file)) {
				$fpw = fopen($file, 'w');
				while ($data = fread($fpr, 1024)) {
					fwrite($fpw, $data);
				}
				fclose($fpw);
			}
			fclose($fpr);
		}
		$zip->close();

		// Remove the tmp zip file
		unlink($zipFile);

		// Start creating a new chunk for the plugins settings
		$settingsNew = '"plugins"		=> array(';

		// Set all the old plugins
		for ($i=0; $i<count($oldPlugins); $i++) {
			$settingsNew .= '	array("'.$oldPlugins[$i][0].'","'.$oldPlugins[$i][1].'","'.$oldPlugins[$i][2].'","'.$oldPlugins[$i][3].'","'.$oldPlugins[$i][4].'","'.$oldPlugins[$i][5].'"),';
		}
		// Then add the new one
		$settingsNew .= '			array("'.$pluginsData[$_GET['plugin']]['name'].'","'.$pluginsData[$_GET['plugin']]['icon'].'","'.$pluginsData[$_GET['plugin']]['style'].'","'.$pluginsData[$_GET['plugin']]['URL'].'","'.$pluginsData[$_GET['plugin']]['target'].'","'.$pluginsData[$_GET['plugin']]['timer'].'")';
		$settingsNew .= '	),'.PHP_EOL;
	}

	// ============
	// UNINSTALLING
	// ============

	if ($_GET['action']=="uninstall") {

		// Start creating a new chunk for the plugins settings
		$settingsNew = '"plugins"		=> array(';

		// Set all the old plugins
		for ($i=0; $i<count($oldPlugins); $i++) {
			// As long as it's not the one we want to remove
			if ($oldPlugins[$i][0] != $pluginsData[$_GET['plugin']]['name']) {
				$settingsNew .= '	array("'.$oldPlugins[$i][0].'","'.$oldPlugins[$i][1].'","'.$oldPlugins[$i][2].'","'.$oldPlugins[$i][3].'","'.$oldPlugins[$i][4].'","'.$oldPlugins[$i][5].'"),';
			}
		}
		// Rtrim off the last comma
		$settingsNew = rtrim($settingsNew,',');
		$settingsNew .= '	),'.PHP_EOL;

		// Finally, delete the plugin itself
		$target = '../plugins/';
		$dirName = basename($pluginsData[strClean($_GET['plugin'])]['zipURL'],".zip");
		deletePlugin($target.$dirName."/");
	}

	// ========
	// UPDATING
	// ========

	if ($_GET['action']=="update") {

		// Start creating a new chunk for the plugins settings
		$settingsNew = '"plugins"		=> array(';

		// Redo the arrays using the form data
		for ($i=0; $i<count($oldPlugins); $i++) {
			$timer = intval($_POST['timer'.$i]);
			if ($timer == 0) {
				$timer = "";
			}
			$settingsNew .= '	array("'.$_POST['name'.$i].'","'.$_POST['icon'.$i].'","'.$_POST['style'.$i].'","'.$_POST['URL'.$i].'","'.$_POST['target'.$i].'","'.$timer.'"),';
		}
		// Rtrim off the last comma
		$settingsNew = rtrim($settingsNew,',');
		$settingsNew .= '	),'.PHP_EOL;
	}

	// Now we have a new settingsNew string to use and files installed/uninstalled
	// we can update the plugin arrays in the settings file

	// Identify the bit to replace
	$repPosStart = strpos($settingsContents,'"plugins"');
	$repPosEnd = strpos($settingsContents,'"githubLocalPaths"');

	// Compile our new settings
	$settingsContents = substr($settingsContents,0,$repPosStart).$settingsNew.substr($settingsContents,$repPosEnd,strlen($settingsContents));

	// Now update the config file
	if (is_writeable($settingsFile)) {
		$fh = fopen($settingsFile, 'w');
		fwrite($fh, $settingsContents);
		fclose($fh);
		// Finally, reload ICEcoder itself if plugin requires it or just the iFrame screen for the user if it doesn't
		if ($_GET['action']=="install" && $pluginsData[$_GET['plugin']]['reload'] == "true") {
			echo "<script>if (top.confirm('".$t['ICEcoder needs to...']."')) {top.window.location.reload(true);} else {window.location='plugins-manager.php?updatedPlugins&csrf='+top.ICEcoder.csrf;}</script>";
		} else {
			header("Location: plugins-manager.php?updatedPlugins&csrf=".$_SESSION["csrf"]);
			echo "<script>window.location='plugins-manager.php?updatedPlugins&csrf='+top.ICEcoder.csrf;</script>";
		}
		die("<span style='color: #fff'>".$t['saving plugins']."</span>");
	} else {
		echo "<script>top.ICEcoder.message('".$t['Cannot update config...']." lib/".$settingsFile." ".$t['and try again']."');</script>";
	}
}

// Function to delete the plugin dir & files/dirs inside
function deletePlugin($dir) {
    $mydir = opendir($dir);
    while(false !== ($file = readdir($mydir))) {
        if($file != "." && $file != "..") {
            chmod($dir.$file, 0777);
            if(is_dir($dir.$file)) {
                chdir('.');
                deletePlugin($dir.$file.'/');
                if(is_dir($dir.$file)) {
			rmdir($dir.$file) or DIE("<span style='color: #fff'>".$t['couldnt delete dir'].": $dir$file</span><br />");
		}
            }
            else
                unlink($dir.$file) or DIE("<span style='color: #fff''>".$t['couldnt delete file'].": $dir$file</span><br />");
        }
    }
    closedir($mydir);
    rmdir($dir);
}
?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> plugins manager</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="plugins-manager.css?microtime=<?php echo microtime(true);?>">
</head>

<body class="pluginsManager">

<h1><?php echo $t['plugins'];?></h1>

<a href="javascript:top.ICEcoder.showManual('<?php echo $ICEcoder["versionNo"];?>','writingPlugins')" style="position: absolute; top: 26px; right: 20px"><div style="padding: 10px; background: #333; color: #fff; font-size: 18px"><?php echo $t['Guide to writing...'];?></div></a>
<div style="display: inline-block; width: 760px; height: 340px; overflow-y: auto">
	<?php
	$plugins = $ICEcoder['plugins'];
	if (count($plugins) > 0) {
	?>
	<div style="display: inline-block; width: 740px; margin-bottom: 30px">
		<h2><?php echo $t['Manage Installed'];?></h2><br>

		<form id="pluginUpdateForm" action="plugins-manager.php?action=update" method="POST">
			<table>
			<tr>
			<td colspan="2"></td>
			<td style="padding-left: 5px"><?php echo $t['URL'];?></td>
			<td style="padding-left: 5px"><?php echo $t['Target'];?></td>
			<td style="padding-left: 5px"><?php echo $t['Timer'];?></td>
			</tr>
			<?php
			for ($i=0; $i<count($plugins); $i++) {
				echo '<tr>';
				echo '<td style="padding: 0 10px 8px 0; width: 28px; text-align: center"><img src="../'.$plugins[$i][1].'" alt="'.$plugins[$i][0].'"><input type="hidden" name="name'.$i.'" value="'.$plugins[$i][0].'"><input type="hidden" name="icon'.$i.'" value="'.$plugins[$i][1].'"><input type="hidden" name="style'.$i.'" value="'.$plugins[$i][2].'"></td>';
				echo '<td style="padding: 8px 10px 8px 0; width: 250px; white-space: nowrap">'.$plugins[$i][0].'</td>';
				echo '<td style="padding: 0 10px 8px 0"><input type="text" name="URL'.$i.'" value="'.$plugins[$i][3].'" style="width: 280px"></td>';
				echo '<td style="padding: 0 10px 8px 0"><input type="text" name="target'.$i.'" value="'.$plugins[$i][4].'" style="width: 70px"></td>';
				echo '<td style="padding: 0 0 8px 0"><input type="text" name="timer'.$i.'" value="'.$plugins[$i][5].'" style="width: 50px"></td>';
				echo '</tr>';
			}
			echo '<tr>';
			echo '<td colspan="4"></td>';
			echo '<td style="padding: 3px 0 8px 0"><div style="padding: 5px; background: #2187e7; color: #fff; font-size: 12px; cursor: pointer" onclick="document.getElementById(\'pluginUpdateForm\').submit()">'.$t['Update'].'</div></td>';
			echo '</tr>';
			?>
			</table>
			<input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
		</form>
	</div>
	<?php
	;};
	?>

	<div style="display: inline-block; width: 740px">
		<h2><?php echo $t['Install'].' / '.$t['Uninstall'];?></h2><br>

		<table>
		<?php
		for ($i=0; $i<count($pluginsData); $i++) {
			if ($i % 2 == 0) {
				echo '<tr>'.PHP_EOL;
			}

			$installUninstallButton = '<div style="display: inline-block; padding: 5px; background: #2187e7; color: #fff; font-size: 12px; cursor: pointer" onclick="window.location=\'plugins-manager.php?action=install&plugin='.$i.'&csrf='.$_SESSION["csrf"].'\'">'.$t['Install'].'</div>';
			for ($j=0; $j<count($plugins); $j++) {
				if ($pluginsData[$i]['name'] == $plugins[$j][0]) {
					$installUninstallButton = '<div style="display: inline-block; padding: 5px; background: #333; color: #fff; font-size: 12px; cursor: pointer" onclick="window.location=\'plugins-manager.php?action=uninstall&plugin='.$i.'&csrf='.$_SESSION["csrf"].'\'">'.$t['Uninstall'].'</div>';
				}
			}

			$reloadExtra = $pluginsData[$i]['reload'] == 'true' ? '<br><span style="color: #888">'.$t['Reload after install...'].'</span>' : '';
			echo '<td style="padding: 0 10px 18px 0; width: 28px; text-align: center"><img src="https://icecoder.net/'.$pluginsData[$i]['icon'].'" alt="'.$pluginsData[$i]['name'].'"></td>';
			echo '<td style="padding: 8px 10px 8px 0; width: 250px; white-space: nowrap">'.$pluginsData[$i]['name'].$reloadExtra.'</td>';
			$styleExtra = ($i % 2 == 1 || $i == count($pluginsData)-1) ? "0" : "30px";
			echo '<td style="padding: 3px '.$styleExtra.' 8px 0">'.$installUninstallButton.'</td>';

			if ($i % 2 == 1 || $i == count($pluginsData)-1) {
				echo PHP_EOL.'</tr>'.PHP_EOL;
			}
		}
		?>
		</table>
	</div>
</div>

</body>

</html>
