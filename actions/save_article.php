<?php

namespace FelixOnline\Admin\Actions;

class save_article extends BaseAction {
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

		$blogAction = new liveblog_update($this->rawPage);
		$status = $blogAction->run(array($record->getId()));

		return 'Your changes have been saved. '.$status;
	}
}