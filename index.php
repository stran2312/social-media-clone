<?php
$con = mysqli_connect("localhost","root","","social");

if(mysqli_connect_errno()){
	echo "Failed to connect: ".mysqli_connect_errno(); 
}
$query = mysqli_query($con, "INSERT INTO test VALUES('','REece')");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Welcome to Swizz Feed</title>
</head>
<body>

</body>
</html>