<?php

namespace FelixOnline\Admin\Ajax;

class saveAjaxHelper extends Core {
	public function run() {
		if(!array_key_exists('00page', $_POST) || $_POST['00page'] == '') {
			$this->error("Page not specified.", 400);
		}

		if(!array_key_exists('00key', $_POST) || $_POST['00key'] == '') {
			$this->error("Key not specified.", 400);
		}

		$page = $_POST['00page'];
		$key = $_POST['00key'];

		if(isset($_POST['00pull'])) {
			$pullThrough = $_POST['00pull'];
		} else {
			$pullThrough = null;
		}

		try {
			$page = new \FelixOnline\Admin\Page($page, false, $pullThrough);

			$page->isRecordAccessible($key);
		} catch(Exceptions\InvalidPageException $e) {
			// no such page
			$this->error("Could not find page.", 404);
		} catch(Exceptions\PageAccessException $e) {
			// no access to page
			$this->error("You do not have permission to access this page.", 403);
		} catch(\FelixOnline\Exceptions\ModelNotFoundException $e) {
			// no access to page
			$this->error("Could not find what you want to save.", 404);
		} catch(\Exception $e) {
			$this->error("Unknown error.", 500);
		}

		// Can we save?
		if(!$page->canDo('details') ||
			(array_key_exists('readOnly', $page->getPageData()['modes']['details']) && $page->getPageData()['modes']['details']['readOnly'] == true)) {
			$this->error("You do not have permission to do this", 403);
		}

		$class = $page->getPageData()['model'];
		$model = new $class($key); //test for existance earlier up
		$pk = $model->pk;

		// Validate submission
		$badFields = $this->widgetValidator($page, $model, $_POST, $pk);

		if(count($badFields) > 0) {
			$this->error("There is a problem with what you have submitted.", 400, $badFields);
		}

		try {
			foreach($page->getPageData()['fields'] as $fieldName => $fieldInfo) {
				if($fieldName == $pk) {
					continue;
				}

				if($fieldInfo['readOnly']) {
					continue;
				}

				if($fieldInfo['uploader']) {
		 			continue;
		 		}

				if(array_key_exists('multiMap', $fieldInfo)) {
					$table = (new $fieldInfo['multiMap']['model'])->dbtable;

					$oldRecords = \FelixOnline\Core\BaseManager::build($fieldInfo['multiMap']['model'], $table)
						->filter($fieldInfo['multiMap']['this'].' = "%s"', array($key))
						->values();

					if(is_array($oldRecords) && count($oldRecords) > 0) {
						// Delete old values
						foreach($oldRecords as $record) {
							$record->delete();
						}
					}

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
				} elseif(array_key_exists('choiceMap', $fieldInfo)) {
					$table = (new $fieldInfo['choiceMap']['model'])->dbtable;

					$oldRecords = \FelixOnline\Core\BaseManager::build($fieldInfo['choiceMap']['model'], $table)
						->filter($fieldInfo['choiceMap']['this'].' = "%s"', array($key))
						->values();

					$getter = 'get'.$this->to_camel_case($fieldInfo['choiceMap']['field']);

					// Step 1: Delete records not in the $_POST;
					if($oldRecords) {

						foreach($oldRecords as $oldRecord) {
							if(!$_POST[$fieldName] || array_search($oldRecord->$getter(), $_POST[$fieldName], true) === FALSE) {
								$oldRecord->delete();
							}
						}
					}

					// Step 2: Add new records not in $oldRecords
					if($_POST[$fieldName]) {
						foreach($_POST[$fieldName] as $newItem) {
							$found = false;

							if($oldRecords) {
								foreach($oldRecords as $oldRecord) {
									if($oldRecord->$getter() == $newItem) {
										$found = true;
									}
								}
							}

							if(!$found) {
								$tempRecord = new $fieldInfo['choiceMap']['model']();
								$setter1 = 'set'.$this->to_camel_case($fieldInfo['choiceMap']['this']);
								$setter2 = 'set'.$this->to_camel_case($fieldInfo['choiceMap']['field']);

								$tempRecord->$setter1($model);
								$tempRecord->$setter2($newItem);
								$tempRecord->save();
							}
						}
					}
				} else {
					$fieldType = get_class($model->fields[$fieldName]);

					if($fieldType == 'FelixOnline\Core\Type\ForeignKey') {
						$fkType = $model->fields[$fieldName]->class;

						if($fkType == 'FelixOnline\Core\Text') {
							if($model->fields[$fieldName]->getValue() != null) {
								$id = $model->fields[$fieldName]->getValue()->getId();

								// Update text field
								$text = new \FelixOnline\Core\Text($id);
							} else {
								// We need a new ID

								$text = new \FelixOnline\Core\Text();
								$id = $text->setConverted(1)->save();

								$model->fields[$fieldName]->setValue($text);
							}

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

							$text->setContent(json_encode($json));
							$text->save();

							continue;
						}

						if($_POST[$fieldName] == '') {
							$setter = 'set'.$this->to_camel_case($fieldName);

							$model->$setter(NULL);
							continue;
						}
						$fk = new $fkType($_POST[$fieldName]);

						$setter = 'set'.$this->to_camel_case($fieldName);

						$model->$setter($fk);

						// If this is an image we may need to update the image.
						if($fkType == "FelixOnline\Core\Image" && array_key_exists($fieldName.'-descr', $_POST)) {
							// Should be sufficient that just ONE of the fields exists
							$fk->setDescription($_POST[$fieldName.'-descr']);
							$fk->setAttribution($_POST[$fieldName.'-attr']);
							$fk->setAttrLink($_POST[$fieldName.'-attrlink']);

							$fk->save();
						}
					} elseif($fieldType == 'FelixOnline\Core\Type\DateTimeField') {
						$setter = 'set'.$this->to_camel_case($fieldName);

						if($_POST[$fieldName] == '') {
							$model->$setter(NULL);
						} else {
							$model->$setter($_POST[$fieldName]);
						}
					} else {
						$setter = 'set'.$this->to_camel_case($fieldName);

						$model->$setter($_POST[$fieldName]);
					}
				}
			}

			$model->save();
		} catch(\Exception $e) {
			$this->error("Failed to save: ".$e->getMessage(), 500);
		}

		if(isset($page->getPageData()['modes']['details']['callback'])) {
			try {
				$action = 'FelixOnline\Admin\Actions\\'.$page->getPageData()['modes']['details']['callback'];

				$action = new $action($page);

				$message = $action->run(array($model->getId()));
				$this->success($message);
			} catch(\Exception $e) {
				$this->error("Your entry has been saved, however a problem occured in the callback function. Details: ".$e->getMessage(), 500);
			}
		}

		$this->success("Saved");
	}
}