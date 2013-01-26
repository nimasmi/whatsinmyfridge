<?php
	require_once "common.php";

#FIXME: authentication, duplicates
	if (isset($_REQUEST["Title"])) {
		$stmt = $mysqli->prepare ("INSERT INTO rooms (LabID, Title) VALUES (?, ?)");
		$stmt->bind_param ("ss", $_REQUEST["LabID"], $_REQUEST["Title"]);
		$stmt->execute ();
		header ("Location: room.php?ID=".$mysqli->insert_id);
	}
?>
<html>
<body>
<form method="post" action="addroom.php">
<input type="hidden" name="LabID" value="<?php print $_REQUEST["LabID"]; ?>">
Room title: <input type="text" name="Title"><br>
<input type="submit" value="Add room">
</form>
</body>
</html>
