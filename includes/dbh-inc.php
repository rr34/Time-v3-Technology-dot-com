<?php

// probably bad practice to make this an "callable" function because gives access to db??
function connectdb () {
	$servername = "localhost";
	$dBUsername = "root";
	$dBPassword = "";
	$dBName = "timev3technologydotcom";


	$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
	return $conn;

	if (!$conn) {
		die("Connection failed: ".mysqli_connect_error());
}
}
