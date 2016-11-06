<?php

namespace FelixOnline\Admin\Actions;

class user_create extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records, $pullThrough = false) {
		if(count($records) != 1) {
			throw new \Exception('Expecting one record');
		}

		$this->validateAccess();

		$records = $this->getRecords($records);
		$record = $records[0];

		$record->setName($record->getUser())
			   ->setEmail($record->getUser()."@imperial.ac.uk")
			   ->setInfo(json_encode(array()))
			   ->setVisits(0)
			   ->setIp(0)
			   ->setShowEmail(TRUE)
			   ->setShowLdap(TRUE)
			   ->save();

		// Fetch other LDAP information
		try {
			$ldapUpdate = new user_ldap($this->rawPage);
			$ldapUpdate->run(array($record->getUser()));
		} catch(\Exception $e) { }

		return 'Created and did directory fetch for '.$record->getUser();
	}
}