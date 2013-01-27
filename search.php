<?php
	require_once "common.php";

	if (isset($_REQUEST["searchstr"])) {
		common_header ();
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
			array_push ($results, $item);
		}
		for ($i=1; $i<count($terms); $i++) {
			$pattern = "%".$terms[$i]."%";
			$stmt->execute();
			$nextres = array();
			while ($stmt->fetch()) {
				array_push ($nextres, $item);
			}
			$results = array_intersect ($results, $nextres);
		}
		#FIXME: inefficient, but it'll do for now
		$meta = $mysqli->prepare ("SELECT metatables.Title, metafields.Title, metaoptions.Title, metafields.IsOption, metafields.IsFloat, metafields.IsDate, metafields.IsText, metavalues.FloatData, metavalues.DateData, metavalues.TextData, metatables.ID, metafields.ID, metaoptions.ID, metavalues.ID FROM metatables LEFT JOIN metafields ON metatables.ID = metafields.MetatableID LEFT JOIN metaoptions ON metafields.ID = metaoptions.MetafieldID LEFT JOIN metavalues ON metaoptions.ID = metavalues.MetaoptionID WHERE (metavalues.ItemID = ? OR metavalues.ItemID IS NULL) ORDER BY metatables.Title ASC, metafields.Title ASC, metaoptions.Title ASC;");
		$meta->bind_param ("i", $id);
		$meta->bind_result ($table, $field, $option, $isoption, $isfloat, $isdate, $istext, $d_float, $d_date, $d_text, $tid, $fid, $oid, $vid);
		$item = $mysqli->prepare ("SELECT items.Title, itemtypes.Title, rooms.Title, shelves.Title, shelftypes.Title, labs.Title, labs.Latitude, labs.Longitude FROM items INNER JOIN itemtypes ON items.ItemTypeID = itemtypes.ID INNER JOIN shelves ON items.ShelfID = shelves.ID INNER JOIN shelftypes ON shelves.ShelfTypeID = shelftypes.ID INNER JOIN rooms ON shelves.RoomID = rooms.ID INNER JOIN labs ON rooms.LabID = labs.ID INNER JOIN institutions ON labs.InstitutionID = institutions.ID WHERE items.ID = ?");
		$item->bind_param ("i", $id);
		$item->bind_result ($item_title, $item_type_title, $room_title, $shelf_title, $shelf_type_title, $lab_title, $lat, $lng);
?>
    <div class="row">
    <div class="span3 menus-area">
    </div>
    <div class="span6"> <!-- main area -->

        <h2>Results</h2>
            <p>You searched for "<?php print $_REQUEST["searchstr"]; ?>"</p>

        <div id="accordion2">

        <ul class="media-list">
<?php
		foreach ($results as $id) {
			$item->execute ();
			$item->fetch();
?>
            <li class="media">
                <a class="pull-left" href="#">
                <img class="media-object" src="http://lorempixel.com/m/60/60/technics/"></a>
                <div class="media-body">
                <h4 class="media-heading"><a href="item.php?ID=<?php print $id; ?>"><?php print $item_title." in ".$shelf_title; ?></a></h4>
                
                <dl>
                    <dt>Distance to lab</dt>
                    <dd><?php printf ("%.1f km", latlongdist ($lat, $lng, $_SESSION["Latitude"], $_SESSION["Longitude"])); ?></dd>
                    <dt>Item type</dt>
                    <dd><?php print $item_type_title; ?></dd>
                    <dt>Lab</dt>
                    <dd><?php print $lab_title; ?></dd>
                </dl>
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?php print $id; ?>">more>></a>
                <div id="collapse<?php print $id; ?>" class="accordion-body collapse">
                    <dl>
                        <dt>Room</dt>
                        <dd><?php print $room_title; ?></dd>
                        <dt><?php print $shelf_type_title; ?></dt>
                        <dd><?php print $shelf_title; ?></dd>
<?php
			$meta->execute(); $meta->fetch();
?>
                    </dl>
                </div> <!-- collapse -->
            </li>
<?php
		}
?>
        </ul>
    </div> <!-- accordion -->
    </div>
    <div class="span3">
        <img src="img/icon-item.jpg">
    </div>

    </div> <!-- /row -->
<?php

		common_footer ();
		exit;
	}

	common_header ();
?>
<form method="post" class="form-inline" action="search.php">
Search: <input type="text" name="searchstr" value="">
<input class="btn btn-primary" type="submit" value="Search">
</form>
<?php
	common_footer ();
?>
