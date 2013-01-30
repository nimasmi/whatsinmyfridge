<?php
	require_once "common.php";


	$shelf = $mysqli->prepare ("SELECT rooms.Title, shelves.Title, shelftypes.Title, labs.Title, labs.Latitude, labs.Longitude, labs.ID, rooms.ID, shelves.ID FROM shelves INNER JOIN shelftypes ON shelves.ShelfTypeID = shelftypes.ID INNER JOIN rooms ON shelves.RoomID = rooms.ID INNER JOIN labs ON rooms.LabID = labs.ID INNER JOIN institutions ON labs.InstitutionID = institutions.ID WHERE rooms.ID = ?");
	$shelf->bind_param ("i", $_REQUEST["ID"]);
	$shelf->bind_result ($room_title, $shelf_title, $shelf_type_title, $lab_title, $lat, $lng, $lab_id, $room_id, $shelf_id);
	$shelf->execute ();
	$shelf->fetch ();
	menu_lab ($lab_id, $lab_title);
	menu_room ($room_id, $room_title);
	menu_shelf ($shelf_type_title, $shelf_id, $shelf_title);
	common_header ();
?>
<div class="row">
    <div class="span4">
    <h2><?php print $shelf_title; ?></h2>
    </div>
    <div class="span2">
        <span class="pull-right"><a class="btn btn-primary" href="additem.php?ShelfID=<?php print $_REQUEST["ID"]; ?>"><i class="icon-plus"></i> Add item</a></span>
    </div>
</div>
<?php
	$shelf->close ();

	$item = $mysqli->prepare ("SELECT items.Title, itemtypes.Title, rooms.Title, shelves.Title, shelftypes.Title, labs.Title, labs.Latitude, labs.Longitude, labs.ID, rooms.ID, shelves.ID, items.ID FROM items INNER JOIN itemtypes ON items.ItemTypeID = itemtypes.ID INNER JOIN shelves ON items.ShelfID = shelves.ID INNER JOIN shelftypes ON shelves.ShelfTypeID = shelftypes.ID INNER JOIN rooms ON shelves.RoomID = rooms.ID INNER JOIN labs ON rooms.LabID = labs.ID INNER JOIN institutions ON labs.InstitutionID = institutions.ID WHERE shelves.ID = ?");
	$item->bind_param ("i", $_REQUEST["ID"]);
	$item->bind_result ($item_title, $item_type_title, $room_title, $shelf_title, $shelf_type_title, $lab_title, $lat, $lng, $lab_id, $room_id, $shelf_id, $item_id);
	$item->execute ();
?>
        <ul class="media-list">
<?php
	while ($item->fetch()) {
?>
            <li class="media">
                <a class="pull-left" href="item.php?ID=<?php print $item_id; ?>">
                <img class="media-object" src="img/icon-item.jpg" width="60px" height="60px">
                </a>
                <div class="media-body">
                <h4 class="media-heading"><a href="item.php?ID=<?php print $item_id; ?>"><?php print $item_title; ?></a></h4>
                <p><!-- FIXME some description can go here --></p>
                </div>
            </li>
<?php
	}
?>
        </ul>
<?php
	common_footer ();
?>
