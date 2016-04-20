<?php
	namespace FelixOnline\Admin;

	// Felix Admin App
	// by Philip Kent

	// Released under the MIT license

	if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		header('HTTP/1.1 403 Forbidden');

		echo '<h1>Forbidden</h1><p>This URL is for AJAX requests only.';
		die();
	}

	if($_SERVER['REQUEST_METHOD'] != 'POST') {
		header("HTTP/1.1 405 Invalid method");
		header("Cache-Control: no-cache, must-revalidate", false);
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);
		header("Content-Type: text/json", false);

		echo json_encode(array("message" => 'POST requests only.', "widgets" => array()));
		exit;
	}

	require('core/setup.php');

	$app = \FelixOnline\Core\App::getInstance();
	$currentuser = $app['currentuser'];

	if(!$currentuser->isLoggedIn() && $_POST['q'] != 'login' && $_POST['q'] != 'logout') {
		$app['env']['session']->reset(); // Clear old session variables
		
		header('HTTP/1.1 403 Forbidden');
		header("Cache-Control: no-cache, must-revalidate", false);
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);
		header("Content-Type: text/json", false);

		echo json_encode(array("message" => 'Please log in.', "widgets" => array()));

		session_write_close();
		exit;
	}

	if($_POST['00csrf'] != $_COOKIE['felixonline_csrf_admin']) {
		header('HTTP/1.1 403 Forbidden');
		header("Cache-Control: no-cache, must-revalidate", false);
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);
		header("Content-Type: text/json", false);

		echo json_encode(array("message" => 'Security error.', "widgets" => array()));

		session_write_close();
		exit;
	}

	try {
		$className = '\FelixOnline\Admin\Ajax\\'.$_POST['q'].'AjaxHelper';

		if(!class_exists($className)) {
			throw new Exceptions\HelperNotFoundException($className);
		}

		$obj = new $className();
		$obj->run();
	} catch(Exceptions\HelperNotFoundException $e) {
		header("HTTP/1.1 404 Not Found");
		header("Cache-Control: no-cache, must-revalidate", false);
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);
		header("Content-Type: text/json", false);

		echo json_encode(array("message" => 'The requested endpoint could not be found.', "widgets" => array()));

		session_write_close();
		exit;
	} catch(\Exception $e) {
		header("HTTP/1.1 500 Internal Server Error");
		header("Cache-Control: no-cache, must-revalidate", false);
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT", false);
		header("Content-Type: text/json", false);

		if(LOCAL) {
			$extra = "<br><br>(".get_class($e).") ".$e->getMessage().'<br>At '.$e->getFile().':'.$e->getLine()."<br><br><pre>".$e->getTraceAsString()."</pre>";
		} else {
			$extra = $e->getMessage();
		}

		echo json_encode(array("message" => 'A fatal error has occured. This issue has been reported.'.$extra, "widgets" => array()));

		$app = \FelixOnline\Core\App::getInstance();
		$event_id = $app['sentry']->getIdent($app['sentry']->captureException($e));

		session_write_close();
		exit;
	}