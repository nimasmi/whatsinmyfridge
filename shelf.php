<?php
	require_once "common.php";
#FIXME: authentication

	$stmt = $mysqli->prepare ("SELECT ID, Title FROM items WHERE ShelfID = ?");
	$stmt->bind_param ("i", $_REQUEST["ID"]);
	$stmt->execute ();
	$stmt->bind_result ($id, $title);
?>
<a href="additem.php?ShelfID=<?php print $_REQUEST["ID"]; ?>">Add item</a><br>
<?php
	while ($stmt->fetch()) {
?>
<a href="item.php?ID=<?php print $id; ?>"><?php print $title; ?></a><br>
<?php
	}
?>
