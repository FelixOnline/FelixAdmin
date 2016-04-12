<?php

namespace FelixOnline\Admin\Ajax;

class searchAjaxHelper extends Core {
	public function run() {
		if(!array_key_exists('00page', $_POST) || $_POST['00page'] == '') {
			$this->error("Page not specified.", 400);
		}

		if(!array_key_exists('00page2', $_POST) || $_POST['00page2'] == '' || preg_match('/[^0-9]/', $_POST['00page2'])) {
			$this->error("Paginator page not specified.", 400);
		}

		$page = $_POST['00page'];
		$page2 = $_POST['00page2'];

		if(isset($_POST['00pull'])) {
			$pullThrough = $_POST['00pull'];
		} else {
			$pullThrough = null;
		}

		try {
			$page = new \FelixOnline\Admin\Page($page, false, $pullThrough);
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

		foreach($pageData['fields'] as $fieldName => $fieldInfo) {
			if(isset($pageData['modes']['search']['fields']) && array_search($fieldName, $pageData['modes']['search']['fields']) === FALSE) {
				continue;
			}

			if($_POST[$fieldName] == '' || (get_class($model->fields[$fieldName]) == 'FelixOnline\Core\Type\BooleanField' && $_POST[$fieldName] == "null")) {
				continue; // Don't filter on empty values
			}

			if(array_key_exists('multiMap', $fieldInfo)) {
				continue; // FIXME: These fields are not supported at present.
			}

			if(array_key_exists('uploader', $fieldInfo)) {
				continue; // Not supported
			}

			$filteredSomething = true;

			if(array_key_exists('choiceMap', $fieldInfo)) {
				$table2 = (new $fieldInfo['choiceMap']['model'])->dbtable;

				$manager2 = \FelixOnline\Core\BaseManager::build($fieldInfo['choiceMap']['model'], $table2);

				$manager2->filter($fieldInfo['choiceMap']['field'].' LIKE "%%s%"', array($_POST[$fieldName]));

				$manager->join($manager2, null, null, $fieldInfo['choiceMap']['this']);
				continue;
			}

			$fieldType = get_class($model->fields[$fieldName]);

			if($fieldType == 'FelixOnline\Core\Type\ForeignKey') {
				try {
					$fkType = $model->fields[$fieldName]->class;

					if($fkType == '') {
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

		$numRecords = $manager->count();
		$manager->limit((-LISTVIEW_PAGINATION_LIMIT + $page2*LISTVIEW_PAGINATION_LIMIT), LISTVIEW_PAGINATION_LIMIT);

		$models = $manager->values(true);

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
			$numRecords,
			$pk,
			$page2,
			$page->canDo('details'),
			($page->canDo('list') && $pageData['modes']['list']['canDelete']),
			false,
			true);

		$this->success(array('form' => $rendered_form));
	}
}