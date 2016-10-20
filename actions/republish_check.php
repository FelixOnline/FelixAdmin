<?php

namespace FelixOnline\Admin\Actions;

class republish_check extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records, $pullThrough = false) {
		$this->validateAccess();

		$records = $this->getRecords($records);
		$string = '';

		foreach($records as $record) {
			// Check if events exist
			$mgr = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\ArticlePublication', 'article_publication');

			$count = $mgr->filter('article = %i', array($record->getArticle()->getId()))->count();

			if($count > 1) {
				$record->setRepublished(true)->save();
				$string = 'Article republished';
			} else {
				$string = 'Article published';
			}
		}

		return $string;
	}
}