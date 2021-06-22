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

function recordPhotoUpload($orderUID, $fileDestination) {

	// get the old value in order to append the new after comma
	$sql = "SELECT photos FROM workorders WHERE orderUID = '" . $orderUID . "'";
	$conn = connectdb();
	$oldValue = mysqli_query($conn, $sql); // get the mysqli result object
	$oldValue = mysqli_fetch_array($oldValue); // convert the result object to array
	$oldValue = $oldValue["photos"]; // get the value from the array

	$sql = "UPDATE workorders SET photos = ? WHERE orderUID = ?;";
	$updateField =  $oldValue . $fileDestination . ",";

	// update the photo field with the old value plus the new photo location plus comma
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
	 	header("location: ../signup.php?error=stmtfailed");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "ss", $updateField, $orderUID);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
	return;
}