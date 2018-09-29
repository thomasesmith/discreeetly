CREATE TABLE `last_direct_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `direct_message_id` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `last_direct_message` (direct_message_id) VALUES ('892937445519000000');


CREATE TABLE `tweets` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` varchar(64) CHARACTER SET utf8 DEFAULT '',
  `added` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(10) unsigned DEFAULT '0',
  `content` varchar(260) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `in_reply_to_tweet_id` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `ip_hash` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `session_id` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `approved` tinyint(1) unsigned DEFAULT '0',
  `deleted` tinyint(1) unsigned DEFAULT '0',
  `submitted_to_twitter` tinyint(1) unsigned DEFAULT '0',
  `twitter_tweet_id` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  `twitter_error_text` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;