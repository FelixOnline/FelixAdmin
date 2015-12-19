<?php

namespace FelixOnline\Admin\Pages;

class newHelper {
	private $page;
	private $manager;
	private $pageData;
	private $pk;
	private $widgets;

	public function __construct($pageName, $pageData, $manager, $pk, $pageInfo) {
		$this->pageName= $pageName;
		$this->manager = $manager;
		$this->pageData = $pageData;
		$this->pageInfo = $pageInfo;
		$this->pk = $pk;

		$nullRecord = new $this->pageData['model']();

		// We need to get all the widgets which apply
		$this->widgets = array();

		foreach($this->pageData['fields'] as $field => $data) {
			if($field == $this->pk && $this->pk == 'id') {
				continue;
			}

			if($this->pageData['fields'][$field]['readOnly'] == true) {
				continue;
			}

			$modelField = $nullRecord->fields[$field];

			$otherData = array();
			$currentValue = '';

			if(!array_key_exists('multiMap', $data) && !array_key_exists('choiceMap', $data) && !array_key_exists('uploader', $data)) {
				switch(get_class($modelField)) {
					case "FelixOnline\Core\Type\TextField":
						$widgetClass = 'FelixOnline\Admin\Widgets\TextWidget';
						break;
					case "FelixOnline\Core\Type\IntegerField":
						$widgetClass = 'FelixOnline\Admin\Widgets\NumericWidget';
						break;
					case "FelixOnline\Core\Type\DateTimeField":
						$widgetClass = 'FelixOnline\Admin\Widgets\DateTimeWidget';
						break;
					case "FelixOnline\Core\Type\CharField":
						$widgetClass = 'FelixOnline\Admin\Widgets\StringWidget';
						break;
					case "FelixOnline\Core\Type\BooleanField":
						$widgetClass = 'FelixOnline\Admin\Widgets\CheckboxWidget';
						break;
					case "FelixOnline\Core\Type\ForeignKey":
						if($modelField->class == "FelixOnline\Core\Image") {
							$widgetClass = 'FelixOnline\Admin\Widgets\ForeignKeyImageWidget';
							$otherData = array('page' => $this->pageName,
								'hasEditor' => $data['imageDetailsEditor'],
								'canUpload' => $data['canUpload'],
								'canPick' => $data['canPick'],
								'defaultImage' => $data['defaultImage']);
						} elseif($modelField->class == "FelixOnline\Core\Text") {
							$widgetClass = 'FelixOnline\Admin\Widgets\ForeignKeyTextWidget';
							$otherData = array('page' => $this->pageName);
						} else {
							$widgetClass = 'FelixOnline\Admin\Widgets\ForeignKeyWidget';
							$otherData = array('field' => $data['foreignKeyField'], 'page' => $this->pageName, 'class' => $modelField->class);

							$class = $modelField->class;
							$currentValue = new $class();
						}

						break;
					default:
						$widgetClass = 'FelixOnline\Admin\Widgets\UnimplementedWidget';
						break;
				}
			} elseif(array_key_exists('multiMap', $data)) {
				$widgetClass = 'FelixOnline\Admin\Widgets\ForeignKeyMultiMapWidget';

				try {
					$table = (new $data['multiMap']['model'])->dbtable;

					$currentValue = array();

					$otherData = array('key' => $data['multiMap']['foreignKey'], 'field' => $data['multiMap']['foreignKeyField'], 'page' => $this->pageName);
				} catch(\Exception $e) {
					$widgets[] = new \FelixOnline\Admin\Widgets\ErrorWidget(
						$field,
						$data['label'],
						'Could not load this field as the associated foreign key map table could not be found or it is incorrectly configured.',
						$data['readOnly'],
						$data['required'],
						$data['help'],
						$otherData);
					continue;
				}
			} elseif(array_key_exists('choiceMap', $data)) {
				$widgetClass = 'FelixOnline\Admin\Widgets\ForeignKeyChoiceMapWidget';

				try {
					$table = (new $data['choiceMap']['model'])->dbtable;

					$currentValue = array();

					$otherData = array('field' => $data['choiceMap']['field'], 'page' => $this->pageName);
				} catch(\Exception $e) {
					$widgets[] = new \FelixOnline\Admin\Widgets\ErrorWidget(
						$field,
						$data['label'],
						'Could not load this field as the associated foreign key map table could not be found or it is incorrectly configured.',
						$data['readOnly'],
						$data['required'],
						$data['help'],
						$otherData);
					continue;
				}
			} elseif(array_key_exists('uploader', $data)) {
				$widgetClass = 'FelixOnline\Admin\Widgets\Uploader';
			}

			if(isset($data['defaultValue'])) {
				switch($widgetClass) {
					case 'FelixOnline\Admin\Widgets\ForeignKeyWidget':
						$defaultValue = new $class($data['defaultValue']);
						break;
					case 'FelixOnline\Admin\Widgets\ForeignKeyChoiceMapWidget':
					case 'FelixOnline\Admin\Widgets\ForeignKeyMultiMapWidget':
					case 'FelixOnline\Admin\Widgets\ForeignKeyTextWidget':
					case 'FelixOnline\Admin\Widgets\ForeignKeyImageWidget':
					case 'FelixOnline\Admin\Widgets\Uploader':
					case 'FelixOnline\Admin\Widgets\TextWidget':
						// These do not support defaults
						break;
					default:
						$defaultValue = $data['defaultValue'];
						break;
				}

				$otherData['defaultValue'] = $defaultValue;
			}

			$this->widgets[] = new $widgetClass(
				$field,
				$data['label'],
				$currentValue,
				$data['readOnly'],
				$data['required'],
				$data['help'],
				$otherData);
		}
	}

	public function render() {
		$access = \FelixOnline\Admin\UXHelper::getAccess($this->pageData);

		\FelixOnline\Admin\UXHelper::page(
			$this->pageData['name'],
			array(
				\FelixOnline\Admin\UXHelper::tabs(
					$this->pageName,
					$access,
					'new'),
				\FelixOnline\Admin\UXHelper::text(
					$this->pageData['auxHtml']),
				\FelixOnline\Admin\UXHelper::creator( //functions cannot be called new
					$this->pageName,
					$this->pageData,
					$this->widgets,
					$this->pk)
			),
			$this->pageName);
	}
}