<?php

namespace FelixOnline\Admin\Actions;

class approve_draft extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records) {
		if(count($records) != 1) {
			throw new \Exception('Expecting one record');
		}

		$this->validateAccess();

		$this->validateClass($records, 'FelixOnline\Core\Article');
		$record = new \FelixOnline\Core\Article($records[0]);

		$record->setHidden(1);
		$record->save();

		return 'Article created. To edit it, <a href="'.STANDARD_URL.'draft_articles:details/'.$record->getId().'">click here</a>.';
	}
}