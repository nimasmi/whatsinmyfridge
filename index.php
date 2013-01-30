<?php
	$skipauth = 1;
	require_once "common.php";

	if (isset($_SESSION["UserID"])) {
		header ("Location: home.php");
		exit;
	}

	common_header ();
?>
    <h1>What's in my fridge?</h1>
    <p class="lead">The NHS Marketplace for Laboratory Stock</p>
    <a class="btn btn-large btn-primary" href="register.php">Sign up today</a>
    <a class="btn btn-large btn-primary" href="login.php">Login</a>

    <h4>Search</h4>
    <p>for Chemicals and Reagents that are located in fridge/freezers in your institute and other institutes.</p>

    <h4>Save</h4>
    <p>money on buying and selling within the NHS</p>

    <h4>Network</h4>
    <p>with other health professionals</p>

    <h4>Analyse</h4>
    <p>Can also provide analytics on how often an item is ordered, the analytics can also reveal if any item is surplus to requirement based on expiry date</p>

    <h4>Notify</h4>
    <p>Auto generate emails to yourself and other health professionals once any item comes within two months of the expiry date.</p>
<?php
	$large_icon = "img/whatsfridgelarge.jpg";
	common_footer ();
?>
