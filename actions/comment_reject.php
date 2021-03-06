<?php

namespace FelixOnline\Admin\Actions;

class comment_reject extends BaseAction {
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
			$record->setActive(0)
				   ->setPending(0)
				   ->setSpam(0)
				   ->save();
		}

		return 'Comments rejected.';
	}
}