<?php
	require_once "common.php";
#FIXME: authentication

	if (isset($_REQUEST["ID"])) {
		$stmt = $mysqli->prepare ("SELECT Title, Address, Postcode FROM labs WHERE ID = ?");
		$stmt->bind_param ("i", $_REQUEST["ID"]);
		$stmt->execute ();
		$stmt->bind_result ($title, $address, $postcode);
		if ($stmt->fetch()) {
?>
<?php print $title; ?><br>
<?php print $address; ?><br>
<?php print $postcode; ?><br>
<?php
			$stmt->close ();
			$stmt = $mysqli->prepare ("SELECT ID, Title FROM rooms WHERE LabID = ? ORDER BY Title ASC");
			$stmt->bind_param ("i", $_REQUEST["ID"]);
			$stmt->execute();
			$stmt->bind_result ($id, $title);
			while ($stmt->fetch()) {
?>
<a href="room.php?ID=<?php print $id; ?>"><?php print $title; ?></a><br>
<?php
			}
?>
<br>
<a href="addroom.php?LabID=<?php print $_REQUEST["ID"]; ?>">Add room</a>
<?php
		}
	} else {
		$stmt = $mysqli->prepare ("SELECT ID, Title, Address, Postcode FROM labs ORDER BY Title ASC;");
		$stmt->execute ();
		$stmt->bind_result ($id, $title, $address, $postcode);
		while ($stmt->fetch()) {
?>
<a href="viewlab.php?ID=<?php print $id; ?>"><?php print $title; ?></a><br>
<?php print $address; ?><br>
<?php print $postcode; ?><br>
-<br>
<?php
		}
	}
?>
