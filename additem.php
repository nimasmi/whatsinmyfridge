<?php
	require_once "common.php";

#FIXME: authentication, duplicates
	if (isset($_REQUEST["Title"])) {
		$stmt = $mysqli->prepare ("INSERT INTO items (ItemTypeID, ShelfID, Title) VALUES (?, ?, ?)");
		$stmt->bind_param ("iis", $_REQUEST["ItemTypeID"], $_REQUEST["ShelfID"], $_REQUEST["Title"]);
		$stmt->execute ();
		header ("Location: item.php?ID=".$mysqli->insert_id);
	}
?>
<html>
<body>
<form method="post" action="additem.php">
<input type="hidden" name="ShelfID" value="<?php print $_REQUEST["ShelfID"]; ?>">
<?php
	$stmt = $mysqli->prepare ("SELECT ID, Title FROM itemtypes ORDER BY Title ASC;");
	$stmt->execute ();
	$stmt->bind_result ($id, $title);
?>
Item type: 
<select name="ItemTypeID">
<?php
	while ($stmt->fetch()) {
?>
	<option value="<?php print $id; ?>"><?php print $title; ?></option>
<?php
	}
?>
</select><br>
Item title: <input type="text" name="Title"><br>
<input type="submit" value="Add item">
</form>
</body>
</html>
