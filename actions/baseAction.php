<?php

namespace FelixOnline\Admin\Actions;

class BaseAction {
	protected $permissions;
	protected $page;
	protected $rawPage;

	public function __construct($pageObj) {
		$this->rawPage = $pageObj;
		$this->page = $pageObj->getPageData();

		if(isset($pageObj->getPageData()['actions'][$action]['roles'])) {
			$roles = array_merge($pageObj->getPageData()['actions'][$action]['roles'], $pageObj->getPageData()['baseRole']);
		} else {
			$roles = $pageObj->getPageData()['baseRole'];
		}

		$this->permissions = $roles;
	}

	protected function getRecords($records) {
		$obj = array();
		$class = $this->page['model'];

		try {
			foreach($records as $record) {
				$obj[] = new $class($record);
			}
		} catch(\Exception $e) {
			throw new Exception('Not everything you requested could be found.');
		}

		return $obj;
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