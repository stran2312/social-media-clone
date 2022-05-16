<?php 
require 'config.php';

$fname = "";
$lname = "";
$email = "";
$email2 = "";
$password = "";
$password2 = "";
$date = "";
$error_array = array();
// register
if(isset($_POST['register_button'])){
	// Register form values
	$fname = strip_tags($_POST['reg_fname']); // remove html tags
	$fname = str_replace(' ','', $fname); // remove spaces
	$fname = ucfirst(strtolower($fname)); // uppsercase first letter
	$_SESSION['reg_fname'] = $fname; //store first name into session

	// Last name
	$lname = strip_tags($_POST['reg_lname']); // remove html tags
	$lname = str_replace(' ','', $lname); // remove spaces
	$lname = ucfirst(strtolower($lname));
	$_SESSION['reg_lname'] = $lname; //store last name into session

	// email
	$email = strip_tags($_POST['reg_email']); // remove html tags
	$email = str_replace(' ','', $email); // remove spaces
	$email = ucfirst(strtolower($email));
	$_SESSION['reg_email'] = $email; //store email into session

	// confirm email
	$email2 = strip_tags($_POST['reg_email2']); // remove html tags
	$email2 = str_replace(' ','', $email2); // remove spaces
	$email2 = ucfirst(strtolower($email2));
	$_SESSION['reg_email2'] = $email2; //store email2 into session

	// password
	$password = strip_tags($_POST['reg_password']); // remove html tags
	
	//confirm password
	$password2 = strip_tags($_POST['reg_password2']); // remove html tags 

	// get date
	$date = date('Y-m-d');

	// check if emails are the same
	if($email == $email2){
		// check if email is in valid format
		if(filter_var($email,FILTER_VALIDATE_EMAIL)){
			$email = filter_var($email,FILTER_VALIDATE_EMAIL);

			// check if email exists
			$email_check = mysqli_query($con, "SELECT email FROM users WHERE email = '$email'");

			// count number of rouws returned
			$num_rows = mysqli_num_rows($email_check);
			if ($num_rows > 0 ){
				array_push($error_array, "Email already in use<br>");
			}

		} else {
			array_push($error_array, "Invalid Format<br>");
		}

	} else {
		array_push($error_array, "Emails don't match<br>");
	}

	// check first name length
	if (strlen($fname) > 25 || strlen($fname) < 2) array_push($error_array, "First name must be between 2 and 25 characters<br>");
	if (strlen($lname) > 25 || strlen($lname) < 2) array_push($error_array, "Last name must be between 2 and 25 characters<br>");

	// check password
	if ($password != $password2) {
		array_push($error_array, "Passwords don't match<br>");

	} else {
		if (preg_match('/[^A-Za-z0-9]/', $password)) {
			array_push($error_array, "Password can only contain English characters or numbers<br>");
		}
	}

	if(strlen($password) > 30 || strlen($password) < 5) array_push($error_array, "Passwords don't match<br>");

	if(empty($error_array)){
		$password  = md5($password);
		$username = strtolower($fname . "_" . $lname);
		$result  = mysqli_query($con, "SELECT username FROM users where username = '$username'");
		$row_count = mysqli_num_rows($result);
		$i = 0;
		while($row_count != 0){
			$i++;
			$username = $username . "_" . $i;
			$result = mysqli_query($con, "SELECT username FROM users where username = '$username'");
		}

		// profile picture
		$rand = rand(1,2);
		if ($rand == 1)	$profile_pic = "assets/images/profile_pics/defaults/default.jpg";
		else if ($rand == 2) $profile_pic = "assets/images/profile_pics/defaults/default2.jpg";

		array_push($error_array, "<span style='color: #14C800;'>You're all set to login</span><br>");

		// clear session 
		$_SESSION['reg_fname'] = "";
		$_SESSION['reg_lname'] = "";
		$_SESSION['reg_email'] = "";
		$_SESSION['reg_email2'] = "";

	}
}//end regiser

