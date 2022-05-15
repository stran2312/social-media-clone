<?php 
session_start();
$con = mysqli_connect("localhost","root","","social");

if(mysqli_connect_errno()){
	echo "Failed to connect: ".mysqli_connect_errno(); 
}
$fname = "";
$lname = "";
$email = "";
$email2 = "";
$password = "";
$password2 = "";
$date = "";
$error_array = array();

if(isset($_POST['register_button'])){
	// Register form values
	$fname = strip_tags($_POST['reg_fname']); // remove html tags
	$fname = str_replace(' ','', $fname); // remove spaces
	$fname = ucfirst(strtolower($fname)); // uppsercase first letter
	$_SESSION['reg_fname']; //store first name into session

	// Last name
	$lname = strip_tags($_POST['reg_lname']); // remove html tags
	$lname = str_replace(' ','', $lname); // remove spaces
	$lname = ucfirst(strtolower($lname));
	$_SESSION['reg_lname']; //store last name into session

	// email
	$email = strip_tags($_POST['reg_email']); // remove html tags
	$email = str_replace(' ','', $email); // remove spaces
	$email = ucfirst(strtolower($email));
	$_SESSION['reg_email']; //store email into session

	// confirm email
	$email2 = strip_tags($_POST['reg_email2']); // remove html tags
	$email2 = str_replace(' ','', $email2); // remove spaces
	$email2 = ucfirst(strtolower($email2));
	$_SESSION['reg_email2']; //store email2 into session

	// password
	$password = strip_tags($_POST['reg_password']); // remove html tags
	
	//confirm password
	$password2 = strip_tags($_POST['reg_password2']); // remove html tags 

	// get date
	$date = date('Y-m-d');

	// check if emails are the same
	if(email == email2){
		// check if email is in valid format
		if(filter_var($email,FILTER_VALIDATE_EMAIL)){
			$email = filter_var($email,FILTER_VALIDATE_EMAIL);

			// check if email exists
			$email_check - mysqli_query($con, "SELECT email FROM users WHERE email = '$email'");

			// count number of rouws returned
			$num_rows = mysqli_num_rows($email_check);
			if (num_rows > 0 ){
				array_push($error_array, "Email already in use<br>");
			}

		} else {
			array_push($error_array, "Invalid Format<br>");
		}

	} else {
		array_push($error_array, "Emails don't match<br>");
		}
	} // end else

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




?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Welcome to Swizz Feed</title>
</head>
<body>
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

	</form>
</body>
</html>