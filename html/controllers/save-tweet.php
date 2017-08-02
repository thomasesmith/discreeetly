<?php 

require_once('../_bootstrap.php');

if (isset($_POST['content'])) {
	$content = $_POST['content'];

	try {
		$tweet = new Tweet($dbh, '', '', $content);
	} catch (Exception $e) {
		header('Location: /?e=' . urlencode($e->getMessage()) . '&c=' . urlencode($content));
		exit;
	}

	if (isset($tweet)) { 
		header('Location: /?t=' . $tweet->getUid() );
		exit;
	}

} else {
	header('Location: /');
	exit;
}
