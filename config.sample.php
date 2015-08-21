<?php
	/*
	 * Create a config.php with the information below to run on a local dev machine
	 */

	/*
	 * Change these urls to your local versions, e.g http://localhost/felix
	 */
	define('STANDARD_URL','http://www.felixonline.co.uk/admin/'); // Must have trailing slash
	define('UPLOAD_DIRECTORY', '/var/www/felix/img/'); // Path to where the image upload folder is, must have trailing slash
	define('LOCAL', true); // if true then site is hosted locally - don't use pam_auth etc.
	define('LISTVIEW_PAGINATION_LIMIT', 100); // Max number of records per page for lists, and max number of search results
	define('SESSION_NAME', 'felix-admin');
	define('COOKIE_NAME', 'felixonline-admin');
	define('SSH_LOGIN', false); // if login via pam_auth fails, try via ssh2? (this is mostly a thing for the live ICU deployment)
	define('SSH_HOST', ''); // hostname to connect to

	$config = array(
		'db_name' => "",
		'db_host' => "",
		'db_user' => "",
		'db_pass' => "",
		'base_url' => STANDARD_URL,
		'sentry_dsn' => NULL,
		'akismet_api_key' => '',
	);

	/* turn off error reporting */
	error_reporting(0);
	/* to turn on error reporting uncomment line: */
	//error_reporting(E_ERROR | E_WARNING | E_PARSE);

	return $config;
