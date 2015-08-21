<?php

namespace FelixOnline\Admin\Ajax;

class lookupAjaxHelper extends Core {
	public function run() {
		if(!array_key_exists('page', $_POST) || $_POST['page'] == '') {
			$this->error("Page not specified.", 400);
		}

		if(!array_key_exists('widget', $_POST) || $_POST['widget'] == '') {
			$this->error("Widget not specified.", 400);
		}

		if(!array_key_exists('query', $_POST)) { // Query may be empty
			$query = '';
		} else {
			$query = $_POST['query'];
		}

		$page = $_POST['page'];
		$widget = $_POST['widget'];

		try {
			$page = new \FelixOnline\Admin\Page($page);
		} catch(Exceptions\PageNotFoundException $e) {
			// no such page
			$this->error("Could not find page.", 404);
		} catch(Exceptions\ForbiddenPageException $e) {
			// no access to page
			$this->error("You do not have permission to access this page.", 403);
		}

		// Does the widget exist
		if(!array_key_exists($widget, $page->getPageData()['fields'])) {
			$this->error("Widget does not exist.", 404);
		}

		$pageData = $page->getPageData();

		// Multi Foreign Keys widgets use a different way of obtaining the data we need
		if(array_key_exists('multiMap', $pageData['fields'][$widget])) {
			$fieldObject = new $pageData['fields'][$widget]['multiMap']['model'];

			// Find the fieldObject for the underlying column we are interested in
			$fieldType = $fieldObject->fields[$pageData['fields'][$widget]['multiMap']['foreignKey']]->class;
			$fieldObject = new $fieldType();
			$fieldTable = $fieldObject->dbtable;
			$fieldPk = $fieldObject->pk;
			$fieldVal = $pageData['fields'][$widget]['multiMap']['foreignKeyField'];
		} else {
			// We need to find out what type of foreign key we have
			$model = new $pageData['model'];

			// Is widget a foreign key
			if(get_class($model->fields[$widget]) != 'FelixOnline\Core\Type\ForeignKey') {
				$this->error("Widget is not a foreign key.", 400);
			}

			$fieldType = $model->fields[$widget]->class;
			$fieldObject = new $fieldType();
			$fieldTable = $fieldObject->dbtable;
			$fieldPk = $fieldObject->pk;

			if($fieldType == 'FelixOnline\Core\Image') {
				$fieldVal = 'uri';
			} else {
				$fieldVal = $pageData['fields'][$widget]['foreignKeyField'];
			}
		}

		// Find our data!
		$manager = \FelixOnline\Core\BaseManager::build($fieldType, $fieldTable, $fieldPk);

		$manager->filter($fieldVal.' LIKE "%%s%"', array($query));
		$manager->limit(0,10);

		$matches = $manager->values();

		// Render our data
		$return = array();

		if(is_array($matches)) {
			foreach($matches as $match) {
				$return[] = array("id" => $match->fields[$fieldPk]->getValue(),
					"text" =>  $match->fields[$fieldVal]->getValue().' ('.$match->fields[$fieldPk]->getValue().')');
			}
		}

		$this->success($return);
	}
}