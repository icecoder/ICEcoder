<?php
include(dirname(__FILE__)."/headers.php");
include(dirname(__FILE__)."/settings.php");


function proc_open_enabled() {
  $disabled = explode(',', ini_get('disable_functions'));
  return !in_array('proc_open', $disabled);
}

if(!proc_open_enabled()) {
    exit("<span style=\"color: #fff\">Sorry but you can't use this terminal if your proc_open is disabled</span>\n\n");
}

$aliases = array(
	'la' 	=> 'ls -la',
	'll' 	=> 'ls -lvhF',
);

// Get current working dir
$user = str_replace("\n","",shell_exec("whoami"));
$cwd = getcwd();

// If we have a command
if(!empty($_REQUEST['command'])) {
	// Strip any slashes from it
	if(get_magic_quotes_gpc()) {
		$_REQUEST['command'] = stripslashes($_REQUEST['command']);
	}

	// Begin output with prompt and user command
	$output = '<div class="commandLine"><div class="user">&nbsp;&nbsp;'.$user.'&nbsp;</div>'.
			'<div class="path">&nbsp;'.$cwd.'&nbsp;</div> : '.date("H:m:s").
			'<br>'.
			'<div class="promptVLine"></div><div class="promptHLine">─<div class="promptArrow">▶</div></div> '.$_REQUEST['command'].'</div><br><br>';
}

// If in demo mode, display message and go no further
if ($demoMode) {
	$output .= "Sorry, shell usage not enabled in demo mode\n\n";
	echo $output;
	exit;
}

// If command contains cd but no dir
if (preg_match('/^[[:blank:]]*cd[[:blank:]]*$/', @$_REQUEST['command'])) {
	$_SESSION['cwd'] = getcwd(); //dirname(__FILE__);
// Else cd to a dir
} elseif (preg_match('/^[[:blank:]]*cd[[:blank:]]+([^;]+)$/', @$_REQUEST['command'], $regs)) {
	// The current command is 'cd', which we have to handle as an internal shell command
	// Absolute/relative path ?
	($regs[1][0] == '/') ? $newDir = $regs[1] : $newDir = $_SESSION['cwd'].'/'.$regs[1];

	// Tidy up appearance on /./
	while (strpos($newDir, '/./') !== false) {
		$newDir = str_replace('/./', '/', $newDir);
	}
	// Tidy up appearance on //
	while (strpos($newDir, '//') !== false) {
		$newDir = str_replace('//', '/', $newDir);
	}
	// Tidy up appearance on other variations
	while (preg_match('|/\.\.(?!\.)|', $newDir)) {
		$newDir = preg_replace('|/?[^/]+/\.\.(?!\.)|', '', $newDir);
	}

	// Empty dir
	if(empty($newDir)) {
		$newDir = "/";
	}

	// Test if we could change to that dir, else display error
	(@chdir($newDir)) ? $_SESSION['cwd'] = $newDir : $output .= "\n\nCould not change to: $newDir\n\n";
} else {
	// The command is not a 'cd' command, so we execute it after
	// changing the directory and save the output.
	chdir($_SESSION['cwd']);

	// Alias expansion
	$length = strcspn(@$_REQUEST['command'], " \t");
	$token = substr(@$_REQUEST['command'], 0, $length);
	if (isset($aliases[$token])) {
		$_REQUEST['command'] = $aliases[$token].substr($_REQUEST['command'], $length);
	}

	// Open a proc with array and $io return
	$p = proc_open(
		@$_REQUEST['command'],
		array(
			1 => array('pipe', 'w'),
			2 => array('pipe', 'w')
		),
		$io
	);
    
	// Read output sent to stdout
	while (!feof($io[1])) {   /// this will return always false ... and will loop forever until "fork: retry: no child processes" will show if proc_open is disabled;
		$output .= htmlspecialchars(fgets($io[1]),ENT_COMPAT, 'UTF-8');
	}
	// Read output sent to stderr
	while (!feof($io[2])) { 
		$output .= htmlspecialchars(fgets($io[2]),ENT_COMPAT, 'UTF-8');
	}
	$output .= "\n";

	// Close everything off
	fclose($io[1]);
	fclose($io[2]);
	proc_close($p);
}

// Finally, output our string
echo $output;

