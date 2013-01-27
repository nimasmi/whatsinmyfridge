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

	function geocode ($postcode) {
		global $mysqli;
		$code = preg_replace ("/[^A-Z0-9]/", "", strtoupper($postcode));
		$stmt = $mysqli->prepare ("SELECT lat, lng FROM postcodes WHERE code = ?");
		$stmt->bind_param ("s", $code);
		$stmt->bind_result ($lat, $lng);
		$stmt->execute();
		$stmt->fetch();
		return array($lat, $lng);
	}
?>
