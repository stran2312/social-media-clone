<?php
require 'config.php';

if(isset($_SESSION['username'])) {
	$userLoggedIn = $_SESSION['username'];
	$user_detail_query = mysqli_query($con, "SELECT * FROM users WHERE user_name='$userLoggedIn'");
	$user = mysqli_fetch_array($user_detail_query);
} else {
	header("Location: register.php");
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<!-- css -->
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
	<title>Welcome to Swizz Feed</title>
</head>
<body>
	<div class="top_bar">
		<div class="logo">
			<a href="index.php">Swizz Feed</a>
		</div>
		<nav>
			<a href="#">
				<?php echo $user['first_name']; ?>
			</a>
			<a href="#"><i class="fa fa-home" fa-lg></i></a>
			<a href="#"><i class="fa fa-envelope" fa-lg></i></a>
			<a href="#"><i class="fa fa-bell-o" fa-lg></i></a>
			<a href="#"><i class="fa fa-user-o" fa-lg></i></a>
			<a href="#"><i class="fa fa-cog" fa-lg></i></a>
		</nav>
	</div>
</body>
</html>