// login
if(isset($_POST['log_button'])) {
	$email = filter_var($_POST['log_email'], FILTER_VALIDATE_EMAIL); // filter email

	$_SESSION['log_email'] = $email; // store email session
	$password = md5($_POST['log_password']); // get password

	$check_database_query = mysqli_query($con, "SELECT * FROM users WHERE email= '$email' AND password = '$password'");
	$check_login_query = mysqli_num_rows($check_database_query);
	if($check_login_query == 1) {
		$row = mysqli_fetch_array($check_database_query);

		$user_closed_query = mysqli_query($con, "SELECT * FROM users where email = '$email' AND user_closed = 'yes'");
		if(mysqli_num_rows($user_closed_query) == 1) $reopen_account = mysqli_query($con, "UPDATE users SET user_closed= 'no' WHERE email = '$email'");

		//store user name session
		$username = $row['username'];
		$_SESSION['username'] = $username;
		header("Location: index.php");
		exit();
	} else {
		array_push($error_array, "Email or password was incorrect<br>");
	}

}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
	<title>Welcome to Swizz Feed</title>
</head>
<body>
	<div class="wrapper">
		<div class="login_box">
			
			<div class="login_header">
				<h2>Swizz Feed</h2>
				Login or signup below!
				
			</div>
			<form action="register.php" method="POST">
				<input type="email" name="log_email" placeholder="Email Address" value="<?php 
					if(isset($_SESSION['log_email'])) echo $_SESSION['log_email'];?>">

				<br>
				<input type="password" name="log_password" placeholder="Password">
				<br>
				<input type="submit" name="log_button" value="Login">
				<br>
				<?php if(in_array("Email or password was incorrect<br>", $error_array)) echo "Email or password was incorrect<br>"; ?>
			</form>

			<form action="register.php" method="POST">
				
				<!-- first name -->
				<input type="text" name="reg_fname" placeholder="First Name" 
				value = "<?php 
					if(isset($_SESSION['reg_fname'])) echo $_SESSION['reg_fname'];
				?>"required>
				<br>
					<!-- display first name error -->
					<?php if(in_array("First name must be between 2 and 25 characters<br>", $error_array)){
						echo "First name must be between 2 and 25 characters<br>";
					} ?>

				<!-- last name -->
				<input type="text" name="reg_lname" placeholder="Last Name" 
				value = "<?php 
					if(isset($_SESSION['reg_lname'])) echo $_SESSION['reg_lname'];
				?>"required>
				<br>
					<!-- display last name error -->
					<?php if(in_array("Last name must be between 2 and 25 characters<br>", $error_array)){
						echo "Last name must be between 2 and 25 characters<br>";
					} ?>

				<input type="email" name="reg_email" placeholder="Email" 
				value = "<?php 
					if(isset($_SESSION['reg_email'])) echo $_SESSION['reg_email'];
				?>"required>
				<br>

				<input type="email" name="reg_email2" placeholder="Confirm Email" 
				value = "<?php 
					if(isset($_SESSION['reg_email2'])) echo $_SESSION['reg_email2'];
				?>"required>	
				<br>
					<!-- display email error -->
					<?php if(in_array("Email already in use<br>", $error_array)) echo "Email already in use<br>";
						  else if(in_array("Invalid Format<br>", $error_array)) echo "Invalid Format<br>";
						  else if(in_array("Emails don't match<br>", $error_array)) echo "Emails don't match<br>";?>

				<!--pass word  -->
				<input type="password" name="reg_password" placeholder="Password" required>
				<br>
				<input type="password" name="reg_password2" placeholder="Confirm Password" required>
				<br>
					<!-- display password error -->
					<?php if(in_array("Passwords don't match<br>", $error_array)) echo "Passwords don't match<br>";
						  else if(in_array("Password can only contain English characters or numbers<br>", $error_array)) echo "Password can only contain English characters or numbers<br>";
						  else if(in_array("Passwords don't match<br>", $error_array)) echo "Passwords don't match<br>";?>

				<input type="submit" name="register_button" value="Register" required>
				<br>
				<?php if(in_array("<span style='color: #14C800;'>You're all set to login</span><br>", $error_array)) echo "<span style='color: #14C800;'>You're all set to login</span><br>"; ?>
			</form>
		</div>
	</div>
</body>
</html>