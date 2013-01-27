<?php
	require_once "common.php";

	common_header ();
	$room = $mysqli->prepare ("SELECT rooms.Title, labs.Title, labs.Latitude, labs.Longitude, labs.ID, rooms.ID FROM rooms INNER JOIN labs ON rooms.LabID = labs.ID INNER JOIN institutions ON labs.InstitutionID = institutions.ID WHERE rooms.ID = ?");
	$room->bind_param ("i", $_REQUEST["ID"]);
	$room->bind_result ($room_title, $lab_title, $lat, $lng, $lab_id, $room_id);
	$room->execute ();
	$room->fetch();
?>
    <div class="row">
    <div class="span3 menus-area">
        <ul class="nav nav-pills nav-stacked">
            <li class=""><a href="viewlab.php?ID=<?php print $lab_id; ?>">Lab <span class="pull-right current-value"><?php print $lab_title; ?></span></a></li>
            <li class="active"><a href="room.php?ID=<?php print $room_id; ?>">Room <span class="pull-right current-value"><?php print $room_title; ?></span></a></li>
            <li class="disabled"><a href="#"></span></a></li>
            <li class="disabled"><a href="#">Item <span class="pull-right current-value"></span></a></li>
        </ul>
    </div>

    <div class="span6"> <!-- main area -->
<a href="addshelf.php?RoomID=<?php print $_REQUEST["ID"]; ?>">Add shelf</a><br>
<?php
	$room->close();

	$shelf = $mysqli->prepare ("SELECT rooms.Title, shelves.Title, shelftypes.Title, labs.Title, labs.Latitude, labs.Longitude, labs.ID, rooms.ID, shelves.ID FROM shelves INNER JOIN shelftypes ON shelves.ShelfTypeID = shelftypes.ID INNER JOIN rooms ON shelves.RoomID = rooms.ID INNER JOIN labs ON rooms.LabID = labs.ID INNER JOIN institutions ON labs.InstitutionID = institutions.ID WHERE rooms.ID = ?");
	$shelf->bind_param ("i", $_REQUEST["ID"]);
	$shelf->bind_result ($room_title, $shelf_title, $shelf_type_title, $lab_title, $lat, $lng, $lab_id, $room_id, $shelf_id);
	$shelf->execute ();

	while ($shelf->fetch()) {
?>
<a href="shelf.php?ID=<?php print $shelf_id; ?>"><?php print $shelf_title; ?></a><br>
<?php
	}
?>
    </div> <!-- /span6 -->
    <div class="span3">
        <img src="img/icon-room.jpg">
    </div>
    </div> <!-- /row -->
<?php
	common_footer ();
?>
