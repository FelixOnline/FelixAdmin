<?php

namespace FelixOnline\Admin\Ajax;

class rapAjaxHelper extends Core {
	public function run() {
		if(!array_key_exists('feedback', $_POST) || $_POST['feedback'] == '') {
			$this->error("Message not specified.", 400);
		}

		$app = \FelixOnline\Core\App::getInstance();
		$app['sentry']->context->clear();
		$app['sentry']->user_context($app['env']['session']->session);
		$app['sentry']->extra_context(array("url" => $_POST['url'], "browser" => $_SERVER['HTTP_USER_AGENT']));
		$event_id = $app['sentry']->getIdent($app['sentry']->captureMessage($_POST['feedback']));

		if ($app['sentry']->getLastError() !== null) {
			$this->error("Your report could not be sent due to a systems error. Please try again later.", 500);
		}

		$this->success(array('id' => $event_id));
	}
}