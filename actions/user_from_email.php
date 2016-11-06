<?php

namespace FelixOnline\Admin\Actions;

class user_from_email extends BaseAction {
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
		$failed = false;

		// Check LDAP
		if(LOCAL) {
			if($record->getEmail() == 'valid@example.com') {
				// Testing
				$username = 'test'.rand(0,9999);
			} else {
				$failed = true;
			}
		} else {
			if(preg_match('/[^a-zA-Z0-9-@]/', $record->getEmail()) === 1) {
				$record->purge('Could not generate from email: '.$record->getEmail());
				
				throw new \Exception('Invalid email address.');
			}

			$ldap = ldap_connect("addressbook.ic.ac.uk");
			$bind = ldap_bind($ldap);
			$query = ldap_search($ds, "ou=People,ou=shibboleth,dc=ic,dc=ac,dc=uk", "mail=".$record->getEmail(), array("uid"));
			$data = ldap_get_entries($ldap, $query);
			if($data["count"] > 0) {
				$username = $data[0]['mail'][0];
			} else {
				$failed = true;
			}
		}

		if($failed) {
			$record->purge('Could not generate from email: '.$record->getEmail());

			throw new \Exception('No user found for this email address so no user could be created.');
		} else {
			$record->setUser($username)
				   ->setName($username)
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
		}

		return 'Created and did directory fetch for '.$record->getUser();
	}
}