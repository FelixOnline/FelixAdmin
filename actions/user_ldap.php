<?php

namespace FelixOnline\Admin\Actions;

class user_ldap extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records, $pullThrough = false) {
		if(count($records) == 0) {
			throw new \Exception('No users selected');
		}

		$this->validateAccess();

		$records = $this->getRecords($records);

		foreach($records as $record) {
			$record->syncLdap();
		}

		return 'Directory fetch complete.';
	}
}