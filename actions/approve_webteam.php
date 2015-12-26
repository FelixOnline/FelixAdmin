<?php

namespace FelixOnline\Admin\Actions;

class approve_accept extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records) {
		if(count($records) == 0) {
			throw new \Exception('No articles selected');
		}

		$this->validateAccess();

		$records = $this->getRecords($records);

		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

		foreach($records as $record) {
			$record->setReviewedby($currentuser);
			$record->save();
		}

		return 'Articles submitted to web team for publication.';
	}
}