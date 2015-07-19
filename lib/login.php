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
	if (isset($_GET["get"]) && $_GET["get"]=="code") {
		?>
		<span style="color: #fff"><h1>Thanks for trialling ICEcoder!</h1><b>Please donate on the website to continue using it.</b><br><br>
		<h2>Step 1: Donate</h2><br><br>
		<a href="https://icecoder.net/?display=trialDonateScreen" target="_blank" style="background: #097695; color: #fff; padding: 10px; text-decoration: none; font-size: 17px; font-weight: bold; border-radius: 4px; cursor: pointer">Donate now</a><br><br><br>
		<h2>Step 2: Enter email address &amp; code</h2><br>
		<?php if (isset($_GET["success"]) && $_GET["success"]=="no") {echo "Sorry, that doesn't seem to be correct.<br>Please check your emailed details and try again.<br><br>";};?>
		<?php if ($ICEcoder["multiUser"]) {echo '		<input type="text" name="username" value="Username" onfocus="origValue=\'Username\';if(this.value==origValue){this.value=\'\';};" onblur="if(this.value==\'\'){this.value=origValue;};" class="password"><br><br>'.PHP_EOL;};?>
		<input type="email" name="email" value="Email" onfocus="origValue='Email';if(this.value==origValue){this.value='';};" onblur="if(this.value==''){this.value=origValue;};" class="password"><br><br>
		<input type="text" name="code" value="Code" onfocus="origValue='Code';if(this.value==origValue){this.value='';};" onblur="if(this.value==''){this.value=origValue;};" class="password"><br><br>
		<input type="submit" name="submit" value="Unlock ICEcoder" style="background: #097695; color: #fff; border: 0; padding: 10px; text-decoration: none; font-size: 17px; font-weight: bold; border-radius: 4px; cursor: pointer"><br><br><br>
		Future development relies on your donation, to keep making awesome new features.<br><br>
		Many thanks.</span>
		<?php
	} else {
		if (isset($_GET["message"]) && $_GET["message"]=="trialDonateThanks") {echo '<span style="color: #fff"><b>Thank you very much for your donation!</b><br><br>Your details have been accepted and<br>ICEcoder is now fully unlocked.</span><br><br><br>';};
		if ($ICEcoder["multiUser"]) {echo '		<input type="text" name="username" class="password"><br><br>'.PHP_EOL;};	
		?>
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
	}
		?>
		<input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
		</form>
		</div>
	</div>
</div>

</body>

</html>