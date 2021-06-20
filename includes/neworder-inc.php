<?php

echo $_POST['request1'];
echo "<br><br>";
print_r ($_POST);

if (isset($_POST['initiate'])) {

	// we need the database handler and the functions
	require_once "dbh-inc.php";
	require 'functions-inc.php';
	
	// generate an order number
	$orderNumber = date("YmdHi");

	// get the form data sent by the post method (instead of get, which would be in the URL)
	$customerEmail = $_POST['customeremail'];
	
	$somethingwrong = false; // just to make the verification placeholder look intuitive
	if ( $somethingwrong !== false ) {
		// give an error message
		exit();
		}
	

	for ($i=1; $i<=5; $i++) {
	$request = $_POST['request' . (string)$i];
	$price = (float) $_POST['price' . (string)$i];

/*
	echo "<br><br>";
	echo $i;
	echo "<br><br>";
	echo $_POST['request' . (string)$i];
*/

	createOrder($orderNumber, $customerEmail, $request, $price);
	header('location: ../vieworders.php?customeremail=' . $customerEmail . '&ordernumber=' . $orderNumber);

	}

} else {
	header("location: ../watchbatteryreplace.php");
    exit();
}
