<?php 
try {
	$host="localhost";
	$user="root";
	$passwd="";
	$bd="mytest_login_finalproject";
	$dsn = 'mysql:host='.$host.';dbname='.$bd;
	
	$connect = new PDO($dsn,$user,$passwd);
} catch (PDOException $e) {
	print("Database error occured:").$e->getMessage()."<br>";
	exit();
}
?>