<?php 

class Tweet
{
	private $dbh;
	private $id = 0;
	private $twitter_api_settings = array(
	    'oauth_access_token' => TW_OAUTH_ACCESS_TOKEN,
	    'oauth_access_token_secret' => TW_OAUTH_ACCESS_TOKEN_SECRET,
	    'consumer_key' => TW_CONSUMER_KEY,
	    'consumer_secret' => TW_CONSUMER_SECRET
	);	

	public $uid = "";
	public $added = "";
	public $content = "";
	public $in_reply_to_tweet_id = "";
	public $submitted_to_twitter = 0; 
	public $twitter_tweet_id = ""; 
	public $twitter_error_text = ""; 
	public $approved = 0; 
	public $deleted = 0; 


	function __construct($dbh, $uid, $id, $content, $in_reply_to_tweet_id = '')
	{
		$this->dbh = $dbh;

		if (strlen($uid) > 0) {
			// loading an existing record, with uid
			$this->uid = $uid; 
			$this->fetchTweetDetails();
		} else if (intval($id) > 0) {
			// loading an existing record, with id
			$this->id = intval($id); 
			$this->fetchTweetDetails();
		} else {
			// creating a new record
			$this->setContent($content);
			$this->setReplyTweetId($in_reply_to_tweet_id);
			// in_reply_to_tweet_id and references are for a future feature
			$this->saveTweetDetails();
		}
	}


	public function getUid()
	{
		if (strlen($this->uid) > 0) {
			return $this->uid;
		} 

		return false;
	}


	public function getContent()
	{
		return $this->content;
	}


	public function sendTweet()
	{
		if ($this->submitted_to_twitter == 0 && $this->approved == 1 && $this->deleted == 0) { 
			$postfields = array('status' => $this->content . ' ' . $this->uid);

			if ($this->in_reply_to_tweet_id != '') {
				$postfields['in_reply_to_status_id'] = $this->in_reply_to_tweet_id;
				$postfields['auto_populate_reply_metadata'] = 'true';
			} 

			$url = 'https://api.twitter.com/1.1/statuses/update.json';
			$requestMethod = 'POST';

			$twitter = new TwitterAPIExchange($this->twitter_api_settings);

			$resp = $twitter->buildOauth($url, $requestMethod)->setPostfields($postfields)->performRequest();
			$resp_array = json_decode($resp, true);

			if (isset($resp_array['id_str'])) {
				$this->markAsSubmittedToTwitter($resp_array['id_str'], '');		
			} elseif (isset($resp_array['errors'])) {
				$this->markAsSubmittedToTwitter('', $resp_array['errors'][0]['message']);
			}
		}
	}


