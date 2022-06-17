<?php
include "headers.php";
include "settings.php";

// Redirect up 2 dirs to get into ICEcoder (useful if we changed setting and refresh)
if (false === $ICEcoder['loginRequired']) {
	$tgtDir = dirname(dirname($_SERVER['REQUEST_URI']));
	header('Location: ' . $tgtDir);
	echo "<script>window.location = '" . $tgtDir . "';</script>";
	exit;
}

$t = $text['login'];

$settingPW = true === $ICEcoder["enableRegistration"] && (true === $ICEcoder["multiUser"] || "" === $ICEcoder["password"]);

// If multiUser, detect which users we have
if ($ICEcoder["multiUser"]) {		
	$configUsernames = [];
	$handle = opendir('../data/');
	while (false !== ($file = readdir($handle))) {
		if ($file !== "config-global.php" && 0 === strpos($file, "config-")) {
			$configUsernames[explode("-", $file)[1]] = true;
		}
	}
	closedir($handle);
}

$assetsPath = "assets" === $settingsClass->assetsRoot
    ? "../" . $settingsClass->assetsRoot
    : $settingsClass->assetsRoot
?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php
echo $ICEcoder["versionNo"] . " : ";
echo true === $settingPW ? "Setup" : "Login";
?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link rel="stylesheet" type="text/css" href="<?php echo $assetsPath;?>/css/resets.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" type="text/css" href="<?php echo $assetsPath;?>/css/icecoder.css?microtime=<?php echo microtime(true);?>">
<link rel="icon" type="image/png" href="<?php echo $assetsPath;?>/images/favicon.png">
</head>

<body style="background-color: #181817" onLoad="<?php if (false === isset($_GET["get"])) {$inputFocus = true === $ICEcoder["multiUser"] && (true === $ICEcoder["enableRegistration"] || 1 < count($configUsernames)) ? "username" : "password"; echo "document.settingsUpdate." . $inputFocus . ".focus(); ";}; ?>setTimeout(function(){document.getElementById('screenContainer').style.opacity = '1'}, 50)">

<div class="screenContainer" id="screenContainer" style="background-color: #181817; opacity: 0; transition: opacity 0.1s ease-out">
	<div class="screenVCenter">
		<div class="screenCenter">
		<img src="<?php echo $assetsPath;?>/images/icecoder.png" alt="ICEcoder">
		<div class="version" style="margin-bottom: 22px"><?php echo $ICEcoder["versionNo"];?></div>

		<form name="settingsUpdate" action="login.php" method="POST"<?php if (true === $settingPW) {?> onsubmit="return checkCanSubmit();"<?php } ?>>
		<?php
		if (true === $settingPW && false === $ICEcoder["multiUser"]) {
			echo '<div class="text adminUser">User: admin</div>';
		}
		?>
        <?php
		// Display username field if multiUser enabled
		if (true === $ICEcoder["multiUser"]) {
			// Also set value to "admin" if only 1 user (has to be admin)
			$showAdminValue = 1 === count($configUsernames) ? ' value="admin"' : '';
			echo '<input type="text" name="username"' . $showAdminValue . ' class="password" id="username" onkeydown="return checkUsernameKey(event.key)" onkeyup="checkUsername(this.value, true)" onchange="checkUsername(this.value, true)" onpaste="checkUsername(this.value, true)"><br><br>';
		};
		?>
		<input type="password" name="password" class="password" id="password"<?php
            if (true === $settingPW) {
                ?> onkeyup="checkCase(event); pwStrength(this.value)" onchange="pwStrength(this.value)" onpaste="pwStrength(this.value)"<?php
            } else {
                ?> onkeyup="checkCase(event)"<?php
            }
            ?>><div class="iconCapsLock" style="display: none" id="iconCapsLock" title="Caps lock on"><?php echo file_get_contents(dirname(__FILE__) . "/../assets/images/icons/alert-triangle.svg");?></div><br>
		<?php
		if (true === $settingPW) {
			echo '<div id="pwReqs">'.
				 '<div class="text" style="display: inline-block" id="pwChars">10+</div> &nbsp; ' .
				 '<div class="text" style="display: inline-block" id="pwUpper">upper</div> &nbsp; ' .
				 '<div class="text" style="display: inline-block" id="pwLower">lower</div> &nbsp; ' .
				 '<div class="text" style="display: inline-block" id="pwNum">number</div> &nbsp; ' .
				 '<div class="text" style="display: inline-block" id="pwSpecial">special</div>' .
				 '</div>';
		}
		?><br>
		<input type="submit" name="submit" value="<?php
			// Multi-user
			if ($ICEcoder["multiUser"]) {
				echo $ICEcoder["enableRegistration"] ? $t['set password'] . " / " . $t['login'] : $t['login'];
			// Single-user
			} else {
				echo $ICEcoder["enableRegistration"] && "" === $ICEcoder["password"] ? $t['set password'] : $t['login'];
			}; ?>" class="button">
		<?php
			if(empty($_SERVER['HTTPS'])) {
				echo '<div class="text">Using over non-https connection.<br>TLS is recommended!</div>';
			}
			if($ICEcoder["multiUser"] && $ICEcoder["enableRegistration"]){
				echo '<div class="text">' . $t['Registration mode enabled'] . '</div>';
			}
		?>
		<?php
		if ("" === $ICEcoder["password"] && false === $ICEcoder["multiUser"]) {
			echo '<div class="text" style="position: relative"><input type="checkbox" name="disableFurtherRegistration" value="true" style="position: absolute; margin: -1px 0 0 -20px" checked> ' . $t['disable further registrations'] . '</div>';
		}
		if ("" === $ICEcoder["password"] || true === $ICEcoder["multiUser"]) {
			$tickCheckUpdates = true === $ICEcoder['checkUpdates'] ? " checked" : "";
			echo '<div class="text" style="position: relative"><input type="checkbox" name="checkUpdates" value="true" style="position: absolute; margin: -1px 0 0 -20px"' . $tickCheckUpdates . '> ' . $t['auto-check for updates'] . '</div>';
		}
		if (false === $ICEcoder["multiUser"]) { echo '<div class="text"><a href="javascript:alert(\'' . $t['To put into...'] . '\'); document.settingsUpdate.' . $inputFocus . '.focus();">' . $t['multi-user'] . '?</a></div>';};
		?>
		<input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
		</form>
		</div>
	</div>
