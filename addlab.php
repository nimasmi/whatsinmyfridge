<?php
	require_once "common.php";

	if (isset($_REQUEST["Title"])) {
		#FIXME: duplicate check
		list ($lat, $lng) = geocode ($_REQUEST["Postcode"]);
		$stmt = $mysqli->prepare ("INSERT INTO labs (InstitutionID, Title, Address, Postcode, Latitude, Longitude) VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->bind_param ("isssdd", $_REQUEST["InstitutionID"], $_REQUEST["Title"], $_REQUEST["Address"], $_REQUEST["Postcode"], $lat, $lng);
		$stmt->execute();
		header ("Location: viewlab.php?ID=".$mysqli->insert_id);
	}
?>
<html>
<body>
<form method="post" action="addlab.php">
<?php
	$stmt = $mysqli->prepare ("SELECT ID, Title FROM institutions ORDER BY Title ASC;");
	$stmt->execute ();
	$stmt->bind_result ($id, $title);
?>
Institution: 
<select name="InstitutionID">
<?php
	while ($stmt->fetch()) {
?>
	<option value="<?php print $id; ?>"><?php print $title; ?></option>
<?php
	}
?>
</select><br>
Title: <input type="text" name="Title"><br>
Address: <input type="text" name="Address"><br>
Postcode: <input type="text" name="Postcode"><br>
<input type="submit" value="Add lab">
</form>
</body>
</html>
