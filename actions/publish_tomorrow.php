<?php

namespace FelixOnline\Admin\Actions;

class publish_tomorrow extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records, $pullThrough = false) {
		if(count($records) == 0) {
			throw new \Exception('No articles selected');
		}

		$this->validateAccess();

		$records = $this->getRecords($records);

		if((int) date('G', time()) > 7) {
			$time = strtotime('tomorrow 7am');
		} else {
			$time = strtotime('today 7am');
		}
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