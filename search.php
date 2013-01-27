<?php
	require_once "common.php";

	if (isset($_REQUEST["searchstr"])) {
		#FIXME: ddos
		$terms = preg_split ("/[\s,]+/", $_REQUEST["searchstr"]);
		$results = array();

		$i = 0;
		$stmt = $mysqli->prepare ("SELECT DISTINCT ItemID FROM metavalues INNER JOIN metaoptions ON metavalues.MetaoptionID = metaoptions.ID WHERE metaoptions.Title LIKE ?");
		$stmt->bind_param ("s", $pattern);
		$stmt->bind_result ($item);

		$pattern = "%".$terms[0]."%";
		$stmt->execute();
		while ($stmt->fetch()) {
			print "Term 0, item $item<br>\n";
			array_push ($results, $item);
		}
		for ($i=1; $i<count($terms); $i++) {
			$pattern = "%".$terms[$i]."%";
			$stmt->execute();
			$nextres = array();
			while ($stmt->fetch()) {
				print "Term $i, item $item<br>\n";
				array_push ($nextres, $item);
			}
			$results = array_intersect ($results, $nextres);
		}
		#FIXME: inefficient, but it'll do for now
		$meta = $mysqli->prepare ("SELECT metatables.Title, metafields.Title, metaoptions.Title, metafields.IsOption, metafields.IsFloat, metafields.IsDate, metafields.IsText, metavalues.FloatData, metavalues.DateData, metavalues.TextData, metatables.ID, metafields.ID, metaoptions.ID, metavalues.ID FROM metatables LEFT JOIN metafields ON metatables.ID = metafields.MetatableID LEFT JOIN metaoptions ON metafields.ID = metaoptions.MetafieldID LEFT JOIN metavalues ON metaoptions.ID = metavalues.MetaoptionID WHERE (metavalues.ItemID = ? OR metavalues.ItemID IS NULL) ORDER BY metatables.Title ASC, metafields.Title ASC, metaoptions.Title ASC;");
		$meta->bind_param ("i", $id);
		$meta->bind_result ($table, $field, $option, $isoption, $isfloat, $isdate, $istext, $d_float, $d_date, $d_text, $tid, $fid, $oid, $vid);
		$item = $mysqli->prepare ("SELECT items.Title, itemtypes.Title, shelves.Title, shelftypes.Title, labs.Latitude, labs.Longitude FROM items INNER JOIN itemtypes ON items.ItemTypeID = itemtypes.ID INNER JOIN shelves ON items.ShelfID = shelves.ID INNER JOIN shelftypes ON shelves.ShelfTypeID = shelftypes.ID INNER JOIN rooms ON shelves.RoomID = rooms.ID INNER JOIN labs ON rooms.LabID = labs.ID INNER JOIN institutions ON labs.InstitutionID = institutions.ID WHERE items.ID = ?");
		$item->bind_param ("i", $id);
		$item->bind_result ($item_title, $item_type_title, $shelf_title, $shelf_type_title, $lat, $lng);
		foreach ($results as $id) {
			$item->execute ();
			$item->fetch();
			print "Result: $id $item_title $item_type_title $shelf_title $shelf_type_title $lat $lng<br>\n";
		}

		exit;
	}
?>
<html>
<body>
<form method="post" action="search.php">
Search: <input type="text" name="searchstr" value="">
<input type="submit" value="Search">
</form>
</body>
</html>
