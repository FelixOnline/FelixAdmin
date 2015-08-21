<?php

namespace FelixOnline\Admin\Ajax;

class actionAjaxHelper extends Core {
	public function run() {
		if(!array_key_exists('action', $_POST) || $_POST['action'] == '') {
			$this->error("Action not specified.", 400);
		}

		$action = $_POST['action'];

		if(!array_key_exists('records', $_POST)) {
			$records = array();
		} else {
			$records = $_POST['records'];
		}

		$action = 'FelixOnline\Admin\Actions\\'.$action;

		try {
			$action = new $action($page);
		} catch(\Exception $e) {
			$this->error("Could not find action.", 500);
		}

		try {
			$message = $action->run($records);

			$this->success(array("message" => $message));
		} catch(\Exception $e) {
			$this->error($e->getMessage(), 400);
		}
	}
}