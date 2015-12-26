<?php

namespace FelixOnline\Admin\Actions;

class approve_reject extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records) {
		if(count($records) == 0) {
			throw new \Exception('No articles selected');
		}

		$this->validateAccess();

		$records = $this->getRecords($records);

		foreach($records as $record) {
			$record->setHidden(1);
			$record->save();
		}

		return 'Articles rejected to author for amendment.';
	}
}