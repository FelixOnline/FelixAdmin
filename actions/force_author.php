<?php

namespace FelixOnline\Admin\Actions;

class force_author extends BaseAction {
	public function __construct($permissions) {
		parent::__construct($permissions);
	}

	public function run($records, $pullThrough = false) {
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

		// Is posted in own section
		$isOneOfMine = false;

		if(count(array_intersect($userRoles, array('seniorEditor'))) == 0) {
			foreach($currentuser->getCategories() as $cat) {
				if($cat->getId() == $record->getCategory()->getId()) {
					$isOneOfMine = true;
				}
			}
		}

		// If no authors, or not a section editor and didn't specify self, or is a section editor but not a senior editor and not own section and isnt an author
		if(!$authors ||
			(count(array_intersect($userRoles, array('sectionEditor'))) == 0 && !$isauthor) ||
			(count(array_intersect($userRoles, array('sectionEditor'))) != 0 && count(array_intersect($userRoles, array('seniorEditor'))) == 0 && !$isOneOfMine && !$isauthor)) {
			$app = \FelixOnline\Core\App::getInstance();

			$rec2 = new \FelixOnline\Core\ArticleAuthor();
			$rec2->setArticle($record);
			$rec2->setAuthor($currentuser);
			$rec2->save();
		}

		$blogAction = new liveblog_update($this->rawPage);
		$status = $blogAction->run(array($record->getId()));

		return 'Saved. '.$status;
	}
}