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
<link rel="stylesheet" type="text/css" href="ice-coder.css">
<link rel="icon" type="image/png" href="../favicon.png">
</head>

<body onLoad="document.settingsUpdate.<?php echo $ICEcoder["multiUser"] ? "username" : "password";?>.focus()">
	
<div class="screenContainer" style="background-color: #141414">
	<div class="screenVCenter">
		<div class="screenCenter">
		<img src="../images/ice-coder.png" alt="ICEcoder">
		<div class="version" style="margin-bottom: 22px">v <?php echo $ICEcoder["versionNo"];?></div>
		<form name="settingsUpdate" action="login.php" method="POST">
<?php if ($ICEcoder["multiUser"]) {echo '		<input type="text" name="username" class="password"><br><br>'.PHP_EOL;};?>
		<input type="password" name="password" class="password"><br><br>
		<input type="submit" name="submit" value="<?php if ($ICEcoder["multiUser"] && $ICEcoderSettings["enableRegistration"]) {echo $t['set password']." / ".$t['login'];} else {echo $ICEcoder["password"] == "" ? $t['set password'] : $t['login'];}; ?>" class="button">
		<?php
			if(empty($_SERVER['HTTPS'])) {
				echo '<div class="text">Using over non-https connection.<br>SSL is recommended!</div>';
			}
			if($ICEcoder["multiUser"] && $ICEcoderSettings["enableRegistration"]){
				echo '<div class="text"><a href="javascript:alert(\''.$t['To disable registration...'].'\')">'.$t['Registration mode enabled'].'</a></div>';
			}
		?>
		<?php
		if ($ICEcoder["password"] == "" || $ICEcoder["multiUser"]) {
			echo '<div class="text"><input type="checkbox" name="checkUpdates" value="true" checked> '.$t['auto-check for updates'].'</div>';
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