	public function markAsApproved()
	{
		if ($this->approved == 0 && $this->submitted_to_twitter == 0) { 
			try {
	    		$stmt = $this->dbh->prepare('UPDATE tweets 
	    									SET approved = 1 
	    									WHERE uid = ?');
				$stmt->execute(array($this->uid));
			} catch(PDOException $e) {
    			throw new Exception('Database error: ' . $e->getMessage);
	    	}
    	}
	}


	public function markAsDeleted()
	{
		if ($this->deleted == 0 && $this->submitted_to_twitter == 0) { 
			try {
	    		$stmt = $this->dbh->prepare('UPDATE tweets 
	    									SET deleted = 1
	    									WHERE uid = ?');
				$stmt->execute(array($this->uid));
			} catch(PDOException $e) {
    			throw new Exception('Database error: ' . $e->getMessage);
	    	}
    	}
	}


	private function markAsSubmittedToTwitter($twitter_tweet_id, $twitter_error) {
		if ($this->submitted_to_twitter == 0) { 
			try {
	    		$stmt = $this->dbh->prepare('UPDATE tweets 
	    									SET submitted_to_twitter = 1, 
	    										twitter_tweet_id = ?,
	    										twitter_error_text = ?
	    									WHERE uid = ?');
				$stmt->execute(array($twitter_tweet_id, $twitter_error, $this->uid));
			} catch(PDOException $e) {
    			throw new Exception('Database error: ' . $e->getMessage);
	    	}
    	}
	}


	private function setContent($content)
	{
		if (mb_strlen($content) > 260 || mb_strlen($content) < 1) {
			throw new Exception('Tweets need to be at least one character, and no more than 260 characters');
		} elseif (Tweets::getNumberOfTweetsQueuedByIp($this->dbh) >= MAX_QUEUEABLE_TWEETS) {
			throw new Exception('Whoa hold up. Wait a few minutes while we get a chance to send out the tweets you\'ve already queued. Then try again in a few minutes');
		} else {
			// @TODO: perhaps prepend with a period any tweets that begin with a mention.
			$this->content = $content;
		}
	}


	private function setReplyTweetId($tweet_id)
	{
		$this->in_reply_to_tweet_id = $tweet_id;

		// if (mb_strlen($content) > 260 || mb_strlen($content) < 1) {
		// 	throw new Exception('Tweets need to be at least one character, and no more than 260 characters');
		// } elseif (Tweets::getNumberOfTweetsQueuedByIp($this->dbh) >= MAX_QUEUEABLE_TWEETS) {
		// 	throw new Exception('Whoa hold up. Wait a few minutes while we get a chance to send out the tweets you\'ve already queued. Then try again in a few minutes');
		// } else {
		// 	// @TODO: perhaps prepend with a period any tweets that begin with a mention.
		// 	$this->content = $content;
		// }
	}


	private function fetchTweetDetails()
	{
		try {
			if (strlen($this->uid) > 0) {
	    		$stmt = $this->dbh->prepare('SELECT id, uid, added, content, twitter_tweet_id,
	    											submitted_to_twitter, approved, deleted,
	    											twitter_error_text, in_reply_to_tweet_id
	    									FROM tweets 
	    									WHERE uid = ?
	    									LIMIT 1');
				$stmt->execute(array($this->uid));
			} else if ($this->id > 0) {
	    		$stmt = $this->dbh->prepare('SELECT id, uid, added, content, twitter_tweet_id,
	    											submitted_to_twitter, approved, deleted,
	    											twitter_error_text, in_reply_to_tweet_id
	    									FROM tweets 
	    									WHERE id = ?
	    									LIMIT 1');
				$stmt->execute(array($this->id));
			} else {
    			throw new Exception('No valid ID or UID to load.');
			}

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    			$this->id = $row['id'];
    			$this->uid = $row['uid'];
    			$this->added = $row['added'];
    			$this->content = $row['content'];
    			$this->in_reply_to_tweet_id = $row['in_reply_to_tweet_id'];
    			$this->submitted_to_twitter = $row['submitted_to_twitter'];
    			$this->approved = $row['approved'];
    			$this->deleted = $row['deleted'];
    			$this->twitter_tweet_id = $row['twitter_tweet_id'];
    			$this->twitter_error_text = $row['twitter_error_text'];
			}
		} catch(PDOException $e) {
    		throw new Exception('Database error: ' . $e->getMessage);
    	}
	}


	private function saveTweetDetails()
	{
		try {
			$uid = uniqid(true);
			$ip_hash = crypt($_SERVER['REMOTE_ADDR'], CRYPT_SALT);

    		$stmt = $this->dbh->prepare('INSERT INTO tweets 
    									(uid, content, ip_hash, session_id, in_reply_to_tweet_id) 
    									VALUES (?, ?, ?, ?, ?)');
			$stmt->execute(array(	$uid, $this->content, $ip_hash, session_id(), $this->in_reply_to_tweet_id	));

			$this->uid = $uid;
			$this->id = intval($this->dbh->lastInsertId());

			if ($this->id > 0) {
				$this->solicitModeration();
			}

		} catch(PDOException $e) {
    			throw new Exception('Database error: ' . $e->getMessage);
    	}
	}


	private function solicitModeration()
	{
		// This sends a direct message to the moderating twitter account,
		// so that they can approve or reject a tweet thats been added to the 
		// queue.

		if ($this->submitted_to_twitter == 0 && $this->approved == 0) { 

			$dm_content = "\"" . $this->content . "\"\r\n\r\nReply \"yes " . $this->id . "\" to approve, or \"del " . $this->id. "\" to delete";

			$postfields = array (
				'event' => 
				array(
					'type' => 'message_create',
					'message_create' => 
					array(
						'target' => array('recipient_id' => MODERATORS_TWITTER_USER_ID),
						'message_data' => array('text' => $dm_content, 
												'quick_reply' => array(
														'type' => 'options',
														'options' => array(
															array(
																'label' => 'Yes ' . $this->id,
																'description' => 'Approve'
															),
															array(
																'label' => 'Del ' . $this->id,
																'description' => 'Reject'
															)
														)
													)
												)
					)
				)
			);

			$url = 'https://api.twitter.com/1.1/direct_messages/events/new.json';
			$requestMethod = 'POST';

			try {
				$twitter = new TwitterAPIExchange($this->twitter_api_settings);

				$twitter->appjson = true;

				$twitter->buildOauth($url, $requestMethod)->performRequest(true,
					[
						CURLOPT_POSTFIELDS => json_encode($postfields)
					]
				);
			} catch (Exception $e) {
    			throw new Exception('Twitter API Error: ' . $e->getMessage);
			}
		}
	}


	public function fetchTweetDetailsFromTwitter()
	{
		if (strlen($this->twitter_tweet_id) > 0) {
			$url = 'https://api.twitter.com/1.1/statuses/show.json';
			$getfield = '?id=' . $this->twitter_tweet_id;
			$requestMethod = 'GET';

			$twitter = new TwitterAPIExchange($this->twitter_api_settings);
			$tweet_json = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();

			return json_decode($tweet_json, true);

		}
	}

}