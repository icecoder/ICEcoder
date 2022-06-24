<?php
include dirname(__FILE__) . "/headers.php";
include dirname(__FILE__) . "/settings.php";

// Set some common aliases
$aliases = array(
    'la' 	=> 'ls -la',
    'll' 	=> 'ls -lvhF',
);

// If we have no cwd set in session, set it now
if (false === isset($_SESSION['cwd'])) {
	$_SESSION['cwd'] = $docRoot . $iceRoot;
}

// Change to cwd
chdir($_SESSION['cwd']);

// Get current user and cwd
if (true === $systemClass->functionEnabled("shell_exec")) {
	$user = str_replace("\n", "", shell_exec("whoami"));
	$cwd = str_replace("\n", "", shell_exec("pwd"));
} else {
	$user = "";
	$cwd = "";
}

// Check if we have proc_open_enabled
// (Used later to handle commands)
function proc_open_enabled() {
    $disabled = explode(',', ini_get('disable_functions'));
    return false === in_array('proc_open', $disabled);
}

// Return HTML prompt plus the command the user provided last
function returnHTMLPromptCommand($cmd) {
    global $user, $cwd;
    // Begin output with prompt and user command
    return '<div class="commandLine"><div class="user">&nbsp;&nbsp;' . $user . '&nbsp;</div>'.
        '<div class="cwd">&nbsp;' . $cwd . '&nbsp;</div> : ' . date("H:m:s") .
        '<br>' .
        '<div class="promptVLine"></div><div class="promptHLine">─<div class="promptArrow">▶</div></div> ' . $cmd . '</div></div><br>';
}

// If proc_open isn't enabled, display prompt, command and a message re needing this enabled
if (false === proc_open_enabled()) {
    echo json_encode([
        "output" => returnHTMLPromptCommand($_POST['command'] . "<br><br>Sorry but you can't use this terminal if your proc_open is disabled"),
        "user" => $user,
        "cwd" => $cwd
    ]);
    exit;
}

// If in demo mode, display message and go no further
if (true === $demoMode) {
    echo json_encode([
        "output" => returnHTMLPromptCommand($_POST['command'] . "<br><br>Sorry, shell usage not enabled in demo mode"),
        "user" => $user,
        "cwd" => $cwd
    ]);
    exit;
}

// If no command, display message and go no further
if (false === isset($_POST['command'])) {
    echo json_encode([
        "output" => returnHTMLPromptCommand($_POST['command'] . "<br><br>Sorry, no command received"),
        "user" => $user,
        "cwd" => $cwd
    ]);
    exit;
}

// Strip any slashes from command
$_POST['command'] = stripslashes($_POST['command']);

// Start output with the prompt and command they provided last
$output = returnHTMLPromptCommand($_POST['command']);

// If command contains cd but no dir
if (preg_match('/^[[:blank:]]*cd[[:blank:]]*$/', $_POST['command'])) {
	$_SESSION['cwd'] = $cwd;
// Else cd to a dir
} elseif (preg_match('/^[[:blank:]]*cd[[:blank:]]+([^;]+)$/', $_POST['command'], $regs)) {
	// The current command is 'cd', which we have to handle as an internal shell command
    $newDir = "/" === $regs[1][0] ? $regs[1] : $_SESSION['cwd'] . "/" . $regs[1];

	// Tidy up appearance on /./
	while (false !== strpos($newDir, '/./')) {
		$newDir = str_replace('/./', '/', $newDir);
	}
	// Tidy up appearance on //
	while (false !== strpos($newDir, '//')) {
		$newDir = str_replace('//', '/', $newDir);
	}
	// Tidy up appearance on other variations
    while (preg_match('/\/\.\.(?!\.)/', $newDir)) {
        $newDir = preg_replace('/\/?[^\/]+\/\.\.(?!\.)/', '', $newDir);
	}

	// Empty dir
	if(empty($newDir)) {
		$newDir = "/";
	}

	// Test if we could change to that dir, else display error
	(@chdir($newDir)) ? $_SESSION['cwd'] = $newDir : $output .= "Could not change to: $newDir\n\n";
} else {
	// The command is not a 'cd' command

	// Alias expansion
	$length = strcspn($_POST['command'], " \t");
	$token = substr($_POST['command'], 0, $length);
	if (true === isset($aliases[$token])) {
		$_POST['command'] = $aliases[$token] . substr($_POST['command'], $length);
	}

	// Open a proc with array and $io return
	$p = proc_open(
		$_POST['command'],
		array(
			1 => array('pipe', 'w'),
			2 => array('pipe', 'w')
		),
		$io
	);

	// Read output sent to stdout
	while (false === feof($io[1])) {
	    // this will return always false ... and will loop forever until "fork: retry: no child processes" will show if proc_open is disabled;
		$output .= htmlspecialchars(fgets($io[1]), ENT_COMPAT, 'UTF-8');
	}
	// Read output sent to stderr
	while (false === feof($io[2])) {
		$output .= htmlspecialchars(fgets($io[2]), ENT_COMPAT, 'UTF-8');
	}
	$output .= "\n";

	// Close everything off
	fclose($io[1]);
	fclose($io[2]);
	proc_close($p);
}

// Change to the cwd in session
chdir($_SESSION['cwd']);

// and again ask for current user and working dir
if (true === $systemClass->functionEnabled("shell_exec")) {
	$user = str_replace("\n", "", shell_exec("whoami"));
	$cwd = str_replace("\n", "", shell_exec("pwd"));
} else {
	$user = "";
	$cwd = "";
}

// Finally, output our JSON data
echo json_encode([
    "output" => $output,
    "user" => $user,
    "cwd" => $cwd
]);

