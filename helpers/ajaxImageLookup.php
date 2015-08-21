<?php

namespace FelixOnline\Admin\Ajax;

class imageLookupAjaxHelper extends Core {
	public function run() {
		if(!array_key_exists('query', $_POST)) { // Query may be empty
			$query = '';
		} else {
			$query = $_POST['query'];
		}

		// Find our data!
		$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Image', 'image', 'id');

		$manager->filter('uri LIKE "%%s%"', array($query));
		$manager->limit(0,10);

		$matches = $manager->values();

		$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\Image', 'image', 'id');

		$manager->filter('description LIKE "%%s%"', array($query));
		$manager->limit(0,10);

		$matches2 = $manager->values();

		if(!is_array($matches) && !is_array($matches2)) {
			$matches = array();
		} elseif(is_array($matches) && !is_array($matches2)) {
			$matches = $matches;
		} elseif(!is_array($matches) && is_array($matches2)) {
			$matches = $matches2;
		} else {
			$final = array();
			$matches = array_merge($matches, $matches2);

			foreach($matches as $match) {
				if(!in_array($match, $final, true)) {
					$final[] = $match;
				}
			}

			$matches = $final;
		}

		// Render our data
		$return = array();

		if(is_array($matches)) {
			foreach($matches as $match) {
				$return[] = array("id" => $match->getId(),
					"url" => $match->getURL(),
					"text" => $match->getUri().' ('.$match->getId().')');
			}
		}

		$this->success($return);
	}
}