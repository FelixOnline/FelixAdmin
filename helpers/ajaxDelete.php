<?php

namespace FelixOnline\Admin\Ajax;
use FelixOnline\Admin\Exceptions;

class deleteAjaxHelper extends Core {
	public function run() {
		if(!array_key_exists('page', $_POST) || $_POST['page'] == '') {
			$this->error("Page not specified.", 400);
		}

		if(!array_key_exists('key', $_POST) || $_POST['key'] == '') {
			$this->error("Key not specified.", 400);
		}

		$page = $_POST['page'];
		$key = $_POST['key'];

		try {
			$page = new \FelixOnline\Admin\Page($page);

			$page->isRecordAccessible($key);
		} catch(Exceptions\PageNotFoundException $e) {
			// no such page
			$this->error("Could not find page.", 404);
		} catch(Exceptions\ForbiddenPageException $e) {
			// no access to page
			$this->error("You do not have permission to access this page.", 403);
		} catch(Exceptions\ForbiddenRecordException $e) {
			// no access to record
			$this->error("Could not find what you want to delete.", 404);
		}

		// Does the key exist?
		$class = $page->getPageData()['model'];

		try {
			$model = new $class($key);
		} catch(\Exception $e) {
			$this->error("Could not find model.", 404);
		}

		// Can we delete?
		if(!$page->canDo('list')) {
			$this->error("You do not have permission to do this.", 403);
		}

		if(!$page->getPageData()['modes']['list']['canDelete']) {
			$this->error("You do not have permission to do this.", 403);
		}

		try {
			$model->delete();
		} catch(\Exception $e) {
			$this->error("Failed to delete this record. There may be other data which depends on this record.", 500);
		}

		$this->success("Record deleted.");
	}
}