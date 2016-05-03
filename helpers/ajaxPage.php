<?php

namespace FelixOnline\Admin\Ajax;

class getPageAjaxHelper extends Core {
	public function run() {
		if(!array_key_exists('page', $_POST) || $_POST['page'] == '') {
			$this->error("No page specified.", 400);
		}

		$page = $_POST['page'];

		if(isset($_POST['pullThrough'])) {
			$pullThrough = $_POST['pullThrough'];
		} else {
			$pullThrough = null;
		}

		try {
			if($page == 'home') {
				$page = new \FelixOnline\Admin\HomePage($page);
			} else {
				$page = new \FelixOnline\Admin\Page($page, false, $pullThrough);
			}

			$output = $page->render(filter_var($_POST['hideTabs'], FILTER_VALIDATE_BOOLEAN), filter_var($_POST['showTitle'], FILTER_VALIDATE_BOOLEAN));

			$this->success(array("screen" => $output, "title" => $page->getName().' - '.SERVICE_NAME, "url" => STANDARD_URL.$_POST['page']));
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