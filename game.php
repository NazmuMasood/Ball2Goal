<?php
session_start();
unset($_SESSION['time_taken']);
unset($_SESSION['jump_count']);

if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['username']);
	header("location: game.php");
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Ball2Goal - Lets Move It!</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<style type="text/css">
		#container {
			margin-top: 40px;
			height: 400px;
			width: 600px;
			outline: 2px solid black;
			position: relative;
			visibility: block;
		}

		#box {
			height: 25px;
			width: 25px;
			position: absolute;
			border-radius: 18px;
			/*outline: 2px solid black;*/
			background-color: red;
			left: 80px;
			top: 375px;
		}

		#obstacle {
			width: 40px;
			height: 55px;
			background-color: #87ceeb;
			outline: #87ceeb;
			border-radius: 50px 50px 0px 0px;
			position: absolute;
			visibility: block;
			top: 345px;
			left: 610px;
		}

		#hideObstacle {
			margin-top: 40px;
			margin-left: -50px;
			height: 400px;
			width: 50px;
			position: relative;
			visibility: block;
			background-color: white;
		}

		#startMessage{
			visibility: block; 
			display:inline-block;
			text-align:center;
			overflow:hidden;
			position: relative;
			top: 50%; 
			-webkit-transform: translateY(-50%);
			transform: translateY(-50%);
			z-index: 999;
			left:50%;
			-webkit-transform: translateX(-50%);
			transform: translateX(-50%);
			width:65%;
			background-color: yellow;
		}
	</style>
	<audio id="myAudio">
		<source src="js_doodling/chicken.mp3" type="audio/mp3">
		Your browser does not support the audio element.
	</audio>
	<script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
</head>

<body>
	<p></p>
	<div class="container">
		<!-- see username div -->
		<?php if (isset($_SESSION['username'])) : ?>
			<div style="float: left;">
				<a style="pointer-events: none; cursor: default;" href="" style="color: red;"><?php echo $_SESSION['username']; ?></a>
			</div>
			<div style="clear: right;"></div>
		<?php else : ?>
			<div style="float: left;">
				<a style="pointer-events: none; cursor: default;" href="" style="color: red;">Guest</a>
			</div>
			<div style="clear: right;"></div>
		<?php endif ?>
		<!-- see username div ends-->
		<!-- logout button div -->
		<?php if (isset($_SESSION['username'])) : ?>
			<div style="float: right;">&nbsp;&nbsp;&nbsp;
				<a href="game.php?logout='1'" style="color: red;">Logout</a>
			</div>
		<?php endif ?>
		<!-- logout button div ends -->
		<!-- login button div -->
		<?php if (!isset($_SESSION['username'])) : ?>
			<div style="float: right;">&nbsp;&nbsp;&nbsp;
				<a href="login_updt.php" style="color: red;">Login</a>
			</div>
		<?php endif ?>
		<!-- login button div ends -->
		<!-- see leaderboard button div -->
		<div style="float: right;">
			<a href="leaderboard.php" style="color: red;">See Leaderboard</a>
		</div>
		<div style="clear: right;"></div>
		<!-- see leaderboard button div ends-->
		<div id="letsHideAll" style="visibility: block; max-width:500px;">
			<div id="hideObstacle" style="float: left;"></div>
			<div id="container" style="float: left;">
				<div id="startMessage">
					<strong> 
					 Rush the 
					<span style="color:red;" class="iconify" data-icon="ion:bowling-ball" data-inline="false"></span> 
					to victory
					<span style="color:blue;" class="iconify" data-icon="emojione-monotone:flag-for-black-flag" data-inline="false"></span>! 
					Press any 
					<span class="iconify" data-icon="emojione:key" data-inline="false"></span>
					to start...  
					 </strong>
				</div>
				<div id="box"></div>
				<div id="flag" style="float: right;width: 8px;margin-top: 100px;margin-right: 16px;height: 300px;background-color: indigo;"></div>
				<div id="flag-stand" style="float: right;width: 80px;height: 60px;background-color: #87ceeb;margin-top: 105px;border-radius: 50px 0px 30px 5px;outline: #87ceeb; "></div>
				<div id="obstacle" style="z-index: 0;"></div>
			</div>
			<div id="hideObstacle" style="float: left;left:52px;"></div>
			<div id="playInstructions" 
				style="display: inline-block;vertical-align:bottom;visibility: block; margin-top:30px;"
			>
				<span style="height:2em; padding-bottom:4px;" class="iconify" data-icon="logos:todomvc" data-inline="false"></span>
				Press "
				<span class="iconify" data-icon="zmdi-space-bar" data-inline="false"></span>
				" to jump, Press " 
				<span class="iconify" data-icon="ic:round-keyboard-backspace" data-inline="false"></span>
				<span class="iconify" data-icon="ic:round-keyboard-backspace" data-inline="false" data-flip="horizontal"></span>
				" to move backward and forward
			</div>
		</div>
		<div id="winnerMessage" style="display: none;margin-top: 110px;">
			<img src="js_doodling/wwcd.jpg" style="margin-top: 40px;">
			<img src="js_doodling/chicken_nugget.jpg" style="float: right;">
			<button type="button" class="btn btn-primary btn-lg" style="float: left;margin-top: 68px;margin-left: 60px;padding: 30px;padding-right: 120px;padding-left: 120px;" onclick="location.href='see_score.php'">
				<span style="font-size:30px;">View My Score!</span>
			</button>
			<button type="button" class="btn btn-primary btn-lg" style="float: right;margin-top: 30px;margin-right: 85px;padding: 30px;padding-right: 150px;padding-left: 150px;" 
			onclick="reRun()">
				<span style="font-size:30px;">Play Again!</span>
			</button>
		</div>
		<div id="loserMessage" style="display: none;margin-top: 10px;">
			<img src="js_doodling/gameOver.jpg" style="margin-top: 40px;">
			<button type="button" class="btn btn-primary btn-lg" style="float: right;margin-top: 194px;margin-right: 80px;padding: 113px;" 
			onclick="reRun()">
				<span style="font-size:30px;">Play Again!</span>
			</button>
		</div>
		<div id="timeOut" style="display: none;margin-top: 100px;margin-left: 170px;">
			<img src="js_doodling/timeOut.jpg" style="margin-left: 250px;">
			<button type="button" class="btn btn-primary btn-lg" style="float: right;margin-top: 30px;margin-right: 50px;padding: 30px;padding-right: 450px;padding-left: 450px;"
			 onclick="reRun()">
				<span style="font-size:30px;">Play Again!</span>
			</button>
		</div>
		<div style="position: absolute;bottom: 10px;
						display:block;text-align: center;width: 100%;left: 0;">
			Made with <span style="color: #e25555;">&#9829;</span> by
			<a href="https://github.com/NazmuMasood" target="_blank">NazmuMasood</a>
		</div>
	</div>
