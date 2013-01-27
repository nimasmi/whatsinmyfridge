<?php
	require_once "common.php";

	if (isset($_REQUEST["searchstr"])) {
		#FIXME: ddos
		$terms = preg_split ("/[\s,]+/", $_REQUEST["searchstr"]);
		$results = array();

		$i = 0;
		$stmt = $mysqli->prepare ("SELECT DISTINCT ItemID FROM metavalues INNER JOIN metaoptions ON metavalues.MetaoptionID = metaoptions.ID WHERE metaoptions.Title LIKE ?");
		$stmt->bind_param ("s", $pattern);
		$stmt->bind_result ($item);

		$pattern = "%".$terms[0]."%";
		$stmt->execute();
		while ($stmt->fetch()) {
			print "Term 0, item $item<br>\n";
			array_push ($results, $item);
		}
		for ($i=1; $i<count($terms); $i++) {
			$pattern = "%".$terms[$i]."%";
			$stmt->execute();
			$nextres = array();
			while ($stmt->fetch()) {
				print "Term $i, item $item<br>\n";
				array_push ($nextres, $item);
			}
			$results = array_intersect ($results, $nextres);
		}
		foreach ($results as $i) {
			print "Final: $i<br>\n";
		}

		exit;
	}
?>
<html>
<body>
<form method="post" action="search.php">
Search: <input type="text" name="searchstr" value="">
<input type="submit" value="Search">
</form>
</body>
</html>
