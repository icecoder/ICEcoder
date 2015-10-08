<?php
// Load common functions
include("headers.php");
include_once("settings-common.php");
$text = $_SESSION['text'];
$t = $text['backup-versions'];

$file = str_replace("|","/",xssClean($_GET['file'],'html'));
$fileCountInfo = getVersionsCount(dirname($file),basename($file));
$versions = $fileCountInfo['count'];
?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> backup version control</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="backup-versions.css?microtime=<?php echo microtime(true);?>">
</head>

<body class="backup-versions">

<h1 id="title"><?php echo $versions." ".($versions != 1 ? $t["backups"] : $t["backup"])." ".$t['available for'].":";?></h1>
<h2><?php echo $file;?></h2>

<br>
<?php
$dateCounts = $fileCountInfo['dateCounts'];
$displayVersions = $versions;

foreach ($dateCounts as $key => $value) {
	echo "<b>".date("jS M Y",strtotime($key))." (".$value." ".($value != 1 ? $t["backups"] : $t["backup"]).")</b>";
	echo '<br>';
	for ($j=0; $j<$value; $j++) {
		echo "Backup ".$displayVersions.'<br>';
		$displayVersions--;
	}
	echo '<br>';
}
?>

</body>

</html>