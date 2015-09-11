<?php

namespace FelixOnline\Admin\Actions;

class approve_lastfriday extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records) {
		if(count($records) == 0) {
			throw new \Exception('No articles selected');
		}

		$this->validateAccess();

		$this->validateClass($records, 'FelixOnline\Core\Article');

		$time = strtotime('last friday 7am');
		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

		foreach($records as $record) {
			$record = new \FelixOnline\Core\Article($record);
			$record->setPublished($time);
			$record->setApprovedby($currentuser);
			$record->save();
		}

		return 'Articles published at '.date("d-M-y H:i", $time).'.';
	}
}