<?php

namespace FelixOnline\Admin\Actions;

class unpublish extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records, $pullThrough = false) {
		if(count($records) == 0) {
			throw new \Exception('No articles selected');
		}

		$this->validateAccess();

		$records = $this->getRecords($records);

		foreach($records as $record) {
			// Check if events exist
			$mgr = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\ArticlePublication', 'article_publication');

			$count = $mgr->filter('article = %i', array($record->getId()))->values();

			foreach($count as $pub) {
				$pub->delete();
			}
		}

		return 'Articles unpublished.';
	}
}