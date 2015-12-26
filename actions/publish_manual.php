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

		if($record->getPublished()) {
			$app = \FelixOnline\Core\App::getInstance();
			$currentuser = $app['currentuser'];

			$record->setApprovedby($currentuser);
			$record->save();

			return 'Article published at '.date("d-M-y H:i", $record->getPublished()).'.';
		} else {
			return 'Article not published';
		}
	}
}