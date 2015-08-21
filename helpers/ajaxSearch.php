<?php

namespace FelixOnline\Admin\Ajax;

class searchAjaxHelper extends Core {
	public function run() {
		if(!array_key_exists('00page', $_POST) || $_POST['00page'] == '') {
			$this->error("Page not specified.", 400);
		}

		$page = $_POST['00page'];

		try {
			$page = new \FelixOnline\Admin\Page($page);
		} catch(Exceptions\InvalidPageException $e) {
			// no such page
			$this->error("Could not find page.", 404);
		} catch(Exceptions\PageAccessException $e) {
			// no access to page
			$this->error("You do not have permission to access this page.", 403);
		} catch(\Exception $e) {
			$this->error("Unknown error.", 500);
		}

		// Does the key exist?
		$pageData = $page->getPageData();
		$class = $pageData['model'];

		try {
			$model = new $class($key);
		} catch(\Exception $e) {
			$this->error("Could not find model.", 404);
		}

		// Can we search?
		if(!$page->canDo('search')) {
			$this->error("You do not have permission to do this.", 403);
		}

		$manager = $page->getManager();
		$model = new $pageData['model']; // null for checking field type
		$pk = $model->pk;

		$filteredSomething = false;

		try {
			foreach($pageData['fields'] as $fieldName => $fieldInfo) {
				if(isset($pageData['modes']['search']['fields']) && array_search($fieldName, $pageData['modes']['search']['fields']) === FALSE) {
					continue;
				}

				if($_POST[$fieldName] == '') {
					continue; // Don't filter on empty values
				}

				if(array_key_exists('multiMap', $fieldInfo)) {
					continue; // FIXME: These fields are not supported at present.
				}

				$filteredSomething = true;

				$fieldType = get_class($model->fields[$fieldName]);

				if($fieldType == 'FelixOnline\Core\Type\ForeignKey') {
					try {
						$fkType = $model->fields[$fieldName]->class;

						if($fkType = 'FelixOnline\Core\Text') {
							$manager2 = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Text', 'text_story', 'id');
							$manager2->filter('content LIKE "%%s%"', array($_POST[$fieldName]));

							$manager->join($manager2, "LEFT", 'text1');
						} else {
							$fk = new $fkType($_POST[$fieldName]);
							$manager->filter($fieldName.' = "%s"', array($_POST[$fieldName]));
						}
					} catch(\Exception $e) {
						continue;
					}
				} elseif($fieldType == 'FelixOnline\Core\Type\DateTimeField') {
					// Filter time out from datetimefields to increase chance of match
					$manager->filter($fieldName.' LIKE "%%s%"', array(date('Y-m-d', strtotime($_POST[$fieldName]))));
				} elseif($fieldType == 'FelixOnline\Core\Type\BooleanField') {
					switch($_POST[$fieldName]) {
						case 'null':
							continue;
							break;
						case 'true':
							$manager->filter($fieldName.' = %i', array(1));
							break;
						case 'false':
							$manager->filter($fieldName.' = %i', array(0));
							break;
					}
				} else {
					$manager->filter($fieldName.' LIKE "%%s%"', array($_POST[$fieldName]));
				}
			}

			if(!$filteredSomething) {
				$this->error("You haven't specified anything to filter by.", 400);
			}

			$manager->limit(0, LISTVIEW_PAGINATION_LIMIT);

			$models = $manager->values();
		} catch(\Exception $e) {
			$this->error("An error occured whilst searching for the data you have requested.", 500);
		}

		if(!array_key_exists('actions', $pageData)) {
			$actions = array();
		} else {
			$actions = $pageData['actions'];
		}

		$rendered_form = \FelixOnline\Admin\UXHelper::recordList(
			$_POST['00page'],
			$pageData,
			$models,
			$actions,
			count($models),
			$pk,
			1,
			$page->canDo('details'),
			($page->canDo('list') && $pageData['modes']['list']['canDelete']),
			true);

		$this->success(array('form' => $rendered_form));
	}
}