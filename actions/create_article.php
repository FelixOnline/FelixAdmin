<?php

namespace FelixOnline\Admin\Actions;

class create_article extends BaseAction {
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
		try {
			$forceAuthor = new force_author($this->rawPage);
			$forceAuthor->run(array($record->getId()));
		} catch(\Exception $e) {
			return 'Failed to process article authors.';
		}

		// If the current user has a webMaster role, submit the article straight to the publication stage.
		if (count(array_intersect($userRoles, array('seniorEditor'))) != 0 ||
			(count(array_intersect($userRoles, array('sectionEditor'))) != 0 && count(array_intersect($currentuser->getCategories(), array($record->getCategory()))) != 0 )) {
			// If user is a senior editor or above, or if the user is the section editor for the category this article is in, auto review and submit for publication.

			$record->setReviewedby($currentuser);
			$record->setHidden(0);
			$record->save();

			return 'Article created and is ready for publication. To edit and publish it, <a href="'.STANDARD_URL.'articles/for_publication:details/'.$record->getId().'">click here</a>.';
		} else {
			$record->setHidden(1);
			$record->save();

			return 'Article created as draft. To edit it, <a href="'.STANDARD_URL.'articles/drafts:details/'.$record->getId().'">click here</a>. When you are ready, go to your draft article and submit it for publication.';
		}
	}
}