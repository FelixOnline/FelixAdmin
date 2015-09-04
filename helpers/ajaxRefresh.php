<?php

namespace FelixOnline\Admin\Ajax;

class refreshAjaxHelper extends Core {
	public function run() {
		if(!array_key_exists('page', $_POST) || $_POST['page'] == '') {
			$this->error("Page not specified.", 400);
		}

		if(!array_key_exists('page2', $_POST) || $_POST['page2'] == '' || preg_match('/[^0-9]/', $_POST['page2'])) {
			$this->error("Paginator page not specified.", 400);
		}

		$page = $_POST['page'];
		$page2 = $_POST['page2'];

		try {
			$page = new \FelixOnline\Admin\Page($page);
		} catch(Exceptions\PageNotFoundException $e) {
			// no such page
			$this->error("Could not find page.", 404);
		} catch(Exceptions\ForbiddenPageException $e) {
			// no access to page
			$this->error("You do not have permission to access this page.", 403);
		}

		if(!$page->canDo('list')) {
			$this->error("You do not have permission to do this.", 403);
		}

		$manager = $page->getManager();
		$numRecords = $manager->count();
		$manager->limit((-LISTVIEW_PAGINATION_LIMIT + $page2*LISTVIEW_PAGINATION_LIMIT), LISTVIEW_PAGINATION_LIMIT);
		$records = $manager->values();
		$pageData = $page->getPageData();

		$access = \FelixOnline\Admin\UXHelper::getAccess($pageData);

		$actions = array();

		$app = \FelixOnline\Core\App::getInstance();

		if(array_key_exists('actions', $pageData)) {
			foreach($pageData['actions'] as $key => $action) {
				if(isset($action['roles'])) {
					if(count(array_intersect($action['roles'], $app['env']['session']->session['roles'])) == 0) {
						continue; // Cannot access action
					}
				}

				$actions[$key] = $action;
			}
		}

		$return = \FelixOnline\Admin\UXHelper::recordList(
			$_POST['page'],
			$pageData,
			$records,
			$actions,
			$numRecords,
			$page->getPk(),
			$page2,
			$access['details'],
			$pageData['modes']['list']['canDelete'],
			false);

		$this->success(array("form" => $return));
	}
}