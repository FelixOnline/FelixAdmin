<?php

namespace FelixOnline\Admin\Actions;

class BaseAction {
	private $permissions;

	public function __construct($permissions) {
		$this->permissions = $permissions;
	}

	protected function validateClass($records, $class) {
		try {
			foreach($records as $record) {
				$obj = new $class($record);
			}
		} catch(\Exception $e) {
			throw new Exception('Not everything you requested could be found.');
		}
	}

	protected function validateAccess() {
		$app = \FelixOnline\Core\App::getInstance();

		$userRoles = $app['env']['session']->session['roles'];

		if(count($this->permissions) > 0) {
			if(count(array_intersect($userRoles, $this->permissions)) == 0) {
				throw new \Exception('You do not have permission to do this.');
			}
		}
	}
}