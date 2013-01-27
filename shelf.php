<?php
	require_once "common.php";


	common_header ();
	$shelf = $mysqli->prepare ("SELECT rooms.Title, shelves.Title, shelftypes.Title, labs.Title, labs.Latitude, labs.Longitude, labs.ID, rooms.ID, shelves.ID FROM shelves INNER JOIN shelftypes ON shelves.ShelfTypeID = shelftypes.ID INNER JOIN rooms ON shelves.RoomID = rooms.ID INNER JOIN labs ON rooms.LabID = labs.ID INNER JOIN institutions ON labs.InstitutionID = institutions.ID WHERE rooms.ID = ?");
	$shelf->bind_param ("i", $_REQUEST["ID"]);
	$shelf->bind_result ($room_title, $shelf_title, $shelf_type_title, $lab_title, $lat, $lng, $lab_id, $room_id, $shelf_id);
	$shelf->execute ();
	$shelf->fetch ();
?>
    <div class="row">
    <div class="span3 menus-area">
        <ul class="nav nav-pills nav-stacked">
            <li class=""><a href="viewlab.php?ID=<?php print $lab_id; ?>">Lab <span class="pull-right current-value"><?php print $lab_title; ?></span></a></li>
            <li class=""><a href="room.php?ID=<?php print $room_id; ?>">Room <span class="pull-right current-value"><?php print $room_title; ?></span></a></li>
            <li class="active"><a href="shelf.php?ID=<?php print $shelf_id; ?>"><?php print $shelf_type_title; ?> <span class="pull-right current-value"><?php print $shelf_title; ?></span></a></li>
            <li class="disabled"><a href="#">Item <span class="pull-right current-value"></span></a></li>
        </ul>
    </div>

    <div class="span6"> <!-- main area -->
<?php
	$shelf->close ();

	$item = $mysqli->prepare ("SELECT items.Title, itemtypes.Title, rooms.Title, shelves.Title, shelftypes.Title, labs.Title, labs.Latitude, labs.Longitude, labs.ID, rooms.ID, shelves.ID, items.ID FROM items INNER JOIN itemtypes ON items.ItemTypeID = itemtypes.ID INNER JOIN shelves ON items.ShelfID = shelves.ID INNER JOIN shelftypes ON shelves.ShelfTypeID = shelftypes.ID INNER JOIN rooms ON shelves.RoomID = rooms.ID INNER JOIN labs ON rooms.LabID = labs.ID INNER JOIN institutions ON labs.InstitutionID = institutions.ID WHERE shelves.ID = ?");
	$item->bind_param ("i", $_REQUEST["ID"]);
	$item->bind_result ($item_title, $item_type_title, $room_title, $shelf_title, $shelf_type_title, $lab_title, $lat, $lng, $lab_id, $room_id, $shelf_id, $item_id);
	$item->execute ();
?>

<a href="additem.php?ShelfID=<?php print $_REQUEST["ID"]; ?>">Add item</a><br>
<?php
	while ($item->fetch()) {
?>
<a href="item.php?ID=<?php print $item_id; ?>"><?php print $item_title; ?></a><br>
<?php
	}
?>
    </div> <!-- /span6 -->
    <div class="span3">
        <img src="img/icon-fridge.jpg">
    </div>
    </div> <!-- /row -->
<?php
	common_footer ();
?>
