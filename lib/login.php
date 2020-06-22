<?php
include("headers.php");
include("settings.php");
$t = $text['login'];

$settingPW = $ICEcoderSettings["enableRegistration"] && ($ICEcoder["multiUser"] || $ICEcoder["password"] == "");
?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php
echo $ICEcoder["versionNo"]." : ";
echo $ICEcoder["password"] == "" && !$ICEcoder["multiUser"] ? "Setup" : "Login";
?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link rel="stylesheet" type="text/css" href="ice-coder.css?microtime=<?php echo microtime(true);?>">
<link rel="icon" type="image/png" href="../favicon.png">
</head>

<body style="background-color: #181817" onLoad="<?php if (!isset($_GET["get"])) {$inputFocus = $ICEcoder["multiUser"] ? "username" : "password"; echo "document.settingsUpdate.".$inputFocus.".focus(); ";}; ?>setTimeout(function(){document.getElementById('screenContainer').style.opacity=1},50)">

<div class="screenContainer" id="screenContainer" style="background-color: #181817; opacity: 0; transition: opacity 0.1s ease-out">
	<div class="screenVCenter">
		<div class="screenCenter">
		<img src="../images/ice-coder.png" alt="ICEcoder">
		<div class="version" style="margin-bottom: 22px">v <?php echo $ICEcoder["versionNo"];?></div>

		<form name="settingsUpdate" action="login.php" method="POST"<?php if($settingPW) {?> onsubmit="return checkCanSubmit();"<?php } ?>>
        <?php
		if ($ICEcoder["multiUser"]) {echo '		<input type="text" name="username" class="password"><br><br>'.PHP_EOL;};
		?>
		<input type="password" name="password" class="password" id="password"<?php if($settingPW) {?> onkeyup="pwStrength(this.value)" onchange="pwStrength(this.value)" onpaste="pwStrength(this.value)"<?php } ?>><br>
		<?php
		if ($settingPW) {
			echo    '<div id="pwReqs">'.
				'<div class="text" style="display: inline-block" id="pwChars">10+</div> &nbsp; '.
				'<div class="text" style="display: inline-block" id="pwUpper">upper</div> &nbsp; '.
				'<div class="text" style="display: inline-block" id="pwLower">lower</div> &nbsp; '.
				'<div class="text" style="display: inline-block" id="pwNum">number</div> &nbsp; '.
				'<div class="text" style="display: inline-block" id="pwSpecial">special</div>'.
				'</div>';
		}
		?><br>
		<input type="submit" name="submit" value="<?php
			// Multi-user
			if ($ICEcoder["multiUser"]) {
				echo $ICEcoderSettings["enableRegistration"] ? $t['set password']." / ".$t['login'] : $t['login'];
			// Single-user
			} else {
				echo $ICEcoderSettings["enableRegistration"] && $ICEcoder["password"] == "" ? $t['set password'] : $t['login'];
			}; ?>" class="button">
		<?php
			if(empty($_SERVER['HTTPS'])) {
				echo '<div class="text">Using over non-https connection.<br>SSL is recommended!</div>';
			}
			if($ICEcoder["multiUser"] && $ICEcoderSettings["enableRegistration"]){
				echo '<div class="text"><a href="javascript:alert(\''.$t['To disable registration...'].'\')">'.$t['Registration mode enabled'].'</a></div>';
			}
		?>
		<?php
		if ($ICEcoder["password"] == "" && !$ICEcoder["multiUser"]) {
			echo '<div class="text" style="position: relative"><input type="checkbox" name="disableFurtherRegistration" value="true" style="position: absolute; margin: -1px 0 0 -20px" checked> '.$t['disable further registrations'].'</div>';
		}
		if ($ICEcoder["password"] == "" || $ICEcoder["multiUser"]) {
			echo '<div class="text" style="position: relative"><input type="checkbox" name="checkUpdates" value="true" style="position: absolute; margin: -1px 0 0 -20px" checked> '.$t['auto-check for updates'].'</div>';
		}
		if (!$ICEcoder["multiUser"]) { echo '<div class="text"><a href="javascript:alert(\''.$t['To put into...'].'\')">'.$t['multi-user'].'?</a></div>';};
		?>
		<input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
		</form>
		</div>
	</div>
</div>

<script>
// Get any elem by ID
var get = function(elem) {
	return top.document.getElementById(elem);
};

// Check password strength and color requirements not met
var pwStrength = function(pw) {
	// Set variables
	var chars, upper, lower, num, special;

	// Test password for requirements
	chars = pw.length >= 10;
	upper = pw.replace(/[A-Z]/g, "").length < pw.length;
	lower = pw.replace(/[a-z]/g, "").length < pw.length;
	num = pw.replace(/[0-9]/g, "").length < pw.length;
	special = pw.replace(/[A-Za-z0-9]/g, "").length > 0;

	// Set colors based on each requirements
	get("pwChars").style.color = chars ? "rgba(0,198,255,0.7)" : "";
	get("pwUpper").style.color = upper ? "rgba(0,198,255,0.7)" : "";
	get("pwLower").style.color = lower ? "rgba(0,198,255,0.7)" : "";
	get("pwNum").style.color = num ? "rgba(0,198,255,0.7)" : "";
	get("pwSpecial").style.color = special ? "rgba(0,198,255,0.7)" : "";

	// Return a bool based on meeting the requirements
	return (chars && upper && lower && num && special);
};

// Check if we can submit, else shake requirements
var checkCanSubmit = function() {
	// Password isn't strong enough, shake requirements
	if(!pwStrength(get("password").value)) {
		var posArray = [6, -6, 3, -3, 0];
		var pos = -1;
		var anim = setInterval(function() {
			if (pos < posArray.length) {
				pos++;
				get("pwReqs").style.marginLeft = posArray[pos] + "px";
			} else {
				clearInterval(anim);
			}
		}, 50);
		return false;
	}
	return true;
}
</script>

</body>

</html>
