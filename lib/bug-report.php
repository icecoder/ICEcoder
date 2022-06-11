<?php
include "headers.php";
include "settings.php" ;
$t = $text['bug-report'];

$assetsPath = "assets" === $settingsClass->assetsRoot
    ? "../" . $settingsClass->assetsRoot
    : $settingsClass->assetsRoot;
?>
<!DOCTYPE html>

<html>
<head>
    <title>ICEcoder <?php echo $ICEcoder["versionNo"];?> bug report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" type="text/css" href="<?php echo $assetsPath;?>/css/resets.css?microtime=<?php echo microtime(true);?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $assetsPath;?>/css/bug-report.css?microtime=<?php echo microtime(true);?>">
</head>

<body class="bug-report">

<h1 id="title"><?php echo $t['Bug Report'];?></h1>
<pre>
<?php
echo trim($settingsClass->serializedFileData("get", $docRoot . $ICEcoderDir . "/data/bug-report.php"));
?>
</pre>

</body>

</html>
