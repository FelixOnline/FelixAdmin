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

		$records = $this->getRecords($records);
		$record = $records[0];

		// Add author if needed
		$authors = $record->getAuthors();

		if(!$authors) {
			$app = \FelixOnline\Core\App::getInstance();
			$currentuser = $app['currentuser'];

			$rec2 = new \FelixOnline\Core\ArticleAuthor();
			$rec2->setArticle($record);
			$rec2->setAuthor($currentuser);
			$rec2->save();
		}

		// If the current user has a webMaster role, submit the article straight to the publication stage.
		$app = \FelixOnline\Core\App::getInstance();

		$userRoles = $app['env']['session']->session['roles'];

		if (count(array_intersect($userRoles, array('webMaster'))) != 0) {
			$record->setHidden(0);
			$record->save();

			return 'Article created and is ready for publication. To edit it, <a href="'.STANDARD_URL.'pending_articles:details/'.$record->getId().'">click here</a>.';
		} else {
			$record->setHidden(1);
			$record->save();

			return 'Article created as draft. To edit it, <a href="'.STANDARD_URL.'draft_articles:details/'.$record->getId().'">click here</a>. When you are ready, go to your draft article and submit it for publication.';
		}
	}
}