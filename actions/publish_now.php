<?php

namespace FelixOnline\Admin\Actions;

class publish_now extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records, $pullThrough = false) {
		if(count($records) == 0) {
			throw new \Exception('No articles selected');
		}

		$this->validateAccess();

		$records = $this->getRecords($records);

		$time = time();
		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

		foreach($records as $record) {
			$rec = new \FelixOnline\Core\ArticlePublication();

			$rec->setArticle($record);
			$rec->setPublicationDate($time);
			$rec->setPublishedBy($currentuser);
			$rec->setRepublished(0);
			$rec->save();
		}

		return 'Articles published at '.date("d-M-y H:i", $time).'.';
	}
}