<?php

namespace FelixOnline\Admin\Ajax;

class actionAjaxHelper extends Core {
	public function run() {
		if(!array_key_exists('action', $_POST) || $_POST['action'] == '') {
			$this->error("Action not specified.", 400);
		}

		if(!array_key_exists('page', $_POST) || $_POST['page'] == '') {
			$this->error("Page not specified.", 400);
		}

		$action = $_POST['action'];
		$page = $_POST['page'];

		if(isset($_POST['pull'])) {
			$pullThrough = $_POST['pull'];
		} else {
			$pullThrough = null;
		}

		if(!array_key_exists('records', $_POST)) {
			$records = array();
		} else {
			$records = $_POST['records'];
		}

		$actionObj = 'FelixOnline\Admin\Actions\\'.$action;
		$app = \FelixOnline\Core\App::getInstance();

		try {
			if(!class_exists($actionObj)) {
				throw new \Exception('Could not find action');
			}

			$pageObj = new \FelixOnline\Admin\Page($page, true, $pullThrough);
			if(!$pageObj->lightLoad($page)) {
				throw new \Exception('You do not have permission to do this');
			}

			$data = $pageObj->getPageData();
			$data = $data['actions'][$action];

			if(isset($action['roles'])) {
				if(count(array_intersect($action['roles'], $app['env']['session']->session['roles'])) == 0) {
					throw new \Exception('You do not have permission to do this');
				}
			}

			$actionObj = new $actionObj($pageObj);
		} catch(\Exception $e) {
			$this->error($e->getMessage(), 500);
		}

		try {
			$message = $actionObj->run($records, $pullThrough);

			$this->success(array("message" => $message));
		} catch(\Exception $e) {
			$this->error($e->getMessage(), 400);
		}
	}
}