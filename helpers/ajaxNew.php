<?php

namespace FelixOnline\Admin\Ajax;

class newAjaxHelper extends Core {
	public function run() {
		if(!array_key_exists('00page', $_POST) || $_POST['00page'] == '') {
			$this->error("Page not specified.", 400);
		}

		$page = $_POST['00page'];

		try {
			$page = new \FelixOnline\Admin\Page($page);
		} catch(Exceptions\PageNotFoundException $e) {
			// no such page
			$this->error("Could not find page.", 404);
		} catch(Exceptions\ForbiddenPageException $e) {
			// no access to page
			$this->error("You do not have permission to access this page.", 403);
		}

		// Can we save?
		if(!$page->canDo('new')) {
			$this->error("You do not have permission to do this", 403);
		}

		// Does the key exist?
		$class = $page->getPageData()['model'];

		try {
			$model = new $class(); // Initialise a null model
			$pk = $model->pk;
		} catch(\Exception $e) {
			$this->error("Could not find model.", 404);
		}

		// Validate submission
		$badFields = $this->widgetValidator($page, $model, $_POST, $pk);

		if(count($badFields) > 0) {
			$this->error("There is a problem with what you have submitted.", 400, $badFields);
		}

		$now = date('Y-m-d H:i:s');

		// To avoid situation where NOT NULL fields would trip up the creation process
		foreach($model->fields as $fieldName => $fieldObject) {
			if(get_class($fieldObject) == 'FelixOnline\Core\Type\ForeignKey' ||
				get_class($fieldObject) == 'FelixOnline\Core\Type\DateTimeField') {
				continue;
			}

			$setter = 'set'.$this->to_camel_case($fieldName);

			$model->$setter('');
		}

		foreach($page->getPageData()['fields'] as $fieldName => $fieldInfo) {
			if($fieldName == $pk && $pk == 'id') {
				continue;
			}

			if(array_key_exists('multiMap', $fieldInfo) || array_key_exists('choiceMap', $fieldInfo) || array_key_exists('uploader', $fieldInfo)) {
				continue; // Do later - uploader fields will be done by callback actions
			}

			$fieldType = get_class($model->fields[$fieldName]);

			if($fieldInfo['readOnly'] && !$fieldInfo['autoField']) {
				continue;
			} elseif($fieldInfo['readOnly'] && $fieldInfo['autoField']) {
				switch($fieldType) {
					case 'FelixOnline\Core\Type\ForeignKey':
						if($model->fields[$fieldName]->class == 'FelixOnline\Core\User') {
							$app = \FelixOnline\Core\App::getInstance();
							$currentuser = $app['currentuser'];
							
							$value = $currentuser;
						} elseif($model->fields[$fieldName]->class == 'FelixOnline\Core\Image' && $fieldInfo['defaultImage']) {
							$value = new \FelixOnline\Core\Image($fieldInfo['defaultImage']);
						} else {
							$this->error('Trying to automatically set an unsupported foreign key.', 500);
						}
						break;
					case 'FelixOnline\Core\Type\DateTimeField':
						$value = strtotime($now);
						break;
					default:
						$value = '';
						break;
				}
			} elseif($fieldType == 'FelixOnline\Core\Type\ForeignKey') {
				$fkType = $model->fields[$fieldName]->class;

				if($fkType == 'FelixOnline\Core\Text') {
					// Create text field
					$text = new \FelixOnline\Core\Text();

						// We may need to update image details
						$jsonDecoded = json_decode($_POST[$fieldName], true);

						if(!$jsonDecoded) {
							throw new \Exception('Did not get json, this is bad');
						} else {
							$newJson = array();

							foreach($jsonDecoded['data'] as $item) {
								if($item['type'] != 'feliximage') {
									$newJson[] = $item;
								} else {
									$image = new \FelixOnline\Core\Image($item['data']['image']);

									$image->setDescription($item['data']['description']);
									$image->setAttribution($item['data']['attribution']);
									$image->setAttrLink($item['data']['attributionLink']);

									$image->save();

									unset($item['data']['description']);
									$newJson[] = $item;
								}
							}

							$json = array("data" => $newJson);
						}

						$text->setConverted(1);
						$text->setContent(json_encode($json));
						$text->save();

					$value = $text; // Assign new text field
				} else {
					if($_POST[$fieldName] == '') {
						continue; // Dont set a null key
					}

					$value = new $fkType($_POST[$fieldName]);

					// If this is an image we may need to update the image.
					if($fkType == "FelixOnline\Core\Image" && array_key_exists($fieldName.'-descr', $_POST)) {
						// Should be sufficient that just ONE of the fields exists
						$value->setDescription($_POST[$fieldName.'-descr']);
						$value->setAttribution($_POST[$fieldName.'-attr']);
						$value->setAttrLink($_POST[$fieldName.'-attrlink']);

						$value->save();
					}
				}
			} else {
				$value = $_POST[$fieldName];
			}

			$setter = 'set'.$this->to_camel_case($fieldName);

			$model->$setter($value);
		}

		$key = $model->save();

		// Now process any multiMap/choiceMap fields now we have a primary key to insert into map tables
		foreach($page->getPageData()['fields'] as $fieldName => $fieldInfo) {
			if(array_key_exists('multiMap', $fieldInfo)) {
				if(is_array($_POST[$fieldName]) && count($_POST[$fieldName]) > 0) {
					foreach($_POST[$fieldName] as $newRecord) {
						$record = new $fieldInfo['multiMap']['model']();

						$setter1 = 'set'.$this->to_camel_case($fieldInfo['multiMap']['this']);
						$setter2 = 'set'.$this->to_camel_case($fieldInfo['multiMap']['foreignKey']);
						$setter2ObjectClass = $record->fields[$fieldInfo['multiMap']['foreignKey']]->class;

						$record->$setter1($model);
						$record->$setter2((new $setter2ObjectClass($newRecord)));

						$record->save();
					}
				}
			}

			if(array_key_exists('choiceMap', $fieldInfo)) {
				if(is_array($_POST[$fieldName]) && count($_POST[$fieldName]) > 0) {
					foreach($_POST[$fieldName] as $newRecord) {
						$record = new $fieldInfo['choiceMap']['model']();

						$setter1 = 'set'.$this->to_camel_case($fieldInfo['choiceMap']['this']);
						$setter2 = 'set'.$this->to_camel_case($fieldInfo['choiceMap']['field']);

						$record->$setter1($model);
						$record->$setter2($newRecord);

						$record->save();
					}
				}
			}
		}

		if(isset($page->getPageData()['modes']['new']['callback'])) {
			try {
				$action = 'FelixOnline\Admin\Actions\\'.$page->getPageData()['modes']['new']['callback'];

				$action = new $action($page->getPageData()['baseRole']);

				$message = $action->run(array($key));
				$this->success(array("key" => $message));
			} catch(\Exception $e) {
				$this->error("Your entry has been created, however a problem occured in the callback function. Details: ".$e->getMessage(), 500);
			}
		} else {
			if(!$page->canDo('details')) {
				$this->success();
			} else {
				$this->success(array("key" => '<a href="'.STANDARD_URL.$_POST['00page'].':details/'.$key.'">Click here to edit your new entry</a>.'));
			}
		}
	}
}