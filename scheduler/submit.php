<?php
session_start();
header( 'Location: index.php' );
if(!isset($_POST['username']) or $_POST['username'] == "") {
	$_SESSION['flash'] = "<h3 style='color:red'>Please include name.</h3>";
}
else {
	$line = $_POST['username'] . "^";
	foreach($_POST['values'] as $time) {
		$line = $line . $time . "|";
	}
	$line = rtrim($line, "|") . "\n";
	$fp = fopen("users.txt", "a+");
	flock($fp, LOCK_EX);
	// reread the file to make sure username hasnt been submitted since last load
	while (($buffer = fgets($fp)) !== false) {
		preg_match('/[^\^]+/', $buffer, $name);
		if($name[0] == $_POST['username']) {
			$_SESSION['flash'] = "<h3 style='color:red'>Username already in use.</h3>";
			return;
		}
	}
	$result = fwrite($fp, $line);
	setcookie(str_replace(' ', '_', $_POST['username']),0,time()+60*60*24*30);
	$_SESSION[$_POST['username']] = 0;
	flock($fp, LOCK_UN);
	fclose($fp);
	echo "This should redirect.";
} ?>