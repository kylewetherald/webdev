	var words;
	var tries;
	var currentWord;
	var timer;
	var round;
	var correct;
	var wins;
	var games;
	var rounds;
	var totalSolved;

	function reset() {
		for(var i=0; i<6; i++) {
			for(var j=0; j<6; j++) {
				var id="#"+i+j;
				$(id).text("");
				$(id).css("color", "black");
			}
		}
	}

	function start() {
		if (round == null) {
			round = 1;
		}
		var info;
		if (round == 1) {
			info = "Click Start to begin a new game";
			correct = 0;
		$("#info").text(info);
		$("#input").hide();
		$("#start").show();
		$("#start").submit(function(event) {
			event.preventDefault();
			reset();
			$("#input").show();
			$("#start").hide();
			$("#info").text("Round " + round);
			getWords();
			playGame();
		}); 
		}
		else {
			reset();
			$("#input").show();
			$("#start").hide();
			$("#info").text("Round " + round);
			playGame();
		}

	} 

	// prompts new user for name, otherwise gets name from localStorage
	function getName() {
		var name = localStorage.getItem('name');
		if(name == "null" || name == null) {
			name = prompt("Please enter your name","Name");
			localStorage.setItem('name', name);
		}
		return name;
	}

	// gets or initializes game stats for user
	function getStats() {
		totalSolved = localStorage.getItem('solved');
		if(totalSolved == "null" || totalSolved == null) {
			totalSolved = 0;
		}
		rounds = localStorage.getItem('rounds');
		if(rounds == "null" || rounds == null) {
			rounds = 0;
		}
		$("#rounds").text("You have solved " + totalSolved +
			" out of " + rounds + " puzzles.");
		wins = localStorage.getItem('wins');
		if(wins == "null" || wins == null) {
			wins = 0;
		}
		games = localStorage.getItem('games');
		if(games == "null" || games == null) {
			games = 0;
		}
		$("#games").text("You have won " + wins +
			" out of " + games + " games.");
	}

	//TODO: fetches random words from server
	function getWords() {
		words = null;
		$.ajax({
	     	async: false,
	     	type: "GET",
	     	url: "words.php",
	     	dataType: 'json',
     		success: function(data) {
				words = data.words;
			}
		});
		if (words == null) {
			alert("unable to retrieve words from server!");
		}
	}

	//Checks to see if all letters in current row are red
	function isSolved() {
		var count = 0;
		for(var i=0; i<5; i++) {
			var id="#" + (tries-1) + i;
			var color = $(id).css("color");
			if(color == "rgb(255, 0, 0)") {
				count++;
			}
		}
		return count==5;
	}

	//takes word, checks for equality with current word, updates grid
	function guess(input) {
		clearInterval(timer);
		var id;
		for(var i=0; i<5; i++) {
			id = "#"+tries+i;
			if(input.charAt(i).toLowerCase() === currentWord.charAt(i)) {
				$(id).css("color", "red");
				$(id).text(input.charAt(i).toUpperCase());
			}
			else if(currentWord.indexOf(input.charAt(i).toLowerCase()) > -1) {
				$(id).css("color", "blue");
				$(id).text(input.charAt(i).toUpperCase());
			}
			else {
				$(id).css("color", "black");
				$(id).text(input.charAt(i).toLowerCase());
			}
		}
		tries++;
		timer = setTimeout(function() {guess(input)}, 10000);
		var solved = isSolved();
		var message;
		if (tries >= 6 || solved)  {
			if (solved) {
				message = "Congratulations! you solved the puzzle!";
				correct++;
				totalSolved++;
				localStorage.setItem('solved', totalSolved);
			}
			else {
				message = "Bummer. You weren't able to solve this one.\n" +
					"word was: " + currentWord;
			}
			rounds++;
			localStorage.setItem('rounds', rounds);
			$("#rounds").text("You have solved " + totalSolved +
				" out of " + rounds + " puzzles.");
			if (round < 5) {
				message = message + "\n\nClick OK to start next round";
			} 
			else {
				if(correct >= 3) {
					message = message + "\n\nYou solved " + correct + " puzzles." +
						"You win!";
					wins++;
					localStorage.setItem('wins', wins);
				}
				else {
					message = message + "\n\nYou only solved " + correct + " puzzles." +
						"You lose!";
				}
				games++;
				localStorage.setItem('games', games);
				$("#games").text("You have won " + wins +
					" out of " + games + " games.");
			}
			alert(message);
			round++;
			clearInterval(timer);
			if(round >= 6) {
				round = 1;
			}

			start();
		}

	}

	//loops through 5 words, 5 guesses per word.
	function playGame() {
		currentWord = words[round-1];
		alert("Word is:" + currentWord);
		var id = "#" + 0 + "0";
		tries = 0; 
		var unsolved = true;
		var input = currentWord.charAt(0) + "    ";
		var timer;
		guess(input);
		$("#input").submit(function(event) {
			event.preventDefault();
			input = $("#entry").val();
			if(input.length == 5) {
				guess(input);
			}
			else {
				$("#message").show();
				setTimeout(function() {$("#message").hide()}, 3000);
			}
			$("#entry").val("");
		});

	}
