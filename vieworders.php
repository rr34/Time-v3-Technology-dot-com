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
?>

<h1>

<?php
	// display appropriate header
	// TODO get the pure html out of the echo statements and separate the php code
	if ($usertype == "admin") {
		echo "View and Edit Order";
	} else {
		echo "View Order";
	}
?>

</h1>

<!-- establish the form-->
<form action="updateorder.php" method="post" id="editorder">

<?php
	// display header information for the displayed data, establish the form in order to edit data
	echo "<label for='customeremail'>Customer email: </label>
	<input type='text' name='customeremail' value=" . $customeremail . " style='width:30em;'>";
?>

</form>

<!--more table header information and establish the variables to build the table-->
<?php
	if ($querycriteria == "orderNumber") {
		echo "Order #" . $queryvalue . "<br>";
	}
	// the table data starts here by establishing variables and querying the database
	// array of the readable field names of the workorders database
	$fieldNames = array( 'ID', 'Customer Name', 'Customer Email', 'Order Number', 'Request / Response', 'Quote', 'Discount', 'Tax', 'Billed', 'Status', 'Paid', 'Photos', 'Notes' );
	// select which fields (columns) to display
	if 	($usertype == "admin" || $usertype == "customer") {
		$fieldsDisplay = array( 4, 5, 6, 7, 8, 9, 10, 12);
	}
	
	// query the entire workorders table for all the rows matching the requested criteria. email different bc quotes required
	if ($querycriteria == "customerEmail") {
		$sql = "SELECT * FROM workorders WHERE '" . $querycriteria . " = " . $queryvalue . "'"; // if requesting by email, need quotes around the email bc of the @ symbol
	} else {
		$sql = "SELECT * FROM workorders WHERE " . $querycriteria . " = " . $queryvalue;
	}
	$conn = connectdb();
	$result = mysqli_query($conn, $sql);
	// TODO row of totals
	
	mysqli_close($conn);
?>

<!--build the table from the results starting with the header-->
<div class='table-wrapper'>
	<table class='order-table' name='ordertable'>
		<thead>

<?php
	if($result) {
		// put the appropriate field names in the header
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
	} else {
		echo "no results obtained from the database. No table to create";
		// need to exit here to prevent running the row code below
	}
?>

		</thead>

<!--populate the several table rows from the database query-->
<?php
	if($result) {
		// while loop runs through $result object row by row until there are no more rows
		while($singleRow = mysqli_fetch_row($result)) {
			echo '<tr>';
			// get each column (selected by fieldsDisplay) of the results row one by one
			foreach($fieldsDisplay as $i) {
				if($i == 4) { // 4 is the request box that has different formatting
					echo "<td class='requestbox'>" . $singleRow[$i] . "</td>";
				} else {
					echo "<td>" . $singleRow[$i] . "</td>";
				}
					
			}
			// allow some users to upload photos
			if ($usertype == "admin") {
				echo "<td><form action='uploadphoto-inc.php' method='POST' enctype='multipart/form-data'><input type='file' name='file'><button type='submit' name='" . $singleRow[0] . "'>Upload Photo</button></form></td>";
			}
			echo '</tr>';
		}
	} else {
		echo "no results obtained from the database. No table to create";
	}
?>

	</table>
</div>

<!--display the photos from the same query-->
<?php
	if($result) {
		// get photos row by row, if any, possibly multiple.
		mysqli_data_seek($result,0); // required to "reset the data pointer" and use the while loop again
		while($singleRow = mysqli_fetch_row($result)) {
			// if there is a photo file listed
			if($singleRow[11]) {
				// can be multiple photos per row
				$photoList = explode(',' , $singleRow[11]);
				for($i = 0; $i < count($photoList); $i++) {
					echo "<img src=" . $photoList[$i] . "><br><br>";
				}
			}
		}
	}

// close with the footer as usual and end the file in php
include_once 'footer.php';