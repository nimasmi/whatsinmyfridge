<?php
	require_once "common.php";

	#FIXME: authentication, metainfo selection

	if (isset($_REQUEST["update"])) {
		$mysqli->autocommit (FALSE);
		$stmt = $mysqli->prepare ("DELETE FROM metavalues WHERE ItemID = ?");
		$stmt->bind_param ("i", $_REQUEST["ID"]);
		$stmt->execute ();
		$stmt->close ();
		$stmt = $mysqli->prepare ("INSERT INTO metavalues (ItemID, MetafieldID, MetaoptionID, FloatData, DateData, TextData) VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->bind_param ("iiidss", $item, $field, $option, $d_float, $d_date, $d_text);
		$item = $_REQUEST["ID"];
		foreach ($_REQUEST as $key => $value) {
			if (substr ($key, 0, 6) == "option") {
				$field = substr ($key, 6);
				$option = $value;
				$stmt->execute ();
			}
			unset ($option);
			unset ($d_float);
			unset ($d_date);
			unset ($d_text);
		}
		$stmt->close();
		$mysqli->commit ();
	}

	$stmt = $mysqli->prepare ("SELECT metatables.Title, metafields.Title, metaoptions.Title, metafields.IsOption, metafields.IsFloat, metafields.IsDate, metafields.IsText, metatables.ID, metafields.ID, metaoptions.ID, metavalues.ID FROM metatables LEFT JOIN metafields ON metatables.ID = metafields.MetatableID LEFT JOIN metaoptions ON metafields.ID = metaoptions.MetafieldID LEFT JOIN metavalues ON metaoptions.ID = metavalues.MetaoptionID WHERE (metavalues.ItemID = ? OR metavalues.ItemID IS NULL) ORDER BY metatables.Title ASC, metafields.Title ASC, metaoptions.Title ASC;");
	$stmt->bind_param ("i", $_REQUEST["ID"]);
	$stmt->execute ();
	$stmt->bind_result ($table, $field, $option, $isoption, $isfloat, $isdate, $istext, $tid, $fid, $oid, $vid);
	$tid = $fid = $oid = -1;
	$ptid = $pfid = -1;
	$wasopt = 1;

?>
<html>
<body>
<form method="post" action="item.php">
<input type="hidden" name="ItemID" value="<?php print $_REQUEST["ID"]; ?>">
<input type="hidden" name="update" value="1">
<table border="1" cellpadding="0" cellspacing="0" width="400">
<?php
	while ($stmt->fetch()) {
		if ($fid != $pfid) {
			if ($pfid > -1) {
				if ($wasopt) {
?>
			</select>
<?php
				}
?>
		</td>
	</tr>
<?php
			}
?>
	<tr>
		<td><?php print $table." ".$field; ?></td>
		<td>
<?php
			if ($isoption) {
?>
			<select name="option<?php echo $fid; ?>">
<?php
			}
			$wasopt = $isoption;
		}
		if ($isoption) {
?>
				<option value="<?php print $oid; ?>"<?php if ($vid > 0) { print " selected"; } ?>><?php print $option; ?></option>
<?php
		} else if ($isdate) {
?>
			<input type="text" name="day<?php echo $fid; ?>" value="" size="2">/<input type="text" name="month<?php echo $fid; ?>" value="" size="2">/<input type="text" name="year<?php echo $fid; ?>" value="" size="4">
<?php
		} else {
?>
			<input type="text" name="" value="">
<?php
		}
		$ptid = $tid;
		$pfid = $fid;
	}
	if ($pfid > -1) {
		if ($wasopt) {
?>
			</select>
<?php
		}
?>
		</td>
	</tr>
<?php
	}
?>
	<tr>
		<td colspan="2"><input type="submit" value="Update">
	</tr>
</table>
