<?php
	#FIXME: user, referential integrity
	$mysql_host = "";
	$mysql_user = "";
	$mysql_pass = "";
	$mysql_db = "";
	$md5salt = "";
	require_once "vars.php";	#Sets the above variables

	session_start ();
	if (!isset($_SESSION["UserID"]) && !isset($skipauth)) {
		header ("Location: login.php");
	}
	$mysqli = new mysqli ($mysql_host, $mysql_user, $mysql_pass, $mysql_db);
?>
