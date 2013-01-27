<?php
	#FIXME: user, referential integrity
	$mysql_host = "";
	$mysql_user = "";
	$mysql_pass = "";
	$mysql_db = "";
	$md5salt = "";
	require_once "vars.php";

	$mysqli = new mysqli ($mysql_host, $mysql_user, $mysql_pass, $mysql_db);
	session_start ();
?>
