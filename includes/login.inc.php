<?php 
if(isset($_POST['submit'])){
	require 'connectDB.inc.php';

	$username=filter_var(trim($_POST['username']),FILTER_SANITIZE_STRING);
	$password=filter_var(trim($_POST['password']),FILTER_SANITIZE_STRING);
	$sql="SELECT * FROM users 
        WHERE firstname = ? AND password = ?";

	$statement= $connect->prepare($sql);

	$statement->bindParam(1,$username,PDO::PARAM_STR);
	$statement->bindParam(2,$password,PDO::PARAM_STR);
	if(!$statement->execute(array($username,$password))){
		$statement=NUll;
		header("location:../index.php?error=stmtfailed");
		exit();
	}
	if($statement->rowCount()==0){
		$statement=NUll;
		header("location:../index.php?error=usernotfound");
		exit();
	}
	$user=$statement->fetch(PDO::FETCH_OBJ);
	
    session_start();
	$_SESSION['username']=$user->firstname;
	$_SESSION['userid']=$user->idUser;
	$statement->closeCursor();
	
    header("location:../index.php?logedin");

}
 ?>
