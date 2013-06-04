<?php
include("../lib/settings.php");
if ($demoMode || !$_SESSION['loggedIn']) {
	die("You must be logged in to access Terminal");
}

error_reporting(E_ALL);
@session_start();

if (isset($_SERVER['PHP_AUTH_USER'])) {
	$_SESSION['user'] = $_SERVER['PHP_AUTH_USER'];
	$_SESSION['pass'] = generateHash(strClean($_SERVER['PHP_AUTH_PW']),$ICEcoder["password"]);
}
$passwd = array($_SESSION['user'] => $ICEcoder["password"]);
$aliases = array('la' 	=> 'ls -la',
		'll' 	=> 'ls -lvhF',
		'dir'	=> 'ls' );

class phpTerm {
	function phpTerm() {} // constructor

	function formatPrompt() {
		$user=shell_exec("whoami");
		$host=explode(".", shell_exec("uname -n"));
		$_SESSION['prompt'] = "".rtrim($user).""."@"."".rtrim($host[0])."";
	}

	function checkPassword($passwd) {
		if(	!isset($_SERVER['PHP_AUTH_USER'])||
			!isset($_SERVER['PHP_AUTH_PW']) ||
			!isset($passwd[$_SERVER['PHP_AUTH_USER']]) ||
			$passwd[$_SERVER['PHP_AUTH_USER']] != $_SESSION['pass']) 	{
			return false;
		} else {
			return true;
		}
	}

	function logout() {
		header('WWW-Authenticate: Basic realm="Terminal"');
		header('HTTP/1.0 401 Unauthorized');
		exit();
	}

	function initVars() {
		if (empty($_SESSION['cwd']) || @!empty($_GET['reset'])) {
			$_SESSION['cwd'] = getcwd();
			$_SESSION['history'] = array();
			$_SESSION['output'] = '';
			$_REQUEST['command'] ='';
		}
	}

	function buildCommandHistory() {
		if(!empty($_REQUEST['command'])) {
			if(get_magic_quotes_gpc()) {
				$_REQUEST['command'] = stripslashes($_REQUEST['command']);
			}
	
			// drop old commands from list if exists
			if (($i = array_search($_REQUEST['command'], $_SESSION['history'])) !== false) {
				unset($_SESSION['history'][$i]);
			}
			array_unshift($_SESSION['history'], $_REQUEST['command']);

			// append commmand */
			$_SESSION['output'] .= "{$_SESSION['prompt']}".":>"."{$_REQUEST['command']}"."\n";
		}
	}

	function buildJavaHistory() {
		// build command history for use in the JavaScript 
		if (empty($_SESSION['history'])) {
			$_SESSION['js_command_hist'] = '""';
		} else {
			$escaped = array_map('addslashes', $_SESSION['history']);
			$_SESSION['js_command_hist'] = '"", "' . implode('", "', $escaped) . '"';
		}
	}

	function outputHandle($aliases) {
		if (preg_match('/^[[:blank:]]*cd[[:blank:]]*$/', @$_REQUEST['command']))
		{
			$_SESSION['cwd'] = getcwd(); //dirname(__FILE__);
		}
		elseif(preg_match('/^[[:blank:]]*cd[[:blank:]]+([^;]+)$/', @$_REQUEST['command'], $regs)) {
			// The current command is 'cd', which we have to handle as an internal shell command. 
			// absolute/relative path ?"
			($regs[1][0] == '/') ? $new_dir = $regs[1] : $new_dir = $_SESSION['cwd'] . '/' . $regs[1];
		
			// cosmetics 
			while (strpos($new_dir, '/./') !== false) {
				$new_dir = str_replace('/./', '/', $new_dir);
			}
			while (strpos($new_dir, '//') !== false) {
				$new_dir = str_replace('//', '/', $new_dir);
			}
			while (preg_match('|/\.\.(?!\.)|', $new_dir)) {
				$new_dir = preg_replace('|/?[^/]+/\.\.(?!\.)|', '', $new_dir);
			}

			if(empty($new_dir)): $new_dir = "/"; endif;

			(@chdir($new_dir)) ? $_SESSION['cwd'] = $new_dir : $_SESSION['output'] .= "could not change to: $new_dir\n";
		} else {
			/* The command is not a 'cd' command, so we execute it after
			changing the directory and save the output. */
			chdir($_SESSION['cwd']);

			/* Alias expansion. */
			$length = strcspn(@$_REQUEST['command'], " \t");
			$token = substr(@$_REQUEST['command'], 0, $length);
			if (isset($aliases[$token]))
			$_REQUEST['command'] = $aliases[$token] . substr($_REQUEST['command'], $length);
		
			$p = proc_open(@$_REQUEST['command'],
				array(1 => array('pipe', 'w'),
				2 => array('pipe', 'w')), $io);
	
			/* Read output sent to stdout. */
			while (!feof($io[1])) {
				$_SESSION['output'] .= htmlspecialchars(fgets($io[1]),ENT_COMPAT, 'UTF-8');
			}
			/* Read output sent to stderr. */
			while (!feof($io[2])) {
				$_SESSION['output'] .= htmlspecialchars(fgets($io[2]),ENT_COMPAT, 'UTF-8');
			}
			
			fclose($io[1]);
			fclose($io[2]);
			proc_close($p);
		}
	}
}

$terminal = new phpTerm;

if ($_REQUEST['command']=="logout") {
	$terminal->logout();
}

if(!$terminal->checkPassword($passwd)) {
	header('WWW-Authenticate: Basic realm="Terminal"');
	header('HTTP/1.0 401 Unauthorized');
} else {
	$terminal->initVars();
	$terminal->buildCommandHistory();
	$terminal->buildJavaHistory();
	if(!isset($_SESSION['prompt'])):$terminal->formatPrompt(); endif;
	$terminal->outputHandle($aliases);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>PHP Terminal</title>
<link rel="stylesheet" type="text/css" href="terminal.css" />  
<script type="text/javascript" language="JavaScript">
var current_line = 0;
var command_hist = new Array(<?php echo $_SESSION['js_command_hist']; ?>);
var last = 0;
	
function key(e) {
	if (!e) var e = window.event;
	if (e.keyCode == 38 && current_line < command_hist.length-1) {
		command_hist[current_line] = document.shell.command.value;
		current_line++;
		document.shell.command.value = command_hist[current_line];
	}
	if (e.keyCode == 40 && current_line > 0) {
		command_hist[current_line] = document.shell.command.value;
		current_line--;
		document.shell.command.value = command_hist[current_line];
	}
}

function init() {
	document.shell.setAttribute("autocomplete", "off");
	document.shell.output.scrollTop = document.shell.output.scrollHeight;
	document.shell.command.focus();
}

</script>
</head>

<body onload="init()">

<div class="head"><?php echo $_SESSION['prompt'].":"."$_SESSION[cwd]"; ?></div>

<form name="shell" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	<textarea name="output" readonly="readonly" rows="24"><?php
		$lines = substr_count($_SESSION['output'], "\n");
		$padding = str_repeat("\n", max(0, 25 - $lines));
		echo "\n\n".trim($padding . $_SESSION['output'])."\n";
	?>
	</textarea>
	<p class="commandLine">$&gt; <input class="command" name="command" type="text"  size='50' onkeyup="key(event)" tabindex="1"></p>
</form>

</body>
</html>
<?php } ?>