<?php include("lib/settings.php");?>
<!DOCTYPE html>

<html style="margin: 0">
<head>
<title>ICEcoder v <?php echo $ICEcoder["versionNo"];?> snippet display</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<style type="text/css">
body {background: #fff}
</style>
</head>

<body>

<?php
$snippets = array(
array(
	"name"		=> "Function",
	"replace"	=> "f ",
	"with"		=> "function ::var::() {\\n\\t::|::\\n}"
),
array(
	"name"		=> "If",
	"replace"	=> "if",
	"with"		=> "if (::|::) {\\n\\t\\n}"
)
);
?>

<?php
for ($i=0;$i<count($snippets);$i++) {
	echo '<div style="cursor: pointer" onClick="top.ICEcoder.doSnippet(\''.$snippets[$i]['replace'].'\',\''.$snippets[$i]['with'].'\')">'.$snippets[$i]['name'].'</div>';
}
;?>

</script>

</body>

</html>