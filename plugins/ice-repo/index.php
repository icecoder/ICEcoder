<?php include("settings.php"); ?>
<!DOCTYPE html>
<html>
<head>
<title>ICErepo v <?php echo $version;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="lib/base64.js"></script>
<script src="lib/github.js"></script>
<script src="ice-repo.js"></script>
<link rel="stylesheet" type="text/css" href="ice-repo.css">
</head>

<?php
if (isset($_GET['sessionLogin'])) {
?>

<form name="sessionLogin" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST">
	<b>Token:</b><br>
	<input type="text" name="token">
	<br><br>
	or:<br><br>
	<b>Username:</b><br>
	<input type="text" name="username"><br>
	<b>Password:</b><br>
	<input type="password" name="password"><br>
	<input type="submit">
</form>

<?php
} else {
?>
	<body style="margin: 0; overflow: hidden" onLoad="doRepo(get('repos').value)">
	
	<div id="loadingMask" class="blackMask" style="visibility: visible" onContextMenu="return false">
		<div class="popupVCenter">
			<div class="popup">
				<div class="circleOutside"></div>
				<div class="circleInside"></div>
				&nbsp;&nbsp;&nbsp;working...
			</div>
		</div>
	</div>

	<div class="header">
		<select name="repos" id="repos" onChange="doRepo(this.value)" style="margin: 20px 0 0 20px">
		<?php
		for ($i=0;$i<count($repos);$i+=3) {
			echo '<option id="repo'.($i/3).'" value="'.$repos[$i].'@'.$repos[$i+1].'"';
			echo $repos[$i+2]=="selected" ? ' selected' : '';
			echo '>'.$repos[$i]."</option>\n";
		}
		?>
		</select>
	
		<div class="pullGithubSel" onClick="pullContent('selected')">Pull selected from Github</div>
		<div class="version"><?php echo $version;?></div>
		<img src="images/ice-repo.gif" alt="ICErepo" class="logo">
	</div>

	<form name="showRepo" action="contents.php?username=<?php echo $username;?>&password=<?php echo $password;?>" target="repo" method="POST">
		<input type="hidden" name="repo" value="">
	</form>

	<iframe id="repo" class="repoFrame" frameborder="0"></iframe>
<?php
;};
?>
	
</body>

</html>