<?php
	require_once "common.php";

	$room = $mysqli->prepare ("SELECT rooms.Title, labs.Title, labs.Latitude, labs.Longitude, labs.ID, rooms.ID FROM rooms INNER JOIN labs ON rooms.LabID = labs.ID INNER JOIN institutions ON labs.InstitutionID = institutions.ID WHERE rooms.ID = ?");
	$room->bind_param ("i", $_REQUEST["ID"]);
	$room->bind_result ($room_title, $lab_title, $lat, $lng, $lab_id, $room_id);
	$room->execute ();
	$room->fetch();
	menu_lab ($lab_id, $lab_title);
	menu_room ($room_id, $room_title);
	common_header ();
?>
<div class="row">
    <div class="span3">
        <h2><?php print $room_title; ?></h2>
    </div>
    <div class="span3">
        <span class="pull-right"><a class="btn btn-primary" href="addshelf.php?RoomID=<?php print $_REQUEST["ID"]; ?>"><i class="icon-plus"></i> Add fridge</a></span>
    </div>
</div>
<?php
	$room->close();

	$shelf = $mysqli->prepare ("SELECT rooms.Title, shelves.Title, shelftypes.Title, labs.Title, labs.Latitude, labs.Longitude, labs.ID, rooms.ID, shelves.ID FROM shelves INNER JOIN shelftypes ON shelves.ShelfTypeID = shelftypes.ID INNER JOIN rooms ON shelves.RoomID = rooms.ID INNER JOIN labs ON rooms.LabID = labs.ID INNER JOIN institutions ON labs.InstitutionID = institutions.ID WHERE rooms.ID = ?");
	$shelf->bind_param ("i", $_REQUEST["ID"]);
	$shelf->bind_result ($room_title, $shelf_title, $shelf_type_title, $lab_title, $lat, $lng, $lab_id, $room_id, $shelf_id);
	$shelf->execute ();
?>
        <ul class="media-list">
<?php
	while ($shelf->fetch()) {
?>
            <li class="media">
                <a class="pull-left" href="shelf.php?ID=<?php print $shelf_id; ?>">
                <img class="media-object" src="img/icon-fridge.jpg" width="60px" height="60px">
                </a>
                <div class="media-body">
                <h4 class="media-heading"><a href="shelf.php?ID=<?php print $shelf_id; ?>"><?php print $shelf_title; ?></a></h4>
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
