<?php
	require_once "common.php";

	if (isset($_REQUEST["mode"])) {
		if ($_REQUEST["pass1"] != $_REQUEST["pass2"]) {
			print "Passwords do not match";
			exit;
		}
		$stmt = $mysqli->prepare ("SELECT ID FROM users WHERE User=?");
		$stmt->bind_params ("s", $_REQUEST["user"]);
		$stmt->execute ();
		if ($stmt->num_rows() != 0) {
			print "User already exists";
			exit;
		} else {
			$stmt->close ();
			$stmt = $mysqli->prepare ("INSERT INTO users (User, Pass, Email) VALUES (?, ?, ?)");
			$stmt->bind_params ("sss", $_REQUEST["user"], md5($md5salt.$_REQUEST["pass1"]), $_REQUEST["email"]);
			$stmt->execute();
			$_SESSION["UserID"] = $mysqli->insert_id;
			header ("Location: home.php");
		}
	} else {
?>
<html>
<body>
<form method="post" action="register.php">
<input type="hidden" name="mode" value="register">
Username: <input type="text" name="user"><br>
Email: <input type="text" name="email"><br>
Password: <input type="password" name="pass1"><br>
Confirm password: <input type="password" name="pass2"><br>
<input type="submit" value="Register">
</form>
</body
</html>
<?php
	}
?>
