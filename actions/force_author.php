<?php

namespace FelixOnline\Admin\Actions;

class force_author extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records) {
		if(count($records) != 1) {
			throw new \Exception('Expecting one record');
		}

		$this->validateAccess();

		$app = \FelixOnline\Core\App::getInstance();
		$userRoles = $app['env']['session']->session['roles'];
		$currentuser = $app['currentuser'];

		$records = $this->getRecords($records);
		$record = $records[0];

		// Add author if needed
		$authors = $record->getAuthors();

		$isauthor = false;

		if($authors) {
			foreach($authors as $author) {
				if($author->getUser() == $currentuser->getUser()) {
					$isauthor = true;
				}
			}
		}

		if(!$authors ||
			(count(array_intersect($userRoles, array('sectionEditor'))) == 0 && !$isauthor)) {
			$app = \FelixOnline\Core\App::getInstance();

			$rec2 = new \FelixOnline\Core\ArticleAuthor();
			$rec2->setArticle($record);
			$rec2->setAuthor($currentuser);
			$rec2->save();
		}

		return 'Saved';
	}
}