<?php
	require_once "common.php";

	#FIXME: authentication, metainfo selection

	function metaoptionhack ($field) {
		#FIXME: until I can be bothered to sort out SQL to do a full outer join, anything that doesn't have an option, will use a placeholder option
		global $mysqli;
		$mohack = $mysqli->prepare ("SELECT ID FROM metaoptions WHERE MetafieldID = ?");
		$mohack->bind_param ("i", $mohack_field);
		$mohack->bind_result ($mohack_option);
		$mohack_field = $field;
		$mohack->execute();
		$mohack->fetch();
		$mohack->close();
		print "<!-- mohack $field = $mohack_option -->\n";
		return $mohack_option;
	}

	if (isset($_REQUEST["update"])) {

		$mysqli->autocommit (FALSE);
		$stmt = $mysqli->prepare ("UPDATE items SET Title = ? WHERE ID = ?");
		$stmt->bind_param ("si", $_REQUEST['Title'], $_REQUEST["ID"]);
		$stmt->execute ();
		$stmt->close ();
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
				if (is_numeric($value)) {
					$option = $value;
				}
				$stmt->execute ();
			} elseif (substr ($key, 0, 5) == "float") {
				$field = substr ($key, 5);
				if (is_numeric($value)) {
					$option = metaoptionhack ($field);
					$d_float = $value;
					$stmt->execute ();
				}
			} elseif (substr ($key, 0, 3) == "day") {
				$field = substr ($key, 3);
				$option = metaoptionhack ($field);
				$d_date = $_REQUEST["year".$field]."-".$_REQUEST["month".$field]."-".$_REQUEST["day".$field];
				$stmt->execute ();
			}
			$option = null;
			$d_float = null;
			$d_date = null;
			$d_text = null;
		}
		$stmt->close();
		$mysqli->commit ();

		$stmt = $mysqli->prepare ("SELECT ShelfID FROM items WHERE ID = ?");
		$stmt->bind_param ("i", $_REQUEST["ID"]);
		$stmt->execute ();
		$stmt->bind_result ($shelf);
		$stmt->fetch();
		$stmt->close();
		header ("Location: shelf.php?ID=".$shelf);
	}

	$item = $mysqli->prepare ("SELECT items.Title, itemtypes.Title, rooms.Title, shelves.Title, shelftypes.Title, labs.Title, labs.Latitude, labs.Longitude, labs.ID, rooms.ID, shelves.ID FROM items INNER JOIN itemtypes ON items.ItemTypeID = itemtypes.ID INNER JOIN shelves ON items.ShelfID = shelves.ID INNER JOIN shelftypes ON shelves.ShelfTypeID = shelftypes.ID INNER JOIN rooms ON shelves.RoomID = rooms.ID INNER JOIN labs ON rooms.LabID = labs.ID INNER JOIN institutions ON labs.InstitutionID = institutions.ID WHERE items.ID = ?");
	$item->bind_param ("i", $_REQUEST["ID"]);
	$item->bind_result ($item_title, $item_type_title, $room_title, $shelf_title, $shelf_type_title, $lab_title, $lat, $lng, $lab_id, $room_id, $shelf_id);
	$item->execute ();
	$item->fetch ();
	menu_lab ($lab_id, $lab_title);
	menu_room ($room_id, $room_title);
	menu_shelf ($shelf_type_title, $shelf_id, $shelf_title);
	menu_item ($_REQUEST['ID'], $item_title);
	common_header ();
?>
<form method="post" action="item.php">
<input type="hidden" name="ID" value="<?php print $_REQUEST["ID"]; ?>">
<input type="hidden" name="update" value="1">
<table class="table table-bordered table-condensed" width="400">
	<tr>
		<td>Item name</td>
		<td><input type="text" name="Title" value="<?php print $item_title; ?>"></td>
	</tr>
<?php
	$item->close();

	$stmt = $mysqli->prepare ("SELECT metatables.Title, metafields.Title, metaoptions.Title, metafields.IsOption, metafields.IsFloat, metafields.IsDate, metafields.IsText, metavalues.FloatData, metavalues.DateData, metavalues.TextData, metatables.ID, metafields.ID, metaoptions.ID, metavalues.ID FROM metatables INNER JOIN metafields ON metatables.ID = metafields.MetatableID INNER JOIN metaoptions ON metafields.ID = metaoptions.MetafieldID LEFT JOIN metavalues ON metaoptions.ID = metavalues.MetaoptionID AND (metavalues.ItemID = ? OR metavalues.ItemID IS NULL) ORDER BY metatables.Title ASC, metafields.Title ASC, metaoptions.Title ASC;");
	$stmt->bind_param ("i", $_REQUEST["ID"]);
	$stmt->execute ();
	$stmt->bind_result ($table, $field, $option, $isoption, $isfloat, $isdate, $istext, $d_float, $d_date, $d_text, $tid, $fid, $oid, $vid);
	$tid = $fid = $oid = -1;
	$ptid = $pfid = -1;
	$wasopt = 1;
	while ($stmt->fetch()) {
		print "<!-- $fid -->\n";
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
			<input class="span1" type="text" name="day<?php echo $fid; ?>" value="<?php print substr($d_date, 8, 2); ?>" size="2">/<input class="span1" type="text" name="month<?php echo $fid; ?>" value="<?php print substr($d_date, 5, 2); ?>" size="2">/<input class="span1" type="text" name="year<?php echo $fid; ?>" value="<?php print substr($d_date, 0, 4); ?>" size="4">
<?php
		} else if ($isfloat) {
?>
			<input type="text" name="float<?php echo $fid; ?>" value="<?php echo $d_float; ?>">
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
		<td colspan="2"><input class="btn btn-primary" type="submit" value="Update">
	</tr>
</table>
<?php
	common_footer ();
?>
