<?php 
	if (isset($_GET['e'])) { http_response_code(403); }
?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Send out anonymous tweets with <?=APP_NAME?>. Perfect for government leaks, or complaining about your boss.">
		<title>Tweet anonymously, with <?=APP_NAME?></title>
		<meta property="og:url" content="http<?=(SERVING_WITH_SSL_CERT == 1 ? 's' : '')?>://<?=APP_URL?>">
		<meta property="og:description" content="Send out anonymous tweets with <?=APP_NAME?>. Perfect for government leaks, or complaining about your boss.">
		<meta property="og:title" content="<?=APP_NAME?>">
		<meta property="og:site_name" content="<?=APP_NAME?>">
		<meta name="twitter:card" content="summary">
		<meta name="twitter:url" content="http<?=(SERVING_WITH_SSL_CERT == 1 ? 's' : '')?>://<?=APP_URL?>">
		<meta name="twitter:title" content="<?=APP_NAME?>">
		<meta name="twitter:description" content="Send out anonymous tweets with <?=APP_NAME?>. Perfect for government leaks, or complaining about your boss.">
		<link rel="icon" href="/favicon.ico">
		<link rel="stylesheet" href="/static/css/bootstrap-flatly.min.css">
		<link rel="stylesheet" href="/static/css/styles.css">
		<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</head>
	<body>
		<div class="container">
		    <div class="header hidden-xs">
				<nav>
					<ul class="nav nav-pills pull-right">
						<li role="presentation"><a href="https://twitter.com/<?=TWITTER_SCREEN_NAME?>">@<?=TWITTER_SCREEN_NAME?></a></li> 
					</ul>
				</nav> 
				<h3 class="text-muted"><a href="/"><?=APP_NAME?></a></h3>
			</div>