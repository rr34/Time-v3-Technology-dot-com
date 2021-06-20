<?php
	include_once 'header.php';


	require_once "includes/dbh-inc.php";
	require_once "includes/functions-inc.php";
	
	// set type of user: 2 is admin, 1 is customer, 0 is anonymous
	$permission = 2;

	// display appropriate header
	if ($permission == 2) {
		echo "<h1>View and Edit Orders</h1>";
	} else {
		echo "<h1>View Orders</h1>";
	}
	
	// display user data, establish the form in order to edit data, display order number
	echo "<form action='updateorder.php' method='post' id='editorder'>
	<label for='customeremail'>Customer email: </label>
	<input type='text' name='customeremail' value=" . $_GET['customeremail'] . " style='width:30em;'>
	</form>";
	echo "Order #" . $_GET['ordernumber'] . "<br>";
	
	// array of the readable field names of the database
	$fieldNames = array( 'ID', 'Customer Name', 'Customer Email', 'Order Number', 'Request / Response', 'Quote', 'Discount', 'Tax', 'Billed', 'Status', 'Paid', 'Photos', 'Notes' );

	if 	($permission == 2 || $permission == 1) {
	$fieldsDisplay = array( 4, 5, 6, 7, 8, 9, 10, 12);
	}
	
	// now query the database for all the rows with the requested order number
	$sql = 'SELECT * FROM workorders WHERE orderNumber = ' . $_GET['ordernumber'];
	$conn = connectdb();
	
	// first ensure there is data
	if($result = mysqli_query($conn, $sql)) {

		echo "<div class='table-wrapper'>";

		// create the table
		if ($permission == 2 || $permission == 1) {
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
		if ($permission == 2) {
			echo "<th>Upload Photo</th>";
		}
		echo '</thead>';

			// while loop runs through $result object row by row until there are no more rows
			while($singleRow = mysqli_fetch_row($result)) {
				echo '<tr>';
				// get each column one by one
				foreach($fieldsDisplay as $i) {
					if($i != 4) {
						echo "<td>" . $singleRow[$i] . "</td>";
					} else {
						echo "<td class='requestbox'>" . $singleRow[$i] . "</td>";
					}
						
				}
				if ($permission == 2) {
					echo "<td><form action='uploadphoto.php' method='POST' enctype='multipart/form-data'><input type='file' name='file'><button type='submit' name='" . $singleRow[0] . "'>Upload Photo</button></form></td>";
				}
				echo '</tr>';
			}
		echo "</table>";
		echo "</div>";
	}

	include_once 'footer.php';