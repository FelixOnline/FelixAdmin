<?php

namespace FelixOnline\Admin\Actions;

class create_article extends BaseAction {
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

		$status = '';

		// Live Blogs
		if($record->getIsLive() && !$record->getBlog()) {
			$blogName = 'article'.$record->getId();

			$blog = new \FelixOnline\Core\Blog();
			$blog->setSprinklerPrefix($blogName);
			$blog->save();

			// Create blog
			try {
				$sprinkler = new \FelixOnline\Sprinkler('http://'.\FelixOnline\Core\Settings::get('sprinkler_host').':'.\FelixOnline\Core\Settings::get('sprinkler_port'), \FelixOnline\Core\Settings::get('sprinkler_admin'));
				$channel = $sprinkler->newChannel($blogName);

				$status .= ' The live blog has been set up for you.';
			} catch(\Exception $e) {
				$status .= ' There was an issue creating the live blog: '.$e->getMessage();
			}

			$record->setBlog($blog);
		}

		// Add author if needed
		try {
			$forceAuthor = new force_author($this->rawPage);
			$forceAuthor->run(array($record->getId()));
		} catch(\Exception $e) {
			return 'Failed to process article authors.';
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

		// If the current user has a seniorEditor role, submit the article straight to the publication stage.
		if (count(array_intersect($userRoles, array('seniorEditor'))) != 0 ||
			(count(array_intersect($userRoles, array('sectionEditor'))) != 0 &&
			$isOneOfMine)) {
			// If user is a senior editor or above, or if the user is the section editor for the category this article is in, auto review and submit for publication.

			$record->setReviewedby($currentuser);
			$record->setHidden(0);
			$record->save();

			$pub = 'The web team will publish the article shortly.';

			if(count(array_intersect($userRoles, array('webMaster'))) != 0) {
				$pub = 'To edit and publish it, <a href="'.STANDARD_URL.'articles/for_publication:details/'.$record->getId().'">click here</a>.';
			}

			return 'Article created and is ready for publication. '.$pub.$status;
		} else {
			$record->setHidden(1);
			$record->save();

			return 'Article created as draft. To edit it, <a href="'.STANDARD_URL.'articles/drafts:details/'.$record->getId().'">click here</a>. When you are ready, go to your draft article and submit it for publication.'.$status;
		}
	}
}