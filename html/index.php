<?php 

require_once('_bootstrap.php');

if (isset($_GET['t'])) {
	if (ctype_alnum($_GET['t'])) { 
		try {
			$loaded_tweet = new Tweet($dbh, $_GET['t'], '', '');
		} catch (Exception $e) { }
	}
}

require_once('views/header.php');

require_once('views/main.php');

require_once('views/footer.php');