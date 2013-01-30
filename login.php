<?php
	$skipauth = 1;
	require_once "common.php";

	if (isset($_REQUEST["logout"])) {
		session_destroy ();
		session_write_close ();
		header ("Location: index.php");
		exit;
	} else if (isset($_REQUEST["User"])) {
		$stmt = $mysqli->prepare ("SELECT ID, Pass, Latitude, Longitude FROM users WHERE User = ?");
		$stmt->bind_param ("s", $_REQUEST["User"]);
		$stmt->execute ();
		$stmt->bind_result ($id, $pass, $lat, $lng);
		if ($stmt->fetch()) {
			if (md5($md5salt.$_REQUEST["Pass"]) != $pass) {
				print "Username/password incorrect";
				exit;
			}
			$_SESSION['UserID'] = $id;
			$_SESSION["Latitude"] = $lat;
			$_SESSION["Longitude"] = $lng;
			session_write_close ();
			header ("Location: index.php");
			exit;
		} else {
			print "Username/password incorrect";
			exit;
		}
	}

	common_header ();
?>
<form method="post" action="login.php">
Username: <input type="text" name="User"><br>
Password: <input type="password" name="Pass"><br>
<input type="submit" value="Login"><br>
<a href="register.php">Register</a>
</form>
<?php
	$large_icon = "img/whatsfridgelarge.jpg";
	common_footer ();
?>