</body>

<script type="text/javascript">
	var container = document.getElementById('container');
	var box = document.getElementById('box');
	var obstacle = document.getElementById('obstacle');
	var hideObstacle = document.getElementById('hideObstacle');
	var hiddenTimeInput = document.getElementById("timeTaken");
	var hiddenJumpInput = document.getElementById("jumpCount");
	var sound = document.getElementById("myAudio");
	var boxLeft;
	var boxTop;
	var obsLef;
	var winnerCheck;
	var winnerTimeTaken;
	var winnerJumpCount;
	var loserCheck;
	var gTime;
	//calculates time_taken to complete the game
	var startTime, endTime;
	//All setTimeout timers
	var gameTimer, obstackleMovingTimer,ballGoUpTimer, ballGoDownTimer;
	var noUserInteraction = true;

	function setupVars(){
		boxLeft = 80;
		boxTop = 375;
		obsLeft = 610;
		winnerCheck = 0;
		winnerTimeTaken = 0;
		winnerJumpCount = 0;
		loserCheck = 0;
		gTime = 200;
	}
	
	function delete_cookie(name) {
		document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
	}

	function timeStart() {
		startTime = new Date();
	};

	function timeEnd() {
		endTime = new Date();
		var timeDiff = endTime - startTime; //in ms
		// strip the ms
		timeDiff /= 1000;
		// get seconds 
		//var seconds = Math.round(timeDiff);
		//console.log(seconds + " seconds");
		var seconds = timeDiff;
		winnerTimeTaken = seconds;
	}

	//function for the moving obstacle
	function movingObstacle() {
		//distance of the horizontal movement of obstacle (in px)
		var distance = obsLeft + 40;
		//number of time-slots i.e. divisions in a cycle
		var timeSlotCounter = 40;
		//time in a half-cycle (goUp/goDown)
		var totalTime = 120;
		//the amount of time in a particular time-slot
		var timeSlot = totalTime / timeSlotCounter;
		//goUp() execution
		var time = gTime;
		for (var i = 1; i <= distance; i++) {
			if (i == 1) {
				obstackleMovingTimer = setTimeout(goLeftObstacle, time);
			} else {
				time = time + timeSlot;
				obstackleMovingTimer = setTimeout(goLeftObstacle, time);
			}
		}
		gTime = time;
	}
	//obstacle moves
	function goLeftObstacle() {
		//check for the ball's crash with the obstacle
		if (boxLeft >= (obsLeft - 20) && boxLeft < obsLeft + 40) {
			if (boxTop >= 325 && winnerCheck == 0) {
				if (loserCheck == 0) {
					loserCheck = 1;
					sound.pause();
					sound.currentTime = 0;
					document.getElementById("loserMessage").style.display = "block";
					document.getElementById("letsHideAll").style.display = "none";
				}
			}
		}
		obsLeft -= 1;
		obstacle.style.left = obsLeft + 'px';
		//hiding obstacle behind white div upon distance completion
		if (obsLeft <= 0 || obsLeft >= 580) {
			obstacle.style.zIndex = -1;
		}
		//moving obstacle back to the starting point
		if (obsLeft <= -45) {
			obsLeft = 610;
			obstacle.style.left = obsLeft + 'px';
		}
	}

	function run() {
		movingObstacle();
	}
	
	function callRun(){
		//Moving the obstacle towards our ball
		for (i = 0; i < 30; i++) {
			run();
		}
	}

	// function setupView(){
	// 	document.getElementById("timeOut").style.display = "none";
	// 	document.getElementById("winnerMessage").style.display = "none";
	// 	document.getElementById("loserMessage").style.display = "none";
	// 	document.getElementById("letsHideAll").style.display = "block";
	// }

	function init(){
		delete_cookie("timeTaken"); delete_cookie("jumpCount");
		setupVars();
		timeStart(); callTheEndTheGame();
		callRun();
	}
	// init();

	function reRun(){
		document.location.reload(false);
		// delete_cookie("timeTaken"); delete_cookie("jumpCount");
		// clearTimeout(gameTimer); clearTimeout(obstackleMovingTimer);
		// clearTimeout(ballGoUpTimer); clearTimeout(ballGoDownTimer);
		// setupView();
		// noUserInteraction = true;

		// setupVars();
		// timeStart(); callTheEndTheGame();
		// callRun();
	}

	//calling the endTheGame() on page load
	// obstacle.addEventListener("load", callTheEndTheGame());
	// document.addEventListener("DOMContentLoaded", function() {
  	// 	callTheEndTheGame();
	// });

	//check for time-out
	function callTheEndTheGame() {
		gameTimer = setTimeout(endTheGame, 15000);
	}
	//end the game when time exceeds 60 seconds
	function endTheGame() {
		if (loserCheck == 0 && winnerCheck == 0) {
			loserCheck = 1;
			sound.pause();
			sound.currentTime = 0;
			document.getElementById("timeOut").style.display = "block";
			document.getElementById("letsHideAll").style.display = "none";
		}
	}

	//main function for ball movement
	function anime(k) {
		if(noUserInteraction){
			if( document.readyState === 'complete'){ 
				noUserInteraction = false; 
				document.getElementById("startMessage").style.display = "none";
				init(); return; 
			}
			else{return;}
		}
		window.winnerCheck;
		//right arrow
		if (k.keyCode == 39) {
			boxLeft += 8;
			//winner-checker
			if (boxLeft >= 560 && loserCheck == 0) {
				if (window.winnerCheck == 0) {
					window.winnerCheck = 1;
					sound.pause();
					sound.currentTime = 0;
					timeEnd();
					document.cookie = 'timeTaken=' + winnerTimeTaken + '';
					document.cookie = 'jumpCount=' + winnerJumpCount + '';
					document.getElementById("winnerMessage").style.display = "block";
					document.getElementById("letsHideAll").style.display = "none";
				}
			}
			if (boxLeft > 576) {
				boxLeft -= 8;
			}
			box.style.left = boxLeft + 'px';

		}
		//left arrow
		if (k.keyCode == 37) {
			boxLeft -= 8;
			box.style.left = boxLeft + 'px';
			if (boxLeft <= 0) {
				boxLeft += 8;
			}
		}
		/*
				//down arrow
				if(k.keyCode==40){
					boxTop +=1;
					if(boxTop>375){
						boxTop -=8;
					}
					box.style.top = boxTop+'px';
				}
				//up arrow
				if(k.keyCode==38){
					boxTop -=1;
					box.style.top = boxTop+'px';
					if(boxTop<0){
						boxTop +=8;
					}
				}
		*/
		//spacebar

		if (k.keyCode == 32 && boxTop == 375) {
			winnerJumpCount++;
			//jumping sound
			playAudio();
			//height of the jump (in px)
			var height = 104;
			//number of time-slots i.e. divisions in a half-cycle
			var timeSlotCounter = 40;
			//time in a half-cycle (goUp/goDown)
			var totalTime = 160;
			//the amount of time in a particular time-slot
			var timeSlot = totalTime / timeSlotCounter;
			//goUp() execution
			var time1 = 0;
			for (var i = 1; i <= height; i++) {
				time1 = time1 + timeSlot;
				ballGoUpTimer = setTimeout(goUp, time1);
			}
			//goDown() execution after finishing goUp 
			var time2 = time1;
			for (var i = 1; i <= height; i++) {
				//ball comes down 10% faster than going-up
				time2 = time2 + (timeSlot * 0.90);
				ballGoDownTimer = setTimeout(goDown, time2);
			}
		}
	}

	//ball goes upwards upon spacebar press
	function goUp() {
		boxTop -= 1;
		if (boxTop < (400 - 104)) {
			boxTop += 1;
		}
		box.style.top = boxTop + 'px';

	}
	//ball returns to the initial level
	function goDown() {
		boxTop += 1;
		if (boxTop > 375) {
			boxTop -= 1;
		}
		box.style.top = boxTop + 'px';
	}
	//play jumping sound 
	function playAudio() {
		sound.pause();
		sound.currentTime = 0;
		sound.play();
	}

	//handles keypress
	document.onkeydown = anime;
</script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</html>
<!--
	<?php if ($_COOKIE["winnerCheck"] == 1) : ?>
	<?php echo "Your time taken is " . $_COOKIE["timeTaken"]; ?>
	<?php endif ?>
-->