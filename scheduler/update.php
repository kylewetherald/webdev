<?php
header( 'Location: index.php' );
$line = $_POST['username'] . "^";
foreach($_POST['values'] as $time) {
	$line = $line . $time . "|";
}
$line = rtrim($line, "|") . "\n";
$result = "";
$fp = fopen("users.txt", "r+");
flock($fp, LOCK_EX);
// reread the file to make sure username hasnt been submitted since last load
while (($buffer = fgets($fp)) !== false) {
	preg_match('/[^\^]+/', $buffer, $name);

	if($name[0] == $_POST['username'])
		$result = $result . $line;
	else 
		$result = $result . $buffer;
}
rewind($fp);
ftruncate($fp, 0);
fwrite($fp, $result);
flock($fp, LOCK_UN);
fclose($fp);
?>