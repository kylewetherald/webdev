<?php
# Kyle Wetherald
# CS 1520 Spring 2014
# Assignment 1

// Load classes needed
function __autoload($class) {
     require_once $class . '.php';
}

// main
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Scheduler</title>
	</head>
	<body align="center">
	<h2>Select your meeting times</h2>
<?php 
date_default_timezone_set('America/New_York');

if (isset($_SESSION['flash'])) {
	echo $_SESSION['flash'];
	unset($_SESSION['flash']);
}

	$action = null;
	if(isset($_POST['action']))
		$action = $_POST['action'];
	?>
	<table border="1">
	<tr align="center">
		<td>User</td>
		<td>Action</td>
		<?php

		$filename = "schedule.txt";
		$pattern = '/([^\^|]+)/'; // one or more of any character except for '^' or '|'
		$format = 'l<\b\r>m/d/y<\b\r>h:s A'; // Monday<br>01/12/13<br>01:23 PM
		$lines = file($filename, FILE_IGNORE_NEW_LINES);
		$numtimes = 0;
		foreach($lines as $line):
			preg_match_all($pattern, $line, $datetimes);
			$isFirst = true;
			foreach($datetimes[0] as $val) {
				if($isFirst) {
					$date = $val;
					$isFirst = false;
					continue;
				}
				$time = strtotime($date.$val);
				echo "<td>" . date($format, $time) . "</td>\n";
				$numtimes++;
			}
		endforeach;

	echo "</tr>";
	$filename = "users.txt";
	$totals = array_fill(0, $numtimes, 0);
	if(!file_exists($filename)) {
		$fp = fopen($filename, "w");
		fclose($fp);
	}
	foreach(file($filename, FILE_IGNORE_NEW_LINES) as $line):
		echo "<tr>";
		preg_match_all($pattern, $line, $uservals);

		$isFirst = true;
		foreach($uservals[0] as $val) {
			//first time through loop gives us username,
			if($isFirst) {
				$editname = $val;

				//determine if we are editing this row.
				$editing = isset($_POST['username']) && $_POST['username'] == $editname;

				// first column = name
				echo "<td>$editname</td>";
				// if editing row, second column is Save button with update action.
				if($editing) 
					echo "<td><form name='edit' action='update.php' method='post'><input type='hidden' name='username' value='$editname' /><input type='submit' name='action' value='Save'></td>";
				// if not editing, but name is in cookie or in session variable, second column is edit button
				elseif(isset($_COOKIE[str_replace(' ', '_', $editname)]) || isset($_SESSION[$editname]))
					echo "<td><form name='edit' action='' method='post'><input type='hidden' name='username' value='$editname' /><input type='submit' name='action' value='Edit'></td>";
				// otherwise, no button.
				else
					echo "<td></td>";
				$isFirst = false;
				$i=0;
				continue;
			}

			//update total
			$totals[(int)$val]++;

			// if editing, want checkboxes.
			if($editing) {
				//black boxes up to the date
				for( ;$i<(int)$val; $i++) {
					echo "<td><input type='checkbox' name='values[]' value='$i'></td>";
				}
				//checked box for date
				echo "<td><input type='checkbox' name='values[]' value='$i' checked></td>";
			}
			//otherwise we want checkmarks.
			else {
				for( ;$i<(int)$val; $i++) {
					echo "<td></td>";
				}
				echo "<td>&#10003</td>";
			}
			$i++;
		}
		// fill to end of table
		if($editing) {
			for( ;$i<$numtimes; $i++) {
				echo "<td><input type='checkbox' name='values[]' value='$i'></td>";
			}
		}
		else {
			for( ;$i<$numtimes; $i++) {
				echo "<td></td>";
			}
		}

		echo "</form></tr>\n";
	endforeach;
		echo "<tr>";
	if($action == 'New') {
		?>
		<form name='input' action='submit.php' method='post'>
		<td><input type='text' name='username'></td>
		<td><input type='submit' name='action' value='Submit'></td>
		<?php
		for($i=0;$i<$numtimes; $i++) {
			echo "<td><input type='checkbox' name='values[]' value='$i'></td>";
		}
	}
	else {
		?>
		<td></td>
		<td><form name='new' action='' method='post'><input type='submit' name='action' value='New'></td>
		<?php
		for($i=0;$i<$numtimes; $i++) {
			echo "<td></td>";
		}
	}
	echo "</tr>\n<tr>";
	echo "<td>Total</td><td></td>";
	for($i=0; $i<$numtimes; $i++) {
		echo "<td>" . $totals[$i] . "</td>";
	}
	echo "</form></tr>";
echo "</table>";

?>

</body>
</html>