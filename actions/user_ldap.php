<?php

namespace FelixOnline\Admin\Actions;

class user_ldap extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records) {
		if(count($records) == 0) {
			throw new \Exception('No users selected');
		}

		$this->validateAccess();

		$this->validateClass($records, 'FelixOnline\Core\User');

		foreach($records as $record) {
			$record = new \FelixOnline\Core\User($record);
			$record->syncLdap();
		}

		return 'Directory fetch complete.';
	}
}