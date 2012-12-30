<?php include("lib/settings.php");?>
<!DOCTYPE html>

<html style="margin: 0">
<head>
<title>ICEcoder v <?php echo $ICEcoder["versionNo"];?> snippet display</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<style type="text/css">
body {font-family: arial; font-size: 10px; background: #ccc}
</style>
</head>

<body style="margin: 5px">

<table border="0" cellpadding="0" cellspacing="0" width="500">
<tr valign="top"><td style="padding-bottom: 5px; font-size: 11px"><b>abbreviation plus CTRL+space</td><td style="font-size: 11px"><b>produces</b></td></tr>
<tr valign="top"><td>f name</td><td style="padding-bottom: 5px">function name() {...}</td></tr>
<tr valign="top"><td>if</td><td style="padding-bottom: 5px">if () {...}</td></tr>
<tr valign="top"><td>for</td><td style="padding-bottom: 5px">for (var i=0; i<; i++) {...}</td></tr>
</table>
<br><br>
Tip: If you have Emmet installed, also try tab key after your abbreviation
</script>

<span onClick="top.ICEcoder.removeSnippet()" style="position: absolute; top: 5px; right: 5px; height: 11px; background: #444; margin: 1px 0 0 5px; border-radius: 6px; cursor: pointer"><img src="../images/nav-close.gif"></span>

</body>

</html>