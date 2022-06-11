<?php
include "headers.php";
include "settings.php";
$t = $text['help'];

$assetsPath = "assets" === $settingsClass->assetsRoot
    ? "../" . $settingsClass->assetsRoot
    : $settingsClass->assetsRoot;
?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> help</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="<?php echo $assetsPath;?>/css/resets.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" type="text/css" href="<?php echo $assetsPath;?>/css/help.css?microtime=<?php echo microtime(true);?>">
</head>

<body class="help" onkeyup="parent.ICEcoder.handleModalKeyUp(event, 'help')" onload="this.focus();">

<h1 id="title"><?php echo $t['shortcuts'];?></h1>

<?php $isMac = -1 < strpos($_SERVER['HTTP_USER_AGENT'], "Macintosh") ? true : false;?>
<div style="display: inline-block; width: 385px; margin-right: 20px">

	<h2><?php echo $t['Within document'];?></h2>
	<!-- This can only be CTRL+space as Cmd+space is a reserved apple shortcut -->
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> <?php echo $t['Space'];?></span> <span class="shortcut"><?php echo $t['Autocomplete add snippet'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> <?php echo $t['Click'];?> <span class="plus"><?php echo $t['or'];?></span> Alt <span class="plus">+</span> <?php echo $t['Drag'];?></span> <span class="shortcut"><?php echo $t['Multiple select'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> &uarr;</span> <span class="shortcut"><?php echo $t['Move line up'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> &darr;</span> <span class="shortcut"><?php echo $t['Move line down'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> +</span> <span class="shortcut"><?php echo $t['Duplicate lines'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> -</span> <span class="shortcut"><?php echo $t['Remove lines'];?></span><br>
	<span class="key">Shift <span class="plus">+</span> Enter</span> <span class="shortcut"><?php echo $t['Insert line before'];?></span><br>
	<span class="key">Alt <span class="plus">+</span> Enter</span> <span class="shortcut"><?php echo $t['Insert line after'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> i</span> <span class="shortcut"><?php echo $t['Search for selected'];?> <span class="info" title="Popups need to be enabled">[?]</span></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> j</span> <span class="shortcut"><?php echo $t['Jump to definition'];?></span><br>
	<span class="key">Esc</span> <span class="shortcut"><?php echo $t['Comment uncomment'];?></span><br>
	<span class="key">Tab</span> <span class="shortcut"><?php echo $t['Insert tab indent'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> [</span> <span class="shortcut"><?php echo $t['Insert less'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> ]</span> <span class="shortcut"><?php echo $t['Insert more'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Alt <span class="plus">+</span> d</span> <span class="shortcut"><?php echo $t['Wrap with div'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Alt <span class="plus">+</span> s</span> <span class="shortcut"><?php echo $t['Wrap with span'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Alt <span class="plus">+</span> p</span> <span class="shortcut"><?php echo $t['Wrap unwrap p'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Alt <span class="plus">+</span> a</span> <span class="shortcut"><?php echo $t['Wrap unwrap a'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Alt <span class="plus">+</span> 1, 2 <?php echo $t['or'];?> 3</span> <span class="shortcut"><?php echo $t['Wrap unwrap h1...'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Alt <span class="plus">+</span> Enter</span> <span class="shortcut"><?php echo $t['End line with...'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Right click</span> <span class="shortcut"><?php echo $t['Jump to'];?></span><br>
	<span class="key">F1 <span class="plus"><?php echo $t['or'];?></span> Fn <span class="plus">+</span> F1</span><span class="shortcut"><?php echo $t['Zoom out'];?></span><br><br>

	<h2><?php echo $t['On Tabs'];?></h2>
	<span class="key"><?php echo $t['Middle click'];?></span> <span class="shortcut"><?php echo $t['Close tab'];?></span><br>
	<span class="key"><?php echo $t['Double click'];?></span> <span class="shortcut"><?php echo $t['Contract expand file...'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Backspace</span> <span class="shortcut"><?php echo $t['Jump to previous tab'];?></span><br><br>
</div>

<div style="display: inline-block; width: 385px">
	<h2><?php echo $t['Within file manager'];?></h2>
	<span class="key"><?php echo $t['Left click'];?></span> <span class="shortcut"><?php echo $t['Select file folder'];?></span><br>
	<span class="key"><?php echo $t['Double click tap...'];?></span> <span class="shortcut"><?php echo $t['Open file'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> <?php echo strtolower($t['Left click']);?></span> <span class="shortcut"><?php echo $t['Multiple select'];?></span><br>
	<span class="key">Shift <span class="plus">+</span> <?php echo strtolower($t['Left click']);?></span> <span class="shortcut"><?php echo $t['Range select'];?></span><br>
	<span class="key"><?php echo $t['Right click'];?></span> <span class="shortcut"><?php echo $t['Options for selected'];?></span><br>
	<span class="key">Delete</span> <span class="shortcut"><?php echo $t['Delete selected'];?></span><br>
	<span class="key">&larr; &rarr; &uarr; &darr;, Enter</span> <span class="shortcut">Move around file manager, open</span><br><br>

	<h2><?php echo $t['Anywhere'];?></h2>
	<span class="key"><?php echo $t['Middle scrollwheel'];?></span> <span class="shortcut"><?php echo $t['Next previous tab'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> &rarr;</span> <span class="shortcut"><?php echo $t['Next tab'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> &larr;</span> <span class="shortcut"><?php echo $t['Previous tab'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> +</span> <span class="shortcut"><?php echo $t['New tab'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> -</span> <span class="shortcut"><?php echo $t['Close current tab'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> o</span> <span class="shortcut"><?php echo $t['Open file prompt'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> f</span> <span class="shortcut"><?php echo $t['Find'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> g</span> <span class="shortcut"><?php echo $t['Previous'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> l</span> <span class="shortcut"><?php echo $t['Focus on Go...'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> s</span> <span class="shortcut"><?php echo $t['Save'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Shift <span class="plus">+</span> s</span> <span class="shortcut"><?php echo $t['Save as'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Enter</span> <span class="shortcut"><?php echo $t['View webpage'];?> <span class="info" title="Popups need to be enabled">[?]</span></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> `</span> <span class="shortcut"><?php echo $t['Contract expand file...'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> .</span> <span class="shortcut"><?php echo $t['Fold unfold current...'];?></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Alt <span class="plus">+</span> Shift</span> <span class="shortcut">Focus on file manager / content<!--<?php echo $t['Refocus on document'];?>//--></span><br>
	<span class="key">Esc</span> <span class="shortcut"><?php echo $t['Cancel tasks'];?></span><br>

</div>

</body>

</html>
