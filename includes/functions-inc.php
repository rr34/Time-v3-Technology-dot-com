<?php

function createOrder($orderNumber, $customerEmail, $request, $price) {
	$sql = "INSERT INTO workorders (ordernumber, customerEmail, requestResponse, quote) VALUES (?, ?, ?, ?);";

	// establish connection to db and set variable
	$conn = connectdb();

	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
	 	header("location: ../signup.php?error=stmtfailed");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "ssss", $orderNumber, $customerEmail, $request, $price);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
	return;
}

function recordImage($orderUID, $fileLocation) {
	$sql = "UPDATE workorders SET photos = ? WHERE orderUID = ?;";
	$fileLocation = $fileLocation . ',';

	// establish connection to db and set variable
	$conn = connectdb();

	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
	 	header("location: ../signup.php?error=stmtfailed");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "ss", $fileLocation, $orderUID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
	return;
}