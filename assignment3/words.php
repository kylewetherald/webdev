<?php
header('Content-type: application/json');
$filename = "words5.txt";
$words = file($filename, FILE_IGNORE_NEW_LINES);
$numwords = count($words);
$result = array();
for($i=0; $i<5; $i++) {
	$result[$i] = $words[rand(0,$numwords-1)];
}
echo json_encode(array("words" => $result));
?>