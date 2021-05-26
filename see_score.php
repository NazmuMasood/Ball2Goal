<?php
session_start();
include('connection.php');

//handles log-out button
if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['username']);
	header("location: game.php");
}

//gets time_taken in finishing the game from javascript
$_SESSION['time_taken'] = $_COOKIE["timeTaken"];
$_SESSION['jump_count'] = $_COOKIE["jumpCount"];
//setcookie("timeTaken", "", time()-3600);
//setcookie("jumpCount", "", time()-3600);

//get THE highest score
$result = mysqli_query($db, "SELECT MIN(time_taken) AS minimum FROM users") or die($query . "<br/><br/>" . mysqli_error($db));;
$row = mysqli_fetch_assoc($result);
$lowest = $row['minimum'];

$user_id = "";
//for logged in user
if (isset($_SESSION['user_id'])) {
	$user_id = $_SESSION['user_id'];
	//get personal high-score	
	$result = mysqli_query($db, "SELECT * from users WHERE `user_id` = '$user_id'") or die($query . "<br/><br/>" . mysqli_error($db));
	$row = mysqli_fetch_assoc($result);
	$time_taken = $row['time_taken'];
	$jump_count = $row['jump_count'];
}
//for guest
if (!isset($_SESSION['user_id'])) {
	$user_id = 0;
	$_SESSION['first_time_try'] = 1;

	if (isset($_SESSION['lowest_time_taken']) && isset($_SESSION['lowest_jump_count'])) {
		$_SESSION['first_time_try'] = 0;
		//gets guest previous best scores before updating them
		$_SESSION['temp_lowest_time_taken'] = $_SESSION['lowest_time_taken'];
		$_SESSION['temp_lowest_jump_count'] = $_SESSION['lowest_jump_count'];
		if ($_SESSION['time_taken'] < $_SESSION['lowest_time_taken']) {
			//updates guest's best score
			$_SESSION['lowest_time_taken'] = $_SESSION['time_taken'];
			$_SESSION['lowest_jump_count'] = $_SESSION['jump_count'];
		}
	} else {
		//saves guest's first try scores as the best score
		$_SESSION['lowest_time_taken'] = $_SESSION['time_taken'];
		$_SESSION['lowest_jump_count'] = $_SESSION['jump_count'];
	}
}

//get leaderboard info
$query = "SELECT * from users where time_taken IS NOT NULL ORDER BY time_taken ASC";
$leaderboard = mysqli_query($db, $query);
?>
<!DOCTYPE html>
<html>

<head>
	<title>My Score</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<style>
		.table {
			border-collapse: separate;
			border: solid black 1px;
			border-radius: 6px;
			-moz-border-radius: 6px;
		}

		td,
		th {
			border-left: solid black 1px;
			border-top: solid black 1px;
		}

		th {
			border-top: none;
		}

		td:first-child,
		th:first-child {
			border-left: none;
		}
	</style>
</head>

