<?php

namespace FelixOnline\Admin\Actions;

class bulk_delete extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records, $pullThrough = false) {
		if(!$this->page['modes']['list']['canDelete']) {
			throw new \Exception('You do not have permission to do this.');
		}

		if(count($records) == 0) {
			throw new \Exception('No records selected');
		}

		$this->validateAccess();

		$records = $this->getRecords($records);

		foreach($records as $record) {
			$record->delete();
		}

		return 'Records deleted.';
	}
}