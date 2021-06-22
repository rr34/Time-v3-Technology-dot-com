<?php
	include_once 'header.php';
?>

<div class='service-page'>

<h1>Watch Battery Replacement</h1>
<p>Watches with batteries are called "quartz" watches because they use an oscillating piece of quartz to keep time.</p>
<div class='order-checklist'>
	<div class='order-checklist-form'>
	<form action='includes/neworder-inc.php' method='post' id='neworder'>
		<label for='email'>Customer email: </label>
		<input type='text' name='customeremail' placeholder='Email...' required>
	</div>
	<div>
	<table class='order-checklist-table' name='checklisttable'>
		<thead>
			<th style='width:75%'>Request</th>
			<th style='width: 5em'>Quote</th>
			<th>Include Service?</th>
		</thead>
		<tr>
			<td><input type='text' form='neworder' name='request1' class='requestbox' readonly='readonly' value='Watch Battery Replacement'></td>
			<td>$<input type='text' form='neworder' name='price1' class='pricebox' readonly='readonly' value='15'></td>
			<td><input type='checkbox' form='neworder' name='check1' checked></td>
		</tr>
		<tr>
			<td><input type='text' form='neworder' name='request2' class='requestbox' value='Record movement model and battery type'></td>
			<td>$<input type='text' form='neworder' name='price2' class='pricebox' readonly='readonly' value='0'></td>
			<td><input type='checkbox' form='neworder' name='check2' checked></td>
		</tr>
		<tr>
			<td><input type='text' form='neworder' name='request3' class='requestbox' value='Size customer wrist, adjust band'></td>
			<td>$<input type='text' form='neworder' name='price3' class='pricebox' readonly='readonly' value='0'></td>
			<td><input type='checkbox' form='neworder' name='check3' checked></td>
		</tr>
		<tr>
			<td><input type='text' form='neworder' name='request4' class='requestbox' value='Record watch dimensions: case lugs width, OD, thickness, watch weight'></td>
			<td>$<input type='text' form='neworder' name='price4' class='pricebox' readonly='readonly' value='0'></td>
			<td><input type='checkbox' form='neworder' name='check4' checked></td>
		</tr>
		<tr>
			<td><input type='text' form='neworder' name='request5' class='requestbox' value='Video water-proof test'></td>
			<td>$<input type='text' form='neworder' name='price5' class='pricebox' readonly='readonly' value='15'></td>
			<td><input type='checkbox' form='neworder' name='check5' checked></td>
		</tr>
	</table>
	</div>
	<button form='neworder' type='submit' name='initiate'>Initiate order</button>
</div>

</div>
	
<div class='past-examples'>
	<p>[past examples ...]</p>
</div>

<?php
	include_once 'footer.php';
?>
