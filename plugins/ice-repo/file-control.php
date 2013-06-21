<?php include("settings.php"); ?>
<?php
//$repoPath = strClean($_POST['repoPath']);
$gitRepo = strClean($_POST['gitRepo']);
$path = strClean($_POST['path']);
$rowID = strClean($_POST['rowID']);
$repo = strClean($_POST['repo']);
$dir = strClean($_POST['dir']);
$action = str_replace("PULL:","",str_replace("SAVEPULLS:","",strClean($_POST['action'])));
$rowIDArray = explode(",",$rowID);
$repoArray = explode(",",$repo);
$dirArray = explode(",",$dir);
$actionArray = explode(",",$action);
?>
<!DOCTYPE html>
<html>
<head>
<title>ICErepo v<?php echo $version;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="lib/underscore-min.js"></script>
<script src="lib/base64.js"></script>
<script src="lib/github.js"></script>
<script src="lib/difflib.js"></script>
<script src="ice-repo.js"></script>
<link rel="stylesheet" type="text/css" href="ice-repo.css">
</head>

<body>
	
<script>
	fullRepoPath='<?php echo $repo;?>';
	gitRepo='<?php echo $gitRepo;?>';
	var github = new Github(<?php
	if ($token!="") {
		echo '{token: "'.$token.'", auth: "oauth"}';
	} else{
		echo '{username: "'.$username.'", password: "'.$password.'", auth: "basic"}';
	}?>);
	repoUser = gitRepo.split('/')[0];
	repoName = gitRepo.split('/')[1];
	filePath = fullRepoPath.replace(repoUser+"/"+repoName+"/","");
	var repo = github.getRepo(repoUser,repoName);
</script>

<?php if ($_POST['action']=="view") {?>
	<form name="fcForm">
		<textarea name="fileContents"><?php echo file_exists($dir) ? htmlentities(file_get_contents($dir)) : ''; ?></textarea>
	</form>
	<script>
	rowID = <?php echo $rowID; ?>;
		
	baseTextName = "Server:     <?php echo str_replace($_SERVER['DOCUMENT_ROOT']."/","",$dir);?>     ";
	newTextName = "Github:     <?php echo $repo;?>";
	window.onLoad = sendData(baseTextName,newTextName);
	</script>
<?php } else if (substr($_POST['action'],0,5)=="PULL:") { ?>
	<form name="fcForm" action="file-control.php?username=<?php echo $username;?>&password=<?php echo $password;?>" method="POST">
		<?php
		echo '<input type="hidden" name="rowID" value="'.$rowID.'">';
		echo '<input type="hidden" name="repo" value="'.$repo.'">';
		echo '<input type="hidden" name="dir" value="'.$dir.'">';
		echo '<input type="hidden" name="action" value="SAVEPULLS:'.$action.'">';
		echo '<input type="hidden" name="path" value="'.$path.'">';
		for ($i=0;$i<count($rowIDArray);$i++) {
			if ($repoArray[$i]!="") {
				echo '<textarea name="repoContents'.$rowIDArray[$i].'"></textarea>';
			}
		}
		?>
	</form>
	<script>
	rowIDArray = [<?php echo implode(",", $rowIDArray);?>];
	repoArray = [<?php echo "'".implode("','", $repoArray)."'";?>];
	dirArray = [<?php echo "'".implode("','", $dirArray)."'";?>];
	actionArray = [<?php echo "'".implode("','", $actionArray)."'";?>];
	window.onLoad = getData();
	</script>
<?php } else if (substr($_POST['action'],0,10)=="SAVEPULLS:") {?>
	<script>
	<?php
		for ($i=0;$i<count($rowIDArray);$i++) {
			if ($actionArray[$i]!="new") {
				$dirs = explode("/",$repoArray[$i]);
				$relDir = "";
				for ($j=0;$j<count($dirs)-1;$j++) {
					$relDir .= "/".$dirs[$j];
					if (!is_dir($path.$relDir)) {
						mkdir($path.$relDir, 0755);
					}
				}
				$fh = fopen($path."/".$repoArray[$i], 'w') or die("alert('Sorry, there was a problem pulling ".$repoArray[$i].". Either the file is unavailable on Github or server permissions aren\'t allowing it to be created/updated.');get('loadingMask','top').style.display='none';");
				fwrite($fh, $_POST['repoContents'.$rowIDArray[$i]]);
				fclose($fh);
				echo "hideRow(".$rowIDArray[$i].");top.newCount--;";
			} else {
				is_dir($dir) ? $success = rmdir($dir) : $success = unlink($dir);
				if (!$success) {
					echo "alert('Sorry, couldn\'t delete ".$dir."\\n\\n";
					echo "Maybe you need to give file permissions for it to be deleted?');";
				} else {
					echo "hideRow(".$rowIDArray[$i].");top.deletedCount--;";
				}
			}
		}
		echo "get('loadingMask','top').style.display = 'none';";
	?>
	</script>
<?php } else { ?>
	<form name="fcForm">
	<?php
		for ($i=0;$i<count($rowIDArray);$i++) {
			if ($dirArray[$i]!="") {
				echo '<textarea name="fileContents'.$rowIDArray[$i].'">';
				echo htmlentities(file_get_contents($dirArray[$i]));
				echo '</textarea>';
			}
		}
	?>
	</form>
	<script>
	rowIDArray = [<?php echo implode(",", $rowIDArray);?>];
	repoArray = [<?php echo "'".implode("','", $repoArray)."'";?>];
	dirArray = [<?php echo "'".implode("','", $dirArray)."'";?>];
	actionArray = [<?php echo "'".implode("','", $actionArray)."'";?>];
	window.onLoad = startProcess();
	</script>
<?php } ?>
	
</body>
	
</html>