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
		$_SESSION["Latitude"] = $lat;
		$_SESSION["Longitude"] = $lng;
		session_write_close ();
		header ("Location: index.php");
		exit;
	}

	common_header ();
?>
<form method="post" action="location.php">
Postcode of your current location: <input type="text" name="Postcode"><br>
<input type="submit" value="Update my location">
</form>
<?php
	common_footer ();
?>
