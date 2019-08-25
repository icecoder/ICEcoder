<?php
include("headers.php");
include("settings.php");
$t = $text['ftp-manager'];

// If we have an action to perform
if (!$demoMode && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && isset($_GET['action'])) {

	// Get our old FTP sites & user settings
	$oldFTPSites = $ICEcoder["ftpSites"];
	$settingsContents = getData("../data/".$settingsFile);

	// ========
	// CHOOSING
	// ========
	if ($_GET['action']=="choose") {

		// Set the site ref in session, hide the popup and reload the file manager
		$_SESSION['ftpSiteRef'] = numClean($_GET['ftpSiteRef']);
		// Hide this popup and reload file manager
		echo "<script>top.ICEcoder.showHide('hide',top.document.getElementById('blackMask'));top.ICEcoder.refreshFileManager();</script>";
	} else {
		// Start creating a new chunk for the FTP sites
		$settingsNew = '"ftpSites"		=> array(
';
	}

	// ======
	// ADDING
	// ======

	if ($_GET['action']=="add") {

		// Add the new FTP site
		if ($_POST['ftpSiteNEW'] != "") {
			$settingsNew .= '	array(
		"site" => "'.injClean($_POST['ftpSiteNEW']).'",
		"host" => "'.injClean($_POST['ftpHostNEW']).'",
		"user" => "'.injClean($_POST['ftpUserNEW']).'",
		"pass" => "'.injClean($_POST['ftpPassNEW']).'",
		"pasv" => '.injClean($_POST['ftpPASVNEW']).',
		"mode" => "'.injClean($_POST['ftpModeNEW']).'",
		"root" => "'.injClean($_POST['ftpRootNEW']).'"
	),
';
		}

	}

	// ===============================================
	// UPDATING & REMOVING PLUS UPDATE CONFIG SETTINGS
	// ===============================================

	if ($_GET['action']!="choose" && $_GET['action']!="edit") {

		// Look at each of the existing FTP sites
		for ($i=0; $i<count($oldFTPSites); $i++) {

			// Updating
			if ($_GET['action']=="update" && $i == $_GET['ftpSiteRef']) {
				$settingsNew .= '	array(
		"site" => "'.injClean($_POST['ftpSiteNEW']).'",
		"host" => "'.injClean($_POST['ftpHostNEW']).'",
		"user" => "'.injClean($_POST['ftpUserNEW']).'",
		"pass" => "'.injClean($_POST['ftpPassNEW']).'",
		"pasv" => '.injClean($_POST['ftpPASVNEW']).',
		"mode" => "'.injClean($_POST['ftpModeNEW']).'",
		"root" => "'.injClean($_POST['ftpRootNEW']).'"
	),
';
			// Deleting
			} elseif ($_GET['action']=="remove" && $i == $_GET['ftpSiteRef']) {
				// Do nothing, so we ignore this entry now

			// Entry is as before
			} else {
				$settingsNew .= '	array(
		"site" => "'.$oldFTPSites[$i]['site'].'",
		"host" => "'.$oldFTPSites[$i]['host'].'",
		"user" => "'.$oldFTPSites[$i]['user'].'",
		"pass" => "'.$oldFTPSites[$i]['pass'].'",
		"pasv" => '.($oldFTPSites[$i]['pasv'] ? 'true' : 'false').',
		"mode" => "'.($oldFTPSites[$i]['mode'] == 'FTP_ASCII' ? 'FTP_ASCII' : 'FTP_BINARY').'",
		"root" => "'.$oldFTPSites[$i]['root'].'"
	),
';
			}
		}
		// Rtrim off the last comma
		$settingsNew = rtrim($settingsNew,',
');
		$settingsNew .= '
),'.PHP_EOL;

		// Now we have a new settingsNew string to use
		// we can update the FTP sites in the settings file

		// Identify the bit to replace
		$repPosStart = strpos($settingsContents,'"ftpSites"');
		$repPosEnd = strpos($settingsContents,'"githubLocalPaths"');

		// Compile our new settings
		$settingsContents = substr($settingsContents,0,$repPosStart).$settingsNew.substr($settingsContents,$repPosEnd,strlen($settingsContents));

		// Now update the config file
		if (is_writeable("../data/".$settingsFile)) {
			$fh = fopen("../data/".$settingsFile, 'w');
			fwrite($fh, "../data/".$settingsContents);
			fclose($fh);
			// Finally, reload the iFrame screen for the user
			header("Location: ftp-manager.php?updatedFTPSites&csrf=".$_SESSION["csrf"]);
			echo "<script>window.location='ftp-manager.php?updatedFTPSites&csrf='+top.ICEcoder.csrf;</script>";
			die($t['Saving FTP sites']);
		} else {
			echo "<script>top.ICEcoder.message('".$t['Cannot update config...']." data/".$settingsFile." ".$t['and try again']."');</script>";
		}
	}
}
?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> FTP manager</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="ftp-manager.css?microtime=<?php echo microtime(true);?>">
</head>

