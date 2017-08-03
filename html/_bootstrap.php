<?php 
// These php.ini settings should be set accordingly, if you don't have access to the php.ini
// try just commenting these lines out.
//ini_set('session.cookie_httponly', 1);
//ini_set('session.use_only_cookies', 1);
//ini_set('session.cookie_secure', 1);

// App settings, database credentials, and Twitter API keys are stored in a json file.
// It's important that this file is not in the web root. 
$config = json_decode(file_get_contents(__DIR__ . '/../config.json'), true);

define("APP_NAME", $config['app_settings']['app_name']);
define("APP_URL", $config['app_settings']['app_url']);
define("TWITTER_SCREEN_NAME", $config['app_settings']['twitter_screen_name']);
define("MAX_QUEUEABLE_TWEETS", $config['app_settings']['max_queueable_tweets']);
define("SERVING_WITH_SSL_CERT", $config['app_settings']['serving_with_ssl_cert']);
define("MODERATORS_TWITTER_USER_ID", $config['app_settings']['moderators_twitter_user_id']);
define("CRYPT_SALT", $config['app_settings']['crypt_salt']);

define("TW_OAUTH_ACCESS_TOKEN", $config['twitter_api_credentials']['oauth_access_token']);
define("TW_OAUTH_ACCESS_TOKEN_SECRET", $config['twitter_api_credentials']['oauth_access_token_secret']);
define("TW_CONSUMER_KEY", $config['twitter_api_credentials']['consumer_key']);
define("TW_CONSUMER_SECRET", $config['twitter_api_credentials']['consumer_secret']);

if (SERVING_WITH_SSL_CERT == 1 && !isset($_SERVER['HTTPS'])) {
	header('Location: https://' . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI']);
}

session_start();

try {
	$dbh = new PDO('mysql:host=' . $config["database_credentials"]["hostname"] . ';dbname=' . $config["database_credentials"]["database_name"] . ';charset=utf8mb4', $config["database_credentials"]["user_name"], $config["database_credentials"]["password"]);
} catch (Exception $e) {
	echo 'Database connection error.';
	exit;
}

require_once(__DIR__ . '/vendor/autoload.php');

function autoload_classes($class_name) {
    $file = __DIR__ . '/classes/class.' . $class_name . '.php';

    if (file_exists($file)) {
        require_once($file);
    }
}

spl_autoload_register('autoload_classes');
