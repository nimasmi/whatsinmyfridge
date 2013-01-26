<?php
	require_once "common.php";
#FIXME: authentication
	$stmt = $mysqli->prepare ("SELECT LabID, Title FROM rooms WHERE ID=?");
	$stmt->bind_param ("i", $_REQUEST["ID"]);
	$stmt->execute ();
	$stmt->bind_result ($labid, $title);
	$stmt->fetch();
?>
<html>
<body>
Room: <?php print $title; ?>
<?php
?>
</body>
</html>