</div>

<?php
echo $systemClass->getDemoModeIndicator(true);
?>

<script>
// Get any elem by ID
const get = function(elem) {
	return document.getElementById(elem);
};

// Check keydown in username field meets simple rules (alphanums, underscore and hyphen only)
const checkUsernameKey = function(key) {
    return /[\w_\-]/g.test(key);
}

// Check username value meets simple rules (alphanums, underscore and hyphen only)
const checkUsername = function(username, amend) {
    // Amend username if OK to do this
    if (true === amend) {
        get("username").value = username.replace(/[^\w_\-]/g, "");
    }
    // Return a bool based on meeting the requirements
    return username.replace(/[^\w_\-]/g, "").length === username.length;
};

// Check password strength and color requirements not met
const pwStrength = function(pw) {
	// Set variables
    const hlCol = "rgba(0, 198, 255, 0.7)";
	let chars, upper, lower, num, special;

	// Test password for requirements
	chars = pw.length >= 10;
	upper = pw.replace(/[A-Z]/g, "").length < pw.length;
	lower = pw.replace(/[a-z]/g, "").length < pw.length;
	num = pw.replace(/[0-9]/g, "").length < pw.length;
	special = pw.replace(/[A-Za-z0-9]/g, "").length > 0;

	// Set colors based on each requirements
	get("pwChars").style.color = true === chars ? hlCol : "";
	get("pwUpper").style.color = true === upper ? hlCol : "";
	get("pwLower").style.color = true === lower ? hlCol : "";
	get("pwNum").style.color = true === num ? hlCol : "";
	get("pwSpecial").style.color = true === special ? hlCol : "";

	// Return a bool based on meeting the requirements
	return (true === chars && true === upper && true === lower && true === num && true === special);
};

const checkCase = function(evt) {
    const key = evt.keyCode ?? evt.which ?? evt.charCode;

    // Not caps lock key
    if (20 !== key) {
        get("iconCapsLock").style.display = true === evt.getModifierState("CapsLock")
            ? "inline-block"
            : "none";
    }
};

// Check if we can submit, else shake requirements
const checkCanSubmit = function() {
    <?php
    // Check username field if multiUser enabled
    if (true === $ICEcoder["multiUser"]) {
    ?>// Username isn't simple, can't submit
    if(false === checkUsername(get("username").value, false)) {
        return false;
    }
    <?php
    }
    ?>// Password isn't strong enough, shake requirements
	if(false === pwStrength(get("password").value)) {
		var posArray = [24, -24, 12, -12, 6, -6, 3, -3, 0];
		var pos = -1;
		var anim = setInterval(function() {
			if (pos < posArray.length) {
				pos++;
				get("pwReqs").style.marginLeft = get("password").style.marginLeft = posArray[pos] + "px";
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
