<?php

namespace FelixOnline\Admin\Actions;

class comment_reject extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records) {
		if(count($records) == 0) {
			throw new \Exception('No comments selected');
		}

		$this->validateAccess();

		$this->validateClass($records, 'FelixOnline\Core\Comment');

		foreach($records as $record) {
			$record = new \FelixOnline\Core\Comment($record);

			$record->setActive(0)
				   ->setPending(0)
				   ->setSpam(0)
				   ->save();
		}

		return 'Comments rejected.';
	}
}