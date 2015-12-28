<?php

namespace FelixOnline\Admin\Ajax;

class imageAjaxHelper extends Core {
	public function run() {
		if(!array_key_exists('name', $_POST) || $_POST['name'] == '') {
			$this->error("Field name not specified.", 400);
		}

		if(!array_key_exists('hasEditor', $_POST) || $_POST['hasEditor'] == '') {
			$this->error("Editor setting not specified.", 400);
		}

		if(!array_key_exists('image', $_POST) || $_POST['image'] == '' || preg_match('/[^0-9]/', $_POST['page2'])) {
			$this->error("Image not specified.", 400);
		}

		$name = $_POST['name'];
		$hasEditor = $_POST['hasEditor'];
		$image = $_POST['image'];

		try {
			$image = new \FelixOnline\Core\Image($image);
		} catch(\FelixOnline\Exceptions\ModelNotfoundException $e) {
			// no such image
			$this->error("Could not find image - has it been deleted?", 404);
		}

		$return = \FelixOnline\Admin\UXHelper::imageForm($name, $image, $hasEditor);

		$this->success(array("form" => $return, "id" => $image->getId()));
	}
}