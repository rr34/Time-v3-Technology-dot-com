<?php
	include_once 'header.php';

	require_once "includes/dbh-inc.php";
	require_once "includes/functions-inc.php";
	
	// make a variable from the URL for ease of use: types are admin, customer, anonymous
	// TODO make the customeremail and usertype come from a session setting instead of the URL?
	$customeremail = $_GET["customeremail"];
	$usertype = $_GET["usertype"];
	// set variables for which orders to show, these will come from the URL to make the link shareable
	$querycriteria = $_GET["querycriteria"];
	$queryvalue = $_GET["queryvalue"];

	// display appropriate header
	// TODO get the pure html out of the echo statements and separate the php code
	if ($usertype == "admin") {
		echo "<h1>View and Edit Order</h1>";
	} else {
		echo "<h1>View Order</h1>";
	}
	
	// display user data, establish the form in order to edit data, display order number
	echo "<form action='updateorder.php' method='post' id='editorder'>
	<label for='customeremail'>Customer email: </label>
	<input type='text' name='customeremail' value=" . $customeremail . " style='width:30em;'>
	</form>";
	if ($querycriteria == "orderNumber") {
		echo "Order #" . $queryvalue . "<br>";
	}
	
	// array of the readable field names of the workorders database
	$fieldNames = array( 'ID', 'Customer Name', 'Customer Email', 'Order Number', 'Request / Response', 'Quote', 'Discount', 'Tax', 'Billed', 'Status', 'Paid', 'Photos', 'Notes' );

	if 	($usertype == "admin" || $usertype == "customer") {
	$fieldsDisplay = array( 4, 5, 6, 7, 8, 9, 10, 12);
	}
	
	// now query the entire database for all the rows matching the requested orders
	if ($querycriteria == "customerEmail") {
		$sql = "SELECT * FROM workorders WHERE customerEmail = '" . $queryvalue . "'"; // if requesting by email, need quotes around the email bc of the @ symbol
		
	} else {
		$sql = "SELECT * FROM workorders WHERE " . $querycriteria . " = " . $queryvalue;
	}
	
	$conn = connectdb();
	
	// build the table from the results, if there is data
	$result = mysqli_query($conn, $sql);
	if($result) {
		
		echo "<div class='table-wrapper'>";

		// create the table
		if ($usertype == "admin" || $usertype == "customer") {
			echo "<table class='order-table' name='ordertable'>";
		}
		echo "<thead>";
		// create the header with appropriate field names from array
		foreach($fieldsDisplay as $i) {
			if(5 <= $i && $i <= 8) {
				echo "<th class='pricebox'>" . $fieldNames[$i] . "</th>";
			} else {
				echo "<th>" . $fieldNames[$i] . "</th>";
			}
		}
		// allow some users to upload photos
		if ($usertype == "admin") {
			echo "<th>Upload Photo</th>";
		}
		echo '</thead>';

			// now populate the rows of the table with individual components of the order
			// while loop runs through $result object row by row until there are no more rows
			while($singleRow = mysqli_fetch_row($result)) {
				echo '<tr>';
				// now get each column (selected by fieldsDisplay) of the row one by one
				foreach($fieldsDisplay as $i) {
					if($i != 4) { // 4 is the request box that has different formatting
						echo "<td>" . $singleRow[$i] . "</td>";
					} else {
						echo "<td class='requestbox'>" . $singleRow[$i] . "</td>";
					}
						
				}
				// allow some users to upload photos
				if ($usertype == "admin") {
					echo "<td><form action='uploadphoto-inc.php' method='POST' enctype='multipart/form-data'><input type='file' name='file'><button type='submit' name='" . $singleRow[0] . "'>Upload Photo</button></form></td>";
				}
				echo '</tr>';
			}
		echo "</table>";
		echo "</div>";
	}
	
	//now do similar query and build to display the photos
	if($result = mysqli_query($conn, $sql)) {

		// now get photos row by row 
		while($singleRow = mysqli_fetch_row($result)) {
			// if there is a photo file listed
			if($singleRow[11]) {
				// can be multiple photos per row
				$photoList = explode(',' , $singleRow[11]);
				for($i = 0; $i < count($photoList); $i++) {
					echo "<img src=" . $photoList[$i] . ">" . "<br><br>";
				}
			}
		}
	}

	include_once 'footer.php';