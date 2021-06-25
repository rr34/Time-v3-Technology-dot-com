<?php
	$querycriteria = $_POST["querycriteria"];
	$queryvalue = $_POST["queryvalue"];
	header("location: ../vieworders.php?userType=admin" . "&querycriteria=" . $querycriteria . "&queryvalue=" . $queryvalue);
