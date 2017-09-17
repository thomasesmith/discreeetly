<?php 

$config = array(); 

$config['app_settings']['max_queueable_tweets'] = 5;
$config['app_settings']['app_name'] = 'discreeetly';
$config['app_settings']['app_url'] = 'www.discreeetly.com';
$config['app_settings']['serving_with_ssl_cert'] = 1;
$config['app_settings']['twitter_screen_name'] = 'discreeetly';
$config['app_settings']['force_moderation'] = 1;
$config['app_settings']['moderators_twitter_user_id'] = '00000000';
$config['app_settings']['crypt_salt'] = 'User IP gets hashed with this salt before stored';

$config['database_credentials']['hostname'] = 'localhost';
$config['database_credentials']['database_name'] = 'discreeetly';
$config['database_credentials']['user_name'] = 'discreeetly_user';
$config['database_credentials']['password'] = 'SuperSecretP@ssword';

$config['twitter_api_credentials']['oauth_access_token'] = '';
$config['twitter_api_credentials']['oauth_access_token_secret'] = '';
$config['twitter_api_credentials']['consumer_key'] = '';
$config['twitter_api_credentials']['consumer_secret'] = '';
