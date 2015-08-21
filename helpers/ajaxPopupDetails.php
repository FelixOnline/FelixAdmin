<?php

namespace FelixOnline\Admin\Ajax;

class popupDetailsAjaxHelper extends Core {
	public function run() {
		if(!array_key_exists('00page', $_POST) || $_POST['00page'] == '') {
			$this->error("Page not specified.", 400);
		}

		if(!array_key_exists('00key', $_POST) || $_POST['00key'] == '') {
			$this->error("Key not specified.", 400);
		}

		$page = $_POST['00page'];
		$key = $_POST['00key'];

		try {
			$page = new \FelixOnline\Admin\Page($page);

			$page->isRecordAccessible($key);
			$helper = $page->getSpecificPageHelper('details/'.$key);
			$output = $helper->modalRender();

			$this->success(array("form" => $output));
		} catch(Exceptions\PageNotFoundException $e) {
			// no such page
			$this->error("Could not find page.", 404);
		} catch(Exceptions\ForbiddenPageException $e) {
			// no access to page
			$this->error("You do not have permission to access this page.", 403);
		} catch(Exceptions\ForbiddenRecordException $e) {
			// no access to page
			$this->error("Could not find what you want to view.", 404);
		}
	}
}