<?php

namespace FelixOnline\Admin\Actions;

class liveblog_update extends BaseAction {
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

				$status .= 'The live blog has been set up for you.';
			} catch(\Exception $e) {
				$status .= 'There was an issue creating the live blog: '.$e->getMessage();
			}

			$record->setBlog($blog);
		} else {
			$status .= 'Saved.';
		}

		$record->save();

		return $status;
	}
}