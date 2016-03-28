<?php

namespace FelixOnline\Admin\Actions;

class publish_manual extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records) {
		if(count($records) != 1) {
			throw new \Exception('Expected one article');
		}

		$this->validateAccess();

		$records = $this->getRecords($records);
		$record = $records[0];

		$blogAction = new liveblog_update($this->rawPage);
		$status = $blogAction->run(array($record->getId()));

		if($record->getPublished()) {
			$app = \FelixOnline\Core\App::getInstance();
			$currentuser = $app['currentuser'];

			$record->setApprovedby($currentuser);
			$record->save();

			return 'Article saved and published at '.date("d-M-y H:i", $record->getPublished()).'. '.$status;
		} else {
			return 'Article saved but not published. '.$status;
		}
	}
}