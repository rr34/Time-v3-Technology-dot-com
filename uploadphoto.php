<?php

require_once "includes/dbh-inc.php";
require_once 'includes/functions-inc.php';

print_r($_FILES);
echo '<br><br>';
$orderUID = array_keys($_POST)[0]; // this is the orderUID from the database to associate the photo with a specific task
echo '<br><br>';

$fileName = $_FILES['file']['name'];
$fileTmpName = $_FILES['file']['tmp_name'];
$fileSize = $_FILES['file']['size'];
$fileError = $_FILES['file']['error'];
$fileType = $_FILES['file']['type'];

$fileExt = explode('.', $fileName);
$fileActualExt = strtolower(end($fileExt));

echo $fileNameNew = array_keys($_POST)[0] . '-' . uniqid() . '.' . $fileActualExt;
$fileDestination = 'uploadedphotos/' . $fileNameNew;
move_uploaded_file($fileTmpName, $fileDestination);

// now store the file name followed by a comma in the database using the UID
recordImage($orderUID, $fileDestination);