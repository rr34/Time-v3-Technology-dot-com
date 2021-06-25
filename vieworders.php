<?php
	include_once 'header.php';

	require_once "includes/dbh-inc.php";
	require_once "includes/functions-inc.php";
	
	// make a variable from the URL for ease of use: types are admin, customer, anonymous
	// TODO make the customerEmail and userType come from a session setting instead of the URL?
	$userType = $_GET["userType"];
	// set variables for which orders to show, these will come from the URL to make the link shareable
	$querycriteria = $_GET["querycriteria"];
	if ($querycriteria == "customerEmail") { $querycriteriareadable = "customer email"; }
	if ($querycriteria == "orderNumber") { $querycriteriareadable = "order number"; }
	$queryvalue = $_GET["queryvalue"];
?>

<!--display appropriate header-->
<?php
	echo "<h1>";
	if ($userType == "admin") {
		echo "View and Edit Order";
	} else {
		echo "View Order";
	}
	echo "</h1><br>";
	// display header information for the displayed data
	echo "Showing orders for " . $querycriteriareadable . ": " . $queryvalue . "<br><br>";
?>

<!-- establish the form-->
<form action="includes/vieworders-inc.php" method="post" id="vieworders">
	<label for='querycriteria'>Select orders by: </label>
	<select id='querycriteria' name='querycriteria' value='querycriteria' style='width:12em;'>
		<option value='customerEmail'>Customer Email</option>
		<option value='orderNumber'>Order Number</option>
	</select>
	<label for='queryvalue'> is </label>
	<input type='text' name='queryvalue' style='width:30em;'>
	<input type='submit' value='Search'>
</form>

<!--more table header information and query database to build the table-->
<?php
	// the table data starts here by establishing variables and querying the database
	// select which fields (columns) to display
	if 	($userType == "admin" || $userType == "customer") {
		$fieldsDisplay = "requestResponse as 'Request / Response', quote as 'Quote', discount as 'Discount', tax as 'Tax', billed as 'Billed', status as 'Status', paid as 'Paid', notes as 'Notes', orderUID as 'Upload Photo'";
	}
	// update the database fields
	$conn = connectdb();
	$sql = "UPDATE workorders SET billed = (quote + discount + tax)";
	mysqli_query($conn, $sql);
	$sql = "UPDATE workorders SET owed = billed WHERE paid = 'No'";
	mysqli_query($conn, $sql);
	$sql = "UPDATE workorders SET owed = 0 WHERE paid = 'Paid'";
	mysqli_query($conn, $sql);
	
	// query the entire workorders table for all the rows matching the requested criteria. email different bc quotes required
	if ($querycriteria == "customerEmail") {
		$sql = "SELECT " . $fieldsDisplay . " FROM workorders WHERE " . $querycriteria . " = '" . $queryvalue . "'"; // if requesting by email, need quotes around the email bc of the @ symbol
	} else {
		$sql = "SELECT " . $fieldsDisplay . " FROM workorders WHERE " . $querycriteria . " = " . $queryvalue;
	}
	$result = mysqli_query($conn, $sql);
	
	// find the amount owed on displayed orders
	if ($querycriteria == "customerEmail") {
		$sql = "SELECT sum(owed) FROM workorders WHERE " . $querycriteria . " = '" . $queryvalue . "'"; // if requesting by email, need quotes around the email bc of the @ symbol
	} else {
		$sql = "SELECT sum(owed) FROM workorders WHERE " . $querycriteria . " = " . $queryvalue;
	}
	$resultOwed = mysqli_query($conn, $sql);
	mysqli_close($conn);
	$amountOwed = mysqli_fetch_row($resultOwed)[0];
	
?>

<!--build the table from the results starting with the header-->
<div class='table-wrapper'>
	<table class='order-table' name='ordertable'>
		<thead>

<?php
	if($result) {
		// put the appropriate field names in the header
		$i = 0; // initialize variable to find position of billed in the table
		while ($fieldInfo = mysqli_fetch_field($result)) {
			if ($fieldInfo -> orgname == "quote" || $fieldInfo -> orgname == "discount" || $fieldInfo -> orgname == "tax" || $fieldInfo -> orgname == "billed") {
				echo "<th class='pricebox'>" . $fieldInfo -> name . "</th>";
			} else {
				echo "<th>" . $fieldInfo -> name . "</th>";
			}
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
		mysqli_data_seek($result,0); // required to "reset the data pointer" and use the while loop again
		$totalBilled = 0; // initialize for adding
		while($singleRow = mysqli_fetch_row($result)) {
			// get each column of the results row one by one
			echo "<tr>";
			foreach ($singleRow as $tableData) {
				if ($userType == "admin" && $tableData == end($singleRow)) {
					echo "<td><form action='uploadphoto-inc.php' method='POST' enctype='multipart/form-data'><input type='file' name='file'><button type='submit' name='" . end($singleRow) . "'>Upload Photo</button></form></td>";
				} else {
				echo "<td>" . $tableData . "</td>";
				}
			}
			echo "</tr>";
		}
	}else {
		echo "no results obtained from the database. No table to create";
	}
?>

	</table>
</div>

<div class="showTotal"><h1>
<!--display total owed on orders shown-->
<?php
echo "Total outstanding: $" . $amountOwed . "<br><br>";
?>
</h1></div>

<div class="displayPhotos">
<!--display the photos starting with db query-->
<?php
	
	if ($querycriteria == "customerEmail") {
		$sql = "SELECT photos FROM workorders WHERE " . $querycriteria . " = '" . $queryvalue . "'";
	} else {
		$sql = "SELECT photos FROM workorders WHERE " . $querycriteria . " = " . $queryvalue;
	}

	$conn = connectdb();
	$result = mysqli_query($conn, $sql);
	mysqli_close($conn);

	if($result) {
		// get photos row by row, if any, possibly multiple.
		while($singleRow = mysqli_fetch_array($result)) {
			// if there is at least one photo file listed
			if($singleRow["photos"]) {
				// can be multiple photos per row
				$photoList = explode(',' , $singleRow["photos"]);
				for($i = 0; $i < count($photoList); $i++) {
					echo "<img src=" . $photoList[$i] . "><br><br>";
				}
			}
		}
	}

?>
</div>

<?php
// close with the footer as usual and end the file in php
include_once 'footer.php';