<?php
include("headers.php");
include("settings.php");
?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> help</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="help.css">
</head>

<body class="help">

<h1 id="title">shortcuts</h1>

<?php $isMac = strpos($_SERVER['HTTP_USER_AGENT'], "Macintosh")>-1 ? true : false;?>
<div style="display: inline-block; width: 365px; margin-right: 20px">
	<h2>Within document</h2>
	<!-- This can only be CTRL+space as Cmd+space is a reserved apple shortcut -->
	<span class="key">Ctrl <span class="plus">+</span> Space</span> <span class="shortcut">Autocomplete  / add snippet</span><br>
	<span class="key">Ctrl <span class="plus">+</span> Click <span class="plus">or</span> Alt <span class="plus">+</span> Drag</span> <span class="shortcut">Multiple select</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> &uarr;</span> <span class="shortcut">Move line up</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> &darr;</span> <span class="shortcut">Move line down</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> +</span> <span class="shortcut">Duplicate line(s)</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> -</span> <span class="shortcut">Remove line(s)</span><br>
	<span class="key">Shift <span class="plus">+</span> Enter</span> <span class="shortcut">Insert line before</span><br>
	<span class="key">Alt <span class="plus">+</span> Enter</span> <span class="shortcut">Insert line after</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> i</span> <span class="shortcut">Search for selected <span class="info" title="Popups need to be enabled">[?]</span></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> j</span> <span class="shortcut">Jump to definition / jump back</span><br>
	<span class="key">Esc</span> <span class="shortcut">Comment / uncomment</span><br>
	<span class="key">Tab</span> <span class="shortcut">Insert tab / indent selected</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Alt <span class="plus">+</span> d</span> <span class="shortcut">Wrap with &lt;div&gt;</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Alt <span class="plus">+</span> s</span> <span class="shortcut">Wrap with &lt;span&gt;</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Alt <span class="plus">+</span> p</span> <span class="shortcut">Wrap / unwrap with &lt;p&gt;</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Alt <span class="plus">+</span> a</span> <span class="shortcut">Wrap / unwrap with &lt;a&gt;</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Alt <span class="plus">+</span> b</span> <span class="shortcut">Wrap / unwrap with &lt;b&gt;</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Alt <span class="plus">+</span> i</span> <span class="shortcut">Wrap / unwrap with &lt;i&gt;</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Alt <span class="plus">+</span> g</span> <span class="shortcut">Wrap / unwrap with &lt;strong&gt;</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Alt <span class="plus">+</span> e</span> <span class="shortcut">Wrap / unwrap with &lt;em&gt;</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Alt <span class="plus">+</span> nums 1 - 3</span> <span class="shortcut">Wrap/unwrap with &lt;h1&gt; - &lt;h3&gt;</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Alt <span class="plus">+</span> Enter</span> <span class="shortcut">End line with &lt;br&gt;</span><br><br>
	<h2>On Tabs</h2>
	<span class="key">Middle click</span> <span class="shortcut">Close tab</span><br>
</div>

<div style="display: inline-block; width: 365px">
	<h2>Within file manager</h2>
	<span class="key">Left click</span> <span class="shortcut">Select file / folder</span><br>
	<span class="key">Double click / click (mobile)</span> <span class="shortcut">Open file</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> left click</span> <span class="shortcut">Multiple select</span><br>
	<span class="key">Shift <span class="plus">+</span> left click</span> <span class="shortcut">Range select</span><br>
	<span class="key">Right click</span> <span class="shortcut">Options for selected</span><br>
	<span class="key">Delete</span> <span class="shortcut">Delete selected</span><br><br>

	<h2>Anywhere</h2>
	<span class="key">Middle scrollwheel</span> <span class="shortcut">Next/previous tab</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> &rarr;</span> <span class="shortcut">Next tab</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> &larr;</span> <span class="shortcut">Previous tab</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> +</span> <span class="shortcut">New tab</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> -</span> <span class="shortcut">Close current tab</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> o</span> <span class="shortcut">Open file prompt</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> f</span> <span class="shortcut">Find</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> g</span> <span class="shortcut">Focus on Go to line input</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> s</span> <span class="shortcut">Save</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Shift <span class="plus">+</span> s</span> <span class="shortcut">Save as...</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> Enter</span> <span class="shortcut">View webpage <span class="info" title="Popups need to be enabled">[?]</span></span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> `</span> <span class="shortcut">Contract/expand file manager</span><br>
	<span class="key"><?php echo $isMac ? "Cmd" : "Ctrl";?> <span class="plus">+</span> .</span> <span class="shortcut">Fold/unfold current line</span><br>
	<span class="key">Space</span> <span class="shortcut">Refocus on document</span><br>
	<span class="key">Esc</span> <span class="shortcut">Cancel tasks</span><br><br>
</div>

</body>

</html>