<body class="ftpManager">

<h1><?php echo $t['ftp manager'];?></h1>

<div style="display: inline-block; width: 620px; height: 440px; overflow-y: auto">
	<?php
	$ftpSites = $ICEcoder['ftpSites'];
	if (count($ftpSites) > 0) {
	?>
	<div style="display: inline-block; width: 600px; margin-bottom: 30px">
		<h2><?php echo $t['Choose existing site'];?></h2><br>

		<form id="ftpUpdateForm" action="ftp-manager.php?action=update" method="POST">
			<table style="width: 100%">
			<?php
			for ($i=0; $i<count($ftpSites); $i++) {
				echo '<tr>';
				echo '<td style="padding: 10px 10px 8px 0">'.$ftpSites[$i]['site'].'</td>';
				echo '<td style="padding: 10px 10px 8px 0">'.$ftpSites[$i]['host'].'</td>';
				echo '<td style="padding: 10px 10px 8px 0"><a href="ftp-manager.php?action=edit&ftpSiteRef='.$i.'&csrf='.$_SESSION["csrf"].'" class="blue">Edit</a></td>';
				echo '<td style="padding: 10px 10px 8px 0"><a href="ftp-manager.php?action=remove&ftpSiteRef='.$i.'&csrf='.$_SESSION["csrf"].'" class="blue" onclick="return top.ICEcoder.ask(\''.$t['Are you sure...'].'\')">Delete</a></td>';
				echo '<td style="padding: 2px 20px 8px 0; text-align: right"><div style="display: inline-block; padding: 5px; margin-top: 4px; background: #2187e7; color: #fff; font-size: 12px; cursor: pointer" onclick="window.location=\'ftp-manager.php?action=choose&ftpSiteRef='.$i.'&csrf='.$_SESSION["csrf"].'\'">'.$t['Choose'].'</div></td>';
				echo '</tr>';
			}
			?>
			</table>
			<input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
		</form>
	</div>
	<?php
	}
	?>

	<div style="display: inline-block; width: 600px">
		<h2><?php echo isset($_GET['action']) && $_GET['action']=="edit" ? $t['Edit site'] : $t['Add new site'];?></h2><br>

		<form id="ftpAddEditForm" action="ftp-manager.php?action=<?php echo isset($_GET['action']) && $_GET['action']=="edit" ? "update&ftpSiteRef=".numClean($_GET['ftpSiteRef']) : "add";?>" method="POST">
			<table>
			<tr>
			<td style="padding-left: 5px"><?php echo $t['Site base'];?> <span class="info" title="<?php echo $t['eg http://yourdomain.com'];?>">[?]</span></td>
			<td style="padding-left: 5px"><?php echo $t['Host'];?> <span class="info" title="<?php echo $t['eg ftp.yourdomain.com'];?>">[?]</span></td>
			</tr>
			<tr>
			<td style="padding: 0 10px 8px 0"><input type="text" name="ftpSiteNEW" value="<?php if (isset($_GET['action']) && $_GET['action']=="edit") {echo $ICEcoder['ftpSites'][numClean($_GET['ftpSiteRef'])]['site'];};?>" style="width: 272px"></td>
			<td style="padding: 0 0 8px 0"><input type="text" name="ftpHostNEW" value="<?php if (isset($_GET['action']) && $_GET['action']=="edit") {echo $ICEcoder['ftpSites'][numClean($_GET['ftpSiteRef'])]['host'];};?>" style="width: 272px"></td>
			</tr>
			<tr>
			<td style="padding-left: 5px"><?php echo $t['Username'];?> <span class="info" title="<?php echo $t['eg user123'];?>">[?]</span></td>
			<td style="padding-left: 5px"><?php echo $t['Password'];?> <span class="info" title="<?php echo $t['eg pass123'];?>">[?]</span></td>
			</tr>
			<tr>
			<td style="padding: 0 10px 8px 0"><input type="text" name="ftpUserNEW" value="<?php if (isset($_GET['action']) && $_GET['action']=="edit") {echo $ICEcoder['ftpSites'][numClean($_GET['ftpSiteRef'])]['user'];};?>" style="width: 272px"></td>
			<td style="padding: 0 0 8px 0"><input type="password" name="ftpPassNEW" value="<?php if (isset($_GET['action']) && $_GET['action']=="edit") {echo $ICEcoder['ftpSites'][numClean($_GET['ftpSiteRef'])]['pass'];};?>" style="width: 272px"></td>
			</tr>
			<tr>
			<td style="padding-left: 5px"><?php echo $t['PASV and mode'];?> <span class="info" title="<?php echo $t['Use PASV mode...'];?>">[?]</span></td>
			<td style="padding-left: 5px"><?php echo $t['Root'];?> <span class="info" title="<?php echo $t['eg /htdocs'];?>">[?]</span></td>
			</tr>
			<tr>
			<td style="padding: 0 10px 8px 0">
				<select name="ftpPASVNEW">
					<option value="false"<?php echo isset($_GET['action']) && $_GET['action']=="edit" && $ICEcoder['ftpSites'][$_GET['ftpSiteRef']]['pasv'] == false ? " selected" : "";?>><?php echo $t['PASV connection off'];?></option>
					<option value="true"<?php echo isset($_GET['action']) && $_GET['action']=="edit" && $ICEcoder['ftpSites'][$_GET['ftpSiteRef']]['pasv'] == true ? " selected" : "";?>><?php echo $t['PASV connection on'];?></option>
				</select>
				<select name="ftpModeNEW">
					<option value="FTP_ASCII"<?php echo isset($_GET['action']) && $_GET['action']=="edit" && $ICEcoder['ftpSites'][$_GET['ftpSiteRef']]['mode'] == "FTP_ASCII" ? " selected" : "";?>><?php echo $t['ASCII transfer'];?></option>
					<option value="FTP_BINARY"<?php echo isset($_GET['action']) && $_GET['action']=="edit" && $ICEcoder['ftpSites'][$_GET['ftpSiteRef']]['mode'] == "FTP_BINARY" ? " selected" : "";?>><?php echo $t['Binary transfer'];?></option>
				</select>
			</td>
			<td style="padding: 0 0 8px 0"><input type="text" name="ftpRootNEW" value="<?php if (isset($_GET['action']) && $_GET['action']=="edit") {echo $ICEcoder['ftpSites'][numClean($_GET['ftpSiteRef'])]['root'];};?>" style="width: 272px"></td>
			</tr>
			<tr>
			<td colspan="2" style="padding: 3px 0 8px 0; text-align: right"><div style="display: inline-block; padding: 5px; background: #2187e7; color: #fff; font-size: 12px; cursor: pointer" onclick="document.getElementById('ftpAddEditForm').submit()"><?php echo isset($_GET['action']) && $_GET['action']=="edit" ? $t['Update'] : $t['Add'];?></div></td>
			</tr>
			</table>
			<input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
		</form>
	</div>
</div>

</body>

</html>
