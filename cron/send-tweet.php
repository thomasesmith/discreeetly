<?php 
// Scours table for untweeted and approved tweets to send out, and sends them out,
// one tweet per execution. This is meant to be cronjob'd to run as frequently as possible.

require_once(__DIR__ . '/../html/_bootstrap.php');
 
// get next 10 approved tweets in queue, and send them off
foreach (Tweets::getNextUidsToSubmitToTwitter($dbh, 10) as $tweet_uid) {
	$tweetToSend = new Tweet($dbh, $tweet_uid, '', '');
	$tweetToSend->sendTweet();
}
