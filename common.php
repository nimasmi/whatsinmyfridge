<?php
	#FIXME: user, referential integrity
	$mysql_host = "";
	$mysql_user = "";
	$mysql_pass = "";
	$mysql_db = "";
	$pass_pepper = "";
	require_once "vars.php";	#Sets the above variables
	require_once "layout.php";

	defined("CRYPT_BLOWFISH") or die("Blowfish encryption not available");
	CRYPT_BLOWFISH>0 or die("Blowfish encryption not available");

	session_start ();
	if (!isset($_SESSION["UserID"]) && !isset($skipauth)) {
		header ("Location: login.php");
	}
	$mysqli = new mysqli ($mysql_host, $mysql_user, $mysql_pass, $mysql_db);

	function crypt_salt () {
		$valid = array_merge(range('A','Z'), range('a','z'), range(0,9));
    	for($i=0; $i < 22; $i++) {
    	  	$salt .= $valid[array_rand($valid)];
    	}
		return "$2y$09$".$salt;
	}
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
	function latlongdist ($lat1, $lng1, $lat2, $lng2) {
		#FIXME: use OS's shiney version
		$R = 6371;
		$dlat = deg2rad ($lat2-$lat1);
		$dlng = deg2rad ($lng2-$lng1);
		$lat1 = deg2rad ($lat1);
		$lng1 = deg2rad ($lng1);

		$a = sin($dlat/2) * sin($dlat/2) + sin($dlng/2) * sin($dlng/2) * cos($lat1) * cos($lng1);
		$c = 2 * atan2(sqrt($a), sqrt(1-$a));
		return $R * $c;
	}
?>
