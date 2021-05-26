<?php 
session_start();
include('connection.php');
if (isset($_SESSION['username'])) {
	$username = $_SESSION['username'];
	$time_taken = $_SESSION['time_taken']; 
	$jump_count = $_SESSION['jump_count'];
	//logged in user's score update
	if (!isset($_SESSION['do_help_guest_publish'])) {
		$result = mysqli_query($db,"UPDATE `users` SET `time_taken`='$time_taken',`jump_count`='$jump_count' WHERE `username` = '$username'") or die($query."<br/><br/>".mysqli_error($db));
	}
	//handles previously registered user's score after logging in
	elseif ($_SESSION['do_help_guest_publish'] == 1) {
		unset($_SESSION['do_help_guest_publish']);
		$result = mysqli_query($db,"SELECT `time_taken` FROM `users` WHERE `username` = '$username'") or die($query."<br/><br/>".mysqli_error($db));
		$row = mysqli_fetch_array($result); 
        $previous_best_time_taken = $row[0]; 
        if ($time_taken<$previous_best_time_taken) {
        	$result = mysqli_query($db,"UPDATE `users` SET `time_taken`='$time_taken',`jump_count`='$jump_count' WHERE `username` = '$username'") or die($query."<br/><br/>".mysqli_error($db));
        }
	}
	//handles newly registered user's score after logging in
	elseif ($_SESSION['do_help_guest_publish'] == 2) {
		unset($_SESSION['do_help_guest_publish']);
		$result = mysqli_query($db,"UPDATE `users` SET `time_taken`='$time_taken',`jump_count`='$jump_count' WHERE `username` = '$username'") or die($query."<br/><br/>".mysqli_error($db));
	}
	header('location: leaderboard.php');
}
else{
	$_SESSION['help_guest_publish'] = 1;
	header('location: register_updt.php');
}
?>