<?php

namespace FelixOnline\Admin\Actions;

class publish_friday extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records) {
		if(count($records) == 0) {
			throw new \Exception('No articles selected');
		}

		$this->validateAccess();

		$records = $this->getRecords($records);

		if(date('l', time()) == 'Friday') {
			$time = strtotime('today 7am');
		} else {
			$time = strtotime('friday 7am');
		}
		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

		foreach($records as $record) {
			$record->setPublished($time);
			$record->setApprovedby($currentuser);
			$record->save();
		}

		return 'Articles published at '.date("d-M-y H:i", $time).'.';
	}
}