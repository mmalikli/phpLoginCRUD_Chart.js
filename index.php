<?php
    session_start();
     if(isset($_SESSION['username'])){
         header('location: table.php');
     }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Login Page</title>
<link rel="stylesheet" href="css/main.css"> 
</head>

<body>
    <h2> Login form</h2>
	<form action="includes/login.inc.php" method="POST">
		<div class="container">
		  <label><b>Firstname</b></label>
		  <input type="text" placeholder="Enter First Name" name="username">
		  <label> <b> Enter Password </b></label>
		  <input type="password" placeholder="Enter Password" name="password">
		  <button type="submit" name="submit"> Login </button>
		</div>
	</form>
</body>
</html>