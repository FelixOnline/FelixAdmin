<?php

namespace FelixOnline\Admin\Actions;

class comment_notspam extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records, $pullThrough = false) {
		if(count($records) == 0) {
			throw new \Exception('No comments selected');
		}

		$this->validateAccess();

		$records = $this->getRecords($records);

		foreach($records as $record) {
			$record->markAsHam();
		}

		return 'Comments returned to pending status and marked as not spam.';
	}
}