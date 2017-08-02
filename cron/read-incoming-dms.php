<?php
// Reads the twitter accounts incoming messages, to do with the tweets as the moderator
// account tells it to. This is meant to be cronjob'd to run as frequently as possible.

require_once(__DIR__ . '/../html/_bootstrap.php');

// Go fetch and process any new incoming approval DMs 
$DM = new DMs($dbh);
$DM->processNextDMs();
