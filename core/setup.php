<?php
/* 
 * Sets up the Felix Online environment 
 */

date_default_timezone_set('Europe/London');

// define current working directory
if(!defined('BASE_DIRECTORY')) define('BASE_DIRECTORY', dirname(__FILE__).'/..');
//if(!defined('CACHE_DIRECTORY')) define('CACHE_DIRECTORY', BASE_DIRECTORY.'/cache/');

if(!file_exists(BASE_DIRECTORY.'/vendor/autoload.php')) {
	die('Please run <code>composer install</code>');
}
if(!file_exists(BASE_DIRECTORY.'/config.php')) {
	die('Please setup the configuration file');
}

// Composer
require BASE_DIRECTORY.'/vendor/autoload.php';

require(BASE_DIRECTORY.'/config.php');
require(BASE_DIRECTORY.'/vendor/felixonline/core/constants.php');

require BASE_DIRECTORY.'/core/exceptions.php';

require BASE_DIRECTORY.'/core/page.php';
require BASE_DIRECTORY.'/core/ux.php';
require BASE_DIRECTORY.'/core/homepage.php';

require BASE_DIRECTORY.'/widgets/interface.php';
require BASE_DIRECTORY.'/helpers/ajaxCore.php';
require BASE_DIRECTORY.'/actions/baseAction.php';

foreach (glob(BASE_DIRECTORY.'/widgets/*.php') as $filename) {
	require_once($filename);
}

foreach (glob(BASE_DIRECTORY.'/helpers/*.php') as $filename) {
	require_once($filename);
}

foreach (glob(BASE_DIRECTORY.'/actions/*.php') as $filename) {
	require_once($filename);
}

// Constants
if(!defined('SERVICE_NAME'))					define('SERVICE_NAME', 'Felix Online Admin');

// Initialize App
$app = new \FelixOnline\Core\App($config);

/* Initialise ezSQL database connection */
$db = new \ezSQL_mysqli();
$db->quick_connect(
	$config['db_user'],
	$config['db_pass'],
	$config['db_name'],
	'localhost',
	3306,
	'utf8'
);
$safesql = new \SafeSQL_MySQLi($db->dbh);

/* Set settings for caching (turned off by defualt) */
// Cache expiry
$db->cache_timeout = 24; // Note: this is hours
$db->use_disk_cache = true;
$db->cache_dir = 'inc/ezsql_cache'; // Specify a cache dir. Path is taken from calling script
$db->show_errors();

$app['db'] = $db;
$app['safesql'] = $safesql;

if (LOCAL) { // development connector
	// Initialize Akismet
	$connector = new \Riv\Service\Akismet\Connector\Test();
	$app['akismet'] = new \Riv\Service\Akismet\Akismet($connector);

	// Initialize email
	$transport = \Swift_NullTransport::newInstance();
	$app['email'] = \Swift_Mailer::newInstance($transport);

	// Don't cache in local mode
	$app['cache'] = new \Stash\Pool();
}

// Initialize Sentry
$app['sentry'] = new \Raven_Client($app->getOption('sentry_dsn', NULL));

$app->run();
