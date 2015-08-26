<?php
	namespace FelixOnline\Admin;

	// Felix Admin App - get_twitter for Sir Trevor
	// by Philip Kent

	// Released under the MIT license

	if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		header('HTTP/1.1 403 Forbidden');

		echo '<h1>Forbidden</h1><p>This URL is for AJAX requests only.';
		die();
	}

	if($_SERVER['REQUEST_METHOD'] != 'GET') {
		header("HTTP/1.1 405 Invalid method");
		header("Cache-Control: no-cache, must-revalidate", false);
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);

		echo 'GET requests only.';
		exit;
	}

	require('core/setup.php');

	global $currentuser;
	$currentuser = new \FelixOnline\Core\CurrentUser();

	if(!$currentuser->isLoggedIn() && $_POST['q'] != 'login' && $_POST['q'] != 'logout') {
		header('HTTP/1.1 403 Forbidden');
		header("Cache-Control: no-cache, must-revalidate", false);
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);

		echo 'Please log in.';

		session_write_close();
		exit;
	}

	if(!isset($_GET['tweet_id']) || preg_match('/[^0-9]/', $_GET['tweet_id'])) {
		header('HTTP/1.1 400 Invalid Request');
		header("Cache-Control: no-cache, must-revalidate", false);
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);

		echo 'Tweet not specified';

		session_write_close();
		exit;
	}

	// Step 1: Get bearer token
	$credentials = new \Twitter\Config\AppCredentials(\FelixOnline\Core\Settings::get('twitter_key'),
		\FelixOnline\Core\Settings::get('twitter_secret'));

	$client = new \Twitter\Client($credentials);

	$app = $client->connect();

	// See if a bearer token already exists
	try {
		$token = \FelixOnline\Core\Settings::get('twitter_bearer');
		$app->getCredentials()->setBearerToken($token);
	} catch(\Exception $e) {
		// Clearly it doesn't
		$app->createBearerToken();
		$token = $app->getCredentials()->getBearerToken();

		// Save for future use
		$setting = (new \FelixOnline\Core\Settings())
			->setSetting('twitter_bearer')
			->setDescription('Used in the article editor. Do not change.')
			->setValue($token)
			->save();
	}

	// Step 2: Get what we wanted
	try {
		$tweet = $app->get('statuses/show.json', array('id' => $_GET['tweet_id']));
	} catch(\GuzzleHttp\Exception\ClientException $e) {
		// 4xx error - invalidate the bearer token
		(new \FelixOnline\Core\Settings('twitter_bearer'))->delete();

		header('HTTP/1.1 500 Internal Server Error');
		header("Cache-Control: no-cache, must-revalidate", false);
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);

		echo $e->getMessage();

		session_write_close();
		exit;
	} catch(\GuzzleHttp\Exception\ServerException $e) {
		// 5xx error

		header('HTTP/1.1 500 Internal Server Error');
		header("Cache-Control: no-cache, must-revalidate", false);
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);

		echo $e->getMessage();

		session_write_close();
		exit;
	} catch(\GuzzleHttp\Exception\RequestException $e) {
		// Other errrors

		header('HTTP/1.1 500 Internal Server Error');
		header("Cache-Control: no-cache, must-revalidate", false);
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);

		echo $e->getMessage();

		session_write_close();
		exit;
	}

	$json = $tweet->json();

	// Step 3: Render
	header("HTTP/1.1 200 OK");
	header("Cache-Control: no-cache, must-revalidate", false);
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);
	header("Content-Type: text/json", false);

	echo json_encode($json);

	exit;