<body>
	<div class="container" style="margin-top: 10px;">
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
		<!-- <div style="float: right;">
	 		<a href="leaderboard.php" style="color: red;">See Leaderboard</a>
	   	</div>
	    -->
		<!-- see leaderboard button div ends   	 -->
		<div style="clear: right;"></div> <br>
		<!-- logged-in user div -->
		<?php if ($user_id > 0) : ?>
			<!--shows congratulation message -->
			<?php if ($_SESSION['time_taken'] <= $lowest) : ?>
				<h3>Congratulations <?php echo $_SESSION['username'] ?>! You beat em all..</h3>
				<!-- handles user who just registered and started playing -->
			<?php elseif ($time_taken == NULL) : ?>
				<h3>Congratulations <?php echo $_SESSION['username']; ?>! You did it..</h3>
				<!-- handles user who just registered and started playing ends -->
			<?php elseif ($_SESSION['time_taken'] < $time_taken) : ?>
				<h3>Congratulations <?php echo $_SESSION['username'] ?>! New Personal High Score..</h3>
			<?php endif ?>
			<!-- ^^^ -->
			<!--shows consolation message -->
			<?php if ($_SESSION['time_taken'] > $time_taken && !$time_taken == NULL) : ?>
				<h3>Good game <?php echo $_SESSION['username'] ?>. You can do better..</h3>
			<?php endif ?>
			<!-- ^^^ -->
			<!--shows current score -->
			<?php
			echo "You finished in " . $_SESSION['time_taken'] . " seconds with " . $_SESSION['jump_count'] . " jumps";
			?><br>
			<!-- ^^^ -->
			<!--shows previous high-score -->
			<?php if ($_SESSION['time_taken'] > $time_taken && !$time_taken == NULL) : ?>
				Previous high score :
				<?php
				echo $time_taken . " seconds with " . $jump_count . " jumps";
				?><br>
			<?php endif ?>
			<!-- ^^^ -->
			<br>
			<?php if ($_SESSION['time_taken'] < $time_taken) : ?>
				<button type="button" class="btn btn-primary btn-lg" onclick="location.href='publish.php'">Update Result</button>
			<?php endif ?>
			<!--<button type="button" class="btn btn-primary btn-lg" onclick="location.href='leaderboard.php'">See Leaderboard</button>-->
			<?php if ($time_taken == NULL) : ?>
				<button type="button" class="btn btn-primary btn-lg" onclick="location.href='publish.php'">Publish Result</button>
			<?php endif ?>
			<button type="button" class="btn btn-primary btn-lg" onclick="location.href='game.php'">Play Again</button>
		<?php endif ?>
		<!-- logged-in user div ends -->

		<!--guest div-->
		<?php if ($user_id == 0) : ?>
			<!-- guest's first try -->
			<?php if ($_SESSION['first_time_try'] == 1) : ?>
				<?php if ($_SESSION['time_taken'] <= $lowest) : ?>
					<h3>Congratulations <?php echo "Guest"; ?>! You beat em all..</h3>
				<?php else : ?>
					<h3>Congratulations <?php echo "Guest"; ?>! You did it..</h3>
				<?php endif ?>
				<?php
				echo "You finished in " . $_SESSION['time_taken'] . " seconds with " . $_SESSION['jump_count'] . " jumps";
				?>
				<br><br>
				<button type="button" class="btn btn-primary btn-lg" onclick="location.href='publish.php'">Publish Result</button>
				<button type="button" class="btn btn-primary btn-lg" onclick="location.href='game.php'">Play Again!</button>
			<?php endif ?>
			<!-- guest's first try ends-->
			<!-- guest's multiple try -->
			<?php if ($_SESSION['first_time_try'] == 0) : ?>
				<!--shows congratulation message -->
				<?php if ($_SESSION['time_taken'] < $_SESSION['temp_lowest_time_taken']) : ?>
					<h3>Congratulations <?php echo "Guest"; ?>! New personal high score..</h3>
				<?php endif ?>
				<!-- ^^^ -->
				<!--shows consolation message -->
				<?php if ($_SESSION['time_taken'] > $_SESSION['temp_lowest_time_taken']) : ?>
					<h3>Good game <?php echo "Guest"; ?>. You can do better..</h3>
				<?php endif ?>
				<!-- ^^^ -->
				<!--shows current score -->
				<?php
				echo "You finished in " . $_SESSION['time_taken'] . " seconds with " . $_SESSION['jump_count'] . " jumps";
				?><br>
				<!-- ^^^ -->
				<!--shows previous high-score -->
				<?php if ($_SESSION['time_taken'] > $_SESSION['temp_lowest_time_taken']) : ?>
					Previous high score :
					<?php
					echo $_SESSION['temp_lowest_time_taken'] . " seconds with " . $_SESSION['temp_lowest_jump_count'] . " jumps";
					?><br>
				<?php endif ?>
				<!-- ^^^ -->
				<br>
				<!--<?php if ($_SESSION['time_taken'] < $_SESSION['temp_lowest_time_taken']) : ?>
				<button type="button" class="btn btn-primary btn-lg" onclick="location.href='publish.php'">Publish Result</button>		
			<?php endif ?>-->
				<button type="button" class="btn btn-primary btn-lg" onclick="location.href='publish.php'">Publish Result</button>
				<button type="button" class="btn btn-primary btn-lg" onclick="location.href='game.php'">Play Again!</button>
			<?php endif ?>
			<!-- guest multiple try ends-->
		<?php endif ?>
		<!-- guest div ends -->
		<!-- leaderboard div -->
		<table class="table" style="width:70%; line-height:45px;margin-top: 40px;">
			<tr>
				<th colspan="4">
					<h2>Leaderboard</h2>
				</th>
			</tr>
			<tr align="center">
				<t>
					<th>Rank</th>
					<th>User</th>
					<th>Best Time</th>
					<th>Jump Count</th>
				</t>
			</tr>
			<?php $rankCount = 1; ?>
			<?php while ($rows = mysqli_fetch_assoc($leaderboard)) {
			?>
				<tr align="center">
					<td><?php echo $rankCount; ?></td>
					<td><?php echo $rows['username'] ?></td>
					<td><?php echo $rows['time_taken'] ?></td>
					<td><?php echo $rows['jump_count'] ?></td>
				</tr>
			<?php
				$rankCount++;
			}
			?>
		</table>
		<!-- leaderboard div ends-->
		<div style="position: absolute;bottom: 10px;
						display:block;text-align: center;width: 100%;left: 0;">
			Made with <span style="color: #e25555;">&#9829;</span> by
			<a href="https://github.com/NazmuMasood" target="_blank">NazmuMasood</a>
		</div>
	</div>
</body>