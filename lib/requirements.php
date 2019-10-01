<?php
if (!isset($reqsPassed)) {
	// Start off assuming all is fine with requirements
	$reqsPassed = true;
	$reqsFailures = [];
}

// Check PHP version meets minimum requirements
if (phpversion()*1 < 7.0) {
	$reqsPassed = false;
	array_push($reqsFailures, "phpVersion");
}

// Check we have a working session
if (!isset($_SESSION) || session_id() === "") {
	$reqsPassed = false;
	array_push($reqsFailures, "phpSession");
}

// Check we can read config settings
if (file_exists(dirname(__FILE__)."/../data/config-settings.php")) {
	include(dirname(__FILE__)."/../data/config-settings.php");
	if (!isset($ICEcoderSettings['versionNo'])) {
		$reqsPassed = false;
		array_push($reqsFailures, "phpReadFile");
	}
}

// If any of these failed, show requirements problem info screen
if (!$reqsPassed) {
// $t = $text['reqsIssue'];
?>
<html>
<head>
<title>ICEcoder <?php echo $ICEcoderSettings['versionNo'];?> : Requirements problem!</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link rel="stylesheet" type="text/css" href="lib/ice-coder.css?microtime=<?php echo microtime(true);?>">
<link rel="icon" type="image/png" href="favicon.png">
</head>

<body style="background-color: #181817" onLoad="setTimeout(function(){document.getElementById('screenContainer').style.opacity=1},50)">

<div class="screenContainer" id="screenContainer" style="background-color: #181817; opacity: 0; transition: opacity 0.1s ease-out">
	<div class="screenVCenter">
		<div class="screenCenter">
			<img src="images/ice-coder.png" alt="ICEcoder">
			<div class="version" style="margin-bottom: 22px">v <?php echo $ICEcoderSettings['versionNo'];?></div>

			<span style="display: inline-block; color: #fff">
		        	<b style="padding: 5px; background: #b00; color: #fff">Requirements problem!</b><br><br><br><br>
				Sorry, but ICEcoder has a problem<br>running on your server:<br><br><hr style="height: 1px; border: 0; background: #888"><br>
				<?php
				if (in_array("phpVersion", $reqsFailures)) {
					echo "Your PHP version is ".phpversion()."<br>and needs 7.0 or above<br><br>";
				}
				if (in_array("phpSession", $reqsFailures)) {
					echo "You don't appear to have a<br>working PHP session<br><br>";
				}
				if (in_array("phpReadFile", $reqsFailures)) {
					echo "You don't seem to be able<br>to read the config file<br><br>";
				}
				if (in_array("phpCreateConfig", $reqsFailures)) {
					echo "Cannot update config file:<br>data/".$configSettings."<br>Please check write permissions<br>on data dir and reload page<br><br>";
				}
				if (in_array("phpCreateSettings", $reqsFailures)) {
					echo "Couldn't create:<br>data/$settingsFile<br>Maybe you need write<br>permissions on the data dir?<br><br>";
				}
				if (in_array("phpUpdateSettings", $reqsFailures)) {
					echo "Can't update config file:<br>data/".$settingsFile."<br>Please check write permissions<br>on data dir and reload page<br><br>";
				}
				if (in_array("phpCreateSettingsFileAddr", $reqsFailures)) {
					echo "Couldn't create:<br>data/$settingsFileAddr<br>Maybe you need write<br>permissions on the data dir?<br><br>";
				}
				if (in_array("systemIPRestriction", $reqsFailures)) {
					echo "Not in permitted IPs list<br><br>";
				}
				?>
			</span>
		
		</div>
	</div>
</div>

</body>

</html>

<?php
exit;
}
?>
