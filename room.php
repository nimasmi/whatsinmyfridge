<?php
	require_once "common.php";
#FIXME: authentication, shelf types
	$stmt = $mysqli->prepare ("SELECT rooms.Title, labs.Title FROM rooms INNER JOIN labs ON rooms.LabID = labs.ID WHERE rooms.ID=?");
	$stmt->bind_param ("i", $_REQUEST["ID"]);
	$stmt->execute ();
	$stmt->bind_result ($title, $lab);
	$stmt->fetch();
?>
<html>
<body>
Room: <?php print $title; ?> (<?php print $lab; ?>)<br>
<a href="addshelf.php?RoomID=<?php print $_REQUEST["ID"]; ?>">Add shelf</a><br>
<?php
	$stmt->close();

	$stmt = $mysqli->prepare ("SELECT shelves.ID, shelves.Title, shelftypes.Title FROM shelves INNER JOIN shelftypes ON shelves.ShelfTypeID=shelftypes.ID WHERE shelves.RoomID=? ORDER BY shelftypes.Title ASC, shelves.Title ASC");
	$stmt->bind_param ("i", $_REQUEST["RoomID"]);
	$stmt->execute ();
	$stmt->bind_result ($id, $title, $type);
	while ($stmt->fetch()) {
?>
<a href="shelf.php?ID=<?php print $id; ?>"><?php print $title; ?></a><br>
<?php
	}
?>
</body>
</html>
