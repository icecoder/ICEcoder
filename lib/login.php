<?php
include("headers.php");
include("settings.php");
$t = $text['login'];
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

		<form name="settingsUpdate" action="login.php" method="POST">
        <?php
		if ($ICEcoder["multiUser"]) {echo '		<input type="text" name="username" class="password"><br><br>'.PHP_EOL;};
		?>
		<input type="password" name="password" class="password"><br><br>
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

</body>

</html>
