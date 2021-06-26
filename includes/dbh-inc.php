<?php

// probably bad practice to make this an "callable" function because gives access to db??
function connectdb () {
	$servername = 'localhost';
	$dBUsername = 'timev3technologywebsite';
	$dBPassword = 'Hiatus32Hiatus32';
	$dBName = 'timev3technologydotcom';


	$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
	return $conn;

	if (!$conn) {
		die("Connection failed: ".mysqli_connect_error());
}
}
