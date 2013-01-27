<?php
	require_once "common.php";

#FIXME: authentication, duplicates
	if (isset($_REQUEST["Title"])) {
		$stmt = $mysqli->prepare ("INSERT INTO shelves (ShelfTypeID, RoomID, Title) VALUES (?, ?, ?)");
		$stmt->bind_param ("iis", $_REQUEST["ShelfTypeID"], $_REQUEST["RoomID"], $_REQUEST["Title"]);
		$stmt->execute ();
		header ("Location: shelf.php?ID=".$mysqli->insert_id);
	}
?>
<html>
<body>
<form method="post" action="addshelf.php">
<input type="hidden" name="RoomID" value="<?php print $_REQUEST["RoomID"]; ?>">
<?php
	$stmt = $mysqli->prepare ("SELECT ID, Title FROM shelftypes ORDER BY Title ASC;");
	$stmt->execute ();
	$stmt->bind_result ($id, $title);
?>
Shelf type: 
<select name="ShelfTypeID">
<?php
	while ($stmt->fetch()) {
?>
	<option value="<?php print $id; ?>"><?php print $title; ?></option>
<?php
	}
?>
</select><br>
Shelf title: <input type="text" name="Title"><br>
<input type="submit" value="Add shelf">
</form>
</body>
</html>
