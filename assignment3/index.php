<html>
<head>
	<title>Lingo!</title>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="lingo.js"></script>
	<style>
		table, th, td {
			border: 1px solid black;
		}
		th, td {
			width: 50px;
			height: 18px;
			text-align: center;
		}
	</style>
</head>
<body>
	<p id="name">Welcome!</p>
	<ul>
		<li id='rounds'>You've attempted 0 puzzles</li>
		<li id='games'>You've played 0 games</li>
	</ul>
	<h1>LINGO!</h1>
	<p id="info"></p> 
	<table>
		<?php
		for ($i=0; $i<6; $i++) {
			echo "<tr>\n";
			for($j=0; $j<5; $j++) {
				echo"<td id='".$i.$j."'></td>\n";
			}
			echo "</tr>\n";
		}
		?>

	</table>
	<form id="input">
		<input tpye="text" name="guess" id="entry">
		<input type="submit" id="submit" value="Guess">
	</form>
	<form id="start">
		<input type="submit" value="Start">
	</form>
	<p id="message">Entry must be 5 letters</p>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#name").text("Hello, " + getName() + "!");
		$("#message").hide();
		getStats();
		start();
	});
	</script>
</body>
</html>