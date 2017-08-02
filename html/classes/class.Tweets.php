<?php 

class Tweets {
	private static $dbh; 

	public static function getNextUidToTweet($dbh) {
		self::$dbh = $dbh;
		$stmt = self::$dbh->query(	'SELECT uid FROM tweets 
									WHERE submitted_to_twitter = 0 
										AND approved = 1
										AND deleted != 1
									ORDER BY id ASC 
									LIMIT 1');

		$uid = false;

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$uid = $row['uid'];
		}

		return $uid;
	}


	public static function getNextUidsToSubmitToTwitter($dbh, $limit) {
		self::$dbh = $dbh;
		
		$limit = intval($limit);

		$stmt = self::$dbh->query(	'SELECT uid 
									FROM tweets 
									WHERE submitted_to_twitter = 0 
										AND approved = 1
										AND deleted != 1
									ORDER BY id ASC
									LIMIT ' . $limit);

		$array_of_uids = [];

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$array_of_uids[] = $row['uid'];
		}

		return $array_of_uids;
	}


	public static function getNumberOfTweetsInQueue($dbh) {
		self::$dbh = $dbh;
		$count = 0;
		$stmt = self::$dbh->query(	'SELECT count(id) as cnt 
									FROM tweets 
									WHERE submitted_to_twitter = 0 
										AND deleted != 1');

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$count = intval($row['cnt']);
		}

		return $count;
	}


	public static function getNumberOfTweetsQueuedByIp($dbh) {
		self::$dbh = $dbh;
		$count = 0;

		$ip_hash = crypt($_SERVER['REMOTE_ADDR'], CRYPT_SALT);

		$stmt = self::$dbh->prepare('SELECT count(id) as cnt 
									FROM tweets 
									WHERE submitted_to_twitter = 0
										AND ip_hash = ?
										AND deleted != 1');
		$stmt->execute(array($ip_hash)); 

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$count = intval($row['cnt']);
		}

		return $count;
	}


	public static function getTotalNumberOfTweetsEverTweeted($dbh) {
		self::$dbh = $dbh;
		$count = 0;
		$stmt = self::$dbh->query('SELECT count(id) as cnt 
									FROM tweets 
									WHERE twitter_tweet_id IS NOT NULL');

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$count = intval($row['cnt']);
		}

		return $count;
	}


	public static function tweetsBySessionID($dbh) {
		self::$dbh = $dbh;

		$stmt = self::$dbh->prepare('SELECT uid 
									FROM tweets 
									WHERE session_id = ?');

		$stmt->execute(array(session_id())); 

		$array_of_uids = [];

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$array_of_uids[] = $row['uid'];
		}

		return $array_of_uids;
	}


	public static function tweetsByUserID($dbh) {
		self::$dbh = $dbh;

		$stmt = self::$dbh->prepare('SELECT uid 
									FROM tweets 
									WHERE user_id = ?
									ORDER BY added DESC');

		$stmt->execute(array(session_id())); 

		$array_of_uids = [];

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$array_of_uids[] = $row['uid'];
		}

		return $array_of_uids;
	}
}
