<?php
include "headers.php";
include "settings.php";
$t = $text['auto-logout-warning'];
?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> auto-logout</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="auto-logout-warning.css?microtime=<?php echo microtime(true);?>">
</head>

<body class="auto-logout-warning">

<h1 id="title"><?php echo $t['Auto Logout Warning'];?></h1>

<?php echo $t['You will be...'];?> <span id="timeRemaning">60</span> <?php echo $t['seconds due to...'];?>

</body>

</html>
