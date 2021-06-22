<?php

require_once "includes/dbh-inc.php";
require_once 'includes/functions-inc.php';

$orderUID = array_keys($_POST)[0]; // in vieworders.php, upload button has the name of the orderUID from the database to associate the photo with a specific task

$fileName = $_FILES['file']['name']; // this is the name of the file when on the user's computer before upload
$fileTmpName = $_FILES['file']['tmp_name'];
$fileSize = $_FILES['file']['size'];
$fileError = $_FILES['file']['error'];
$fileType = $_FILES['file']['type'];

$fileExt = explode('.', $fileName);
$fileExt = strtolower(end($fileExt));

// get the orderNumber to name file better
$sql = "SELECT orderNumber FROM workorders WHERE orderUID = '" . $orderUID . "'";
$conn = connectdb();
$orderNumber = mysqli_query($conn, $sql); // get the mysqli result object
$orderNumber = mysqli_fetch_array($orderNumber); // convert the result object to array
$orderNumber = $orderNumber["orderNumber"]; // get the value from the array



$fileNameNew = $orderNumber . "-" . $orderUID . "-" . uniqid() . "." . $fileExt;
$fileDestination = 'uploadedphotos/' . $fileNameNew;
move_uploaded_file($fileTmpName, $fileDestination);

// now store the file name followed by a comma in the database using the UID
recordPhotoUpload($orderUID, $fileDestination);