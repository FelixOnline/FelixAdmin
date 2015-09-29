<?php
	namespace FelixOnline\Admin;

	// Felix Admin App
	// by Philip Kent and others

	// Released under the MIT license
	// (c) Felix Imperial and authors
	// www.felixonline.co.uk
	require('core/setup.php');

	$app = \FelixOnline\Core\App::getInstance();
	$currentuser = $app['currentuser'];

	if(!$currentuser->isLoggedIn()) {
		$app['env']['session']->reset(); // Clear old session variables
		echo UXHelper::header('Login');
		echo UXHelper::login();
		echo UXHelper::footer();

		return;
	}

	if(!array_key_exists('q', $_GET)) {
		$page = 'home';
	} else {
		$page = $_GET['q'];
	}

	try {
		if($page != 'home') {
			$page = new Page($page);

			$page->render();
		} else {
			$page = new HomePage();

			$page->render();
		}
	} catch(Exceptions\PageNotFoundException $e) {
		echo UXHelper::header('Not found');
		echo UXHelper::notfound();
		echo UXHelper::footer();
	} catch(Exceptions\HelperNotFoundException $e) {
		echo UXHelper::header('Not found');
		echo UXHelper::notfound();
		echo UXHelper::footer();
	} catch(Exceptions\RecordNotFoundException $e) {
		echo UXHelper::header('Not found');
		echo UXHelper::notfound();
		echo UXHelper::footer();
	} catch(Exceptions\ForbiddenPageException $e) {
		echo UXHelper::header('Forbidden');
		echo UXHelper::forbidden();
		echo UXHelper::footer();
	} catch(Exceptions\ForbiddenRecordException $e) {
		echo UXHelper::header('Forbidden');
		echo UXHelper::forbidden();
		echo UXHelper::footer();
	} catch(\Exception $e) {
		// Need to log
		$app = \FelixOnline\Core\App::getInstance();
		$event_id = $app['sentry']->getIdent($app['sentry']->captureException($e));
		echo UXHelper::header('Error');
		echo UXHelper::error($e);
		echo UXHelper::footer();
	}

	session_write_close();
