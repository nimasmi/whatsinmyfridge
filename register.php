<?php
	$skipauth = 1;
	require_once "common.php";

	if (isset($_REQUEST["mode"])) {
		if ($_REQUEST["pass1"] != $_REQUEST["pass2"]) {
			print "Passwords do not match";
			exit;
		}
		$stmt = $mysqli->prepare ("SELECT ID FROM users WHERE User=?");
		$stmt->bind_param ("s", $_REQUEST["user"]);
		$stmt->execute ();
		$stmt->store_result ();
		if ($stmt->num_rows() != 0) {
			print "User already exists";
			exit;
		} else {
			$stmt->close ();
			list($lat,$lng) = geocode ($_REQUEST["postcode"]);
			$stmt = $mysqli->prepare ("INSERT INTO users (User, Pass, Email, Latitude, Longitude) VALUES (?, ?, ?, ?, ?)");
			$stmt->bind_param ("sssss", $_REQUEST["user"], crypt($pass_pepper.$_REQUEST["pass1"], crypt_salt()), $_REQUEST["email"], $lat, $lng);
			$stmt->execute();
			$_SESSION["UserID"] = $mysqli->insert_id;
			$_SESSION["Latitude"] = $lat;
			$_SESSION["Longitude"] = $lng;
			session_write_close ();
			header ("Location: index.php");
		}
	} else {
		common_header ();
?>
<form method="post" action="register.php">
<input type="hidden" name="mode" value="register">
Username: <input type="text" name="user"><br>
Email: <input type="text" name="email"><br>
Password: <input type="password" name="pass1"><br>
Confirm password: <input type="password" name="pass2"><br>
Postcode (for locating labs near to you): <input type="text" name="postcode"><br>
<input type="submit" value="Register">
</form>
<?php
		$large_icon = "img/whatsfridgelarge.jpg";
		common_footer ();
	}
?>
