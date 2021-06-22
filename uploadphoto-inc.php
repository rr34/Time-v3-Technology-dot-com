<?php

require_once "includes/dbh-inc.php";
require_once "includes/functions-inc.php";

$orderUID = array_keys($_POST)[0]; // in vieworders.php, upload button has the name of the orderUID from the database to associate the photo with a specific task

$fileName = $_FILES['file']['name']; // this is the name of the file when on the user's computer before upload
$fileTmpName = $_FILES['file']['tmp_name'];
$fileSize = $_FILES['file']['size'];
$fileError = $_FILES['file']['error'];
$fileType = $_FILES['file']['type'];

$fileExt = explode('.', $fileName);
$fileExt = strtolower(end($fileExt));

// get the orderNumber to name file better, then 
$sql = "SELECT * FROM workorders WHERE orderUID = '" . $orderUID . "'";
$conn = connectdb();
$result = mysqli_query($conn, $sql); // get the mysqli result object
$result = mysqli_fetch_array($result); // convert the result object to array
$orderNumber = $result["orderNumber"]; // get the order number from the array
$customerEmail = $result["customerEmail"]; // get the customer email from the array just for the header when we return to view the order
mysqli_close($conn);

// generate the file name and path, then move the uploaded file there
$fileNameNew = $orderNumber . "-" . $orderUID . "-" . uniqid() . "." . $fileExt;
$fileDestination = 'uploadedphotos/' . $fileNameNew;
move_uploaded_file($fileTmpName, $fileDestination);

// now record the file name in the database using the UID
recordPhotoUpload($orderUID, $fileDestination);

// return to view the order with the uploaded photo
header("location: vieworders.php?customeremail=" . $customerEmail . "&usertype=admin" . "&querycriteria=orderNumber&queryvalue=" . $orderNumber);