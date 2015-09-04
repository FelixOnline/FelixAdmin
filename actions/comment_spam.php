<?php

namespace FelixOnline\Admin\Actions;

class comment_spam extends BaseAction {
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

			$record->markAsSpam();

			$record->setActive(0)
				   ->setPending(0)
				   ->setSpam(1)
				   ->save();
		}

		return 'Comments submitted to the spam detection service and marked as spam.';
	}
}