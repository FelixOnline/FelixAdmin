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

		if(!array_key_exists('records', $_POST)) {
			$records = array();
		} else {
			$records = $_POST['records'];
		}

		$actionObj = 'FelixOnline\Admin\Actions\\'.$action;

		try {
			if(!class_exists($actionObj)) {
				throw new \Exception('Could not find action');
			}

			$pageObj = new \FelixOnline\Admin\Page($page, true);
			if(!$pageObj->lightLoad($page)) {
				throw new \Exception('You do not have permission to do this');
			}

			$actionObj = new $actionObj($pageObj);
		} catch(\Exception $e) {
			$this->error($e->getMessage(), 500);
		}

		try {
			$message = $actionObj->run($records);

			$this->success(array("message" => $message));
		} catch(\Exception $e) {
			$this->error($e->getMessage(), 400);
		}
	}
}