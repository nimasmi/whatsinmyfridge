<?php
	require_once "common.php";

	if (isset($_REQUEST["Postcode"])) {
		list($lat,$lng) = geocode ($_REQUEST["Postcode"]);
		if (is_null($lat)) {
			print "Could not find postcode\n";
			exit;
		}
		$stmt = $mysqli->prepare ("UPDATE users SET Latitude = ?, Longitude = ? WHERE ID = ?");
		$stmt->bind_param ("ddi", $lat, $lng, $_SESSION["UserID"]);
		$stmt->execute();
		header ("Location: index.php");
		exit;
	}
?>
<html>
<body>
<form method="post" action="location.php">
Postcode of your current location: <input type="text" name="Postcode"><br>
<input type="submit" value="Update my location">
</form>
</body>
</html>
