<?php
session_start();
include('connection.php');

//handles log-out button
if (isset($_GET['logout'])) {
  session_destroy();
  unset($_SESSION['username']);
  header("location: game.php");
}

$query = "SELECT * from users where time_taken IS NOT NULL ORDER BY time_taken ASC";
$result = mysqli_query($db, $query);
?>
<!DOCTYPE html>
<html>

<head>
  <title>Leaderboard</title>
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
      <div style="clear: right;"></div>
    <?php endif ?>
    <!-- logout button div ends -->
    <!-- login button div -->
    <?php if (!isset($_SESSION['username'])) : ?>
      <div style="float: right;">&nbsp;&nbsp;&nbsp;
        <a href="login_updt.php" style="color: red;">Login</a>
      </div>
      <div style="clear: right;"></div>
    <?php endif ?>
    <!-- login button div ends -->
    <table align="center" class="table" style="width:70%; line-height:45px;margin-top: 40px;">
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
      <?php while ($rows = mysqli_fetch_assoc($result)) {
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
    <br>
    <button type="button" class="btn btn-primary btn-lg" onclick="location.href='game.php'" style="margin-left: 167px;">Play Again!</button>
    <div style="position: absolute;bottom: 10px;
						display:block;text-align: center;width: 100%;left: 0;">
      Made with <span style="color: #e25555;">&#9829;</span> by
      <a href="https://github.com/NazmuMasood" target="_blank">NazmuMasood</a>
    </div>
  </div>
</body>