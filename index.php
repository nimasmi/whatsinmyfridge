<?php
	require_once "common.php";

	if (!isset($_SESSION["UserID"])) {
		header ("Location: login.php");
		exit;
	}
?>
<html>
<body>
<a href="addlab.php">Add a lab</a><br>
<a href="viewlab.php">Labs</a><br>
<a href="login.php?logout=1">Logout</a>
</body>
</html>

