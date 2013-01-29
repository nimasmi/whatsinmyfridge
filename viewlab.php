<?php
	require_once "common.php";
#FIXME: authentication

	common_header ();


	if (isset($_REQUEST["ID"])) {
		$stmt = $mysqli->prepare ("SELECT Title, Address, Postcode FROM labs WHERE ID = ?");
		$stmt->bind_param ("i", $_REQUEST["ID"]);
		$stmt->execute ();
		$stmt->bind_result ($title, $address, $postcode);
		if ($stmt->fetch()) {
			menu_lab ($_REQUEST["ID"], $title);
?>
    <div class="row">
<?php menu_put (); ?>
    <div class="span6"> <!-- main area -->
<?php
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
?>
    <div class="row">
<?php menu_put (); ?>
    <div class="span6"> <!-- main area -->
<?php
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
    </div> <!-- /span6 -->
    <div class="hidden-phone span3">

        <img src="img/icon-room.jpg">
    </div>
    </div> <!-- /row -->
<?php
	common_footer ();
?>
