<?php

if (isset($_POST['initiate'])) {

	// we need the database handler and the functions
	require_once "dbh-inc.php";
	require_once "functions-inc.php";
	
	// get form data sent by the post method (POST instead of GET, which would be in the URL)
	$customerEmail = $_POST["customeremail"];
	
	// generate an order number, unless the user set an appropriate number when order was initiated
	if (strlen($_POST['ordernumber']) == 12) {
		$orderNumber = $_POST['ordernumber'];
	} else {
		$orderNumber = date("YmdHi");
	}
	
	$somethingwrong = false; // just to make the verification placeholder look intuitive
	if ( $somethingwrong !== false ) {
		// give an error message
		exit();
		}
	
	for ($i=1; $i<=5; $i++) {
		if ($_POST["check" . (string)$i]) {
			$request = $_POST["request" . (string)$i];
			$price = (float) $_POST['price' . (string)$i];

			createOrder($orderNumber, $customerEmail, $request, $price);
		}

	}
		header("location: ../vieworders.php?customeremail=" . $customerEmail . "&usertype=admin" . "&querycriteria=orderNumber&queryvalue=" . $orderNumber);

} else {
	header("location: ../watchbatteryreplace.php");
    exit();
}