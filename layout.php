<?php
	function common_header () {
		$magic_select = array(
			"search.php" => "Search",
			"home.php" => "Home",
			"index.php" => "Home",
			"register.php" => "Home",
			"login.php" => "Home",
		);
		$highlight = "Browse";
		foreach ($magic_select as $search => $hi) {
			if (strpos($_SERVER['SCRIPT_NAME'], $search) !== false) {
				$highlight = $hi;
				break;
			}
		}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>What's in my fridge?</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="ico/favicon.png">
  </head>

  <body>


<div id="wrap">
<div id="nav">
    <div class="container">
        <div class="row">
            <div class="span3">
                <a href="/whatsinmyfridge/"><img src="img/whatsfridgelogo.png"></a>
            </div> <!-- span -->
            <div class="span6">
                <ul class="nav nav-pills">
                    <li class="<?php if ($highlight == "Home") { print "active"; } ?>"><a href="home.php">Home</a></li>
                    <li class="<?php if ($highlight == "Browse") { print "active"; } ?>"><a href="viewlab.php">Browse</a></li>
                    <li class="<?php if ($highlight == "Search") { print "active"; } ?>"><a href="search.php">Search</a></li>
                </ul>
            </div> <!-- span6 -->
        </div> <!-- row -->
    </div> <!-- container -->
</div><div id="main" class="container clear-top">
<?php
	}
	function common_footer () {
?>
</div> <!-- /container -->
<div id="push"></div>
</div> <!-- /wrap -->
<div id="footer">
    <div class="container">
        <p class="muted credit">Built by Omer Saeed, <a href="http://twitter.com/jeblundell">Jim Blundell</a>, <a href="http://twitter.com/londonlime">Ayesha Garrett</a> and <a href="http://twitter.com/nimasmi">Nick Smith</a>.<a href="http://nhshackday.com/"><img class="pull-right" src="img/nhshackdaylogofooter.png"></a></p>
    </div>
</div>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>

  </body>
</html>
<?php
	}
?>
