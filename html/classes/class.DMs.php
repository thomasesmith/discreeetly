<?php 

class DMs {
	private $dbh;
	private $twitter_api_settings = array(
	    'oauth_access_token' => TW_OAUTH_ACCESS_TOKEN,
	    'oauth_access_token_secret' => TW_OAUTH_ACCESS_TOKEN_SECRET,
	    'consumer_key' => TW_CONSUMER_KEY,
	    'consumer_secret' => TW_CONSUMER_SECRET
	);	


	function __construct($dbh) {
		$this->dbh = $dbh; 
	}


	public function processNextDMs() {
		$dms = $this->fetchNextUnprocessedDMs();

		if (count($dms) > 0) {
			foreach ($dms as $dm) {

				if ($dm["sender"]["id"] == MODERATORS_TWITTER_USER_ID) {
					// Obviously, the app should only listen to DMs from the moderator's account
					
					list($answer, $tweet_id) = explode(' ', strtolower(trim($dm["text"])));

					if ($answer == "yes") {
						$tweet = new Tweet($this->dbh, '', intval($tweet_id), '');
						$tweet->markAsApproved();
					} else if ($answer == "del") {
						$tweet = new Tweet($this->dbh, '', intval($tweet_id), '');
						$tweet->markAsDeleted();
					}
				}

				// Over-write the last direct message ID with this DMs ID so it doesn't read it again.
				if (strlen($dm["id"]) > 0) {
					try {
					  	$stmt = $this->dbh->prepare('UPDATE last_direct_message 
					  								SET direct_message_id = ? 
					  								WHERE id = 1');
						$stmt->execute(array($dm["id"]));
					} catch(PDOException $e) {
		    			throw new Exception('Database error: ' . $e->getMessage);
			   		}
			   	}

			}
		}	
	}


	private function fetchIdOfLastDMProcessed() {
		$stmt = $this->dbh->query(	'SELECT direct_message_id 
									FROM last_direct_message 
									WHERE id = 1 
									LIMIT 1');

		$direct_message_id = '0';

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$direct_message_id = $row['direct_message_id'];
		}

		return $direct_message_id;
	}


	private function fetchNextUnprocessedDMs() {
		// Got fetch the unprocessed DMs, return them as an assoc. array

		$url = 'https://api.twitter.com/1.1/direct_messages.json';
		$getfield = '?since_id=' . $this->fetchIdOfLastDMProcessed();
		$requestMethod = 'GET';

		$twitter = new TwitterAPIExchange($this->twitter_api_settings);
		$dms_json = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();

		$dms = json_decode($dms_json, true);

		return $dms;
	}
}