<?php

namespace FelixOnline\Admin\Pages;
use FelixOnline\Admin\Exceptions;

class detailsHelper {
	private $page;
	private $manager;
	private $pageData;
	private $pk;
	private $widgets;
	private $record;
	private $currentPk;
	private $readOnly;

	public function __construct($pageName, $pageData, $manager, $pk, $pageInfo) {
		$this->pageName= $pageName;
		$this->manager = $manager;
		$this->pageData = $pageData;
		$this->pageInfo = $pageInfo;
		$this->pk = $pk;

		$this->pageInfo = explode('/', $this->pageInfo);

		$this->currentPk = $this->pageInfo[1];

		if($this->pageInfo[1] == '') {
			throw new \Exception('No record specified');
		} else {
			$this->manager->filter($this->pk." = \"%s\"", array($this->pageInfo[1]));
			$this->manager->limit(0, 1);
		}

		$mainRecord = $this->manager->values();

		if(count($mainRecord) == 0) {
			throw new Exceptions\RecordNotFoundException($this->pageInfo[1]);
		}

		$this->record = $mainRecord;

		// We need to get all the widgets which apply
		$this->widgets = array();

		foreach($this->pageData['fields'] as $field => $data) {
			if($field == $this->pk) {
				continue;
			}

			$modelField = current($this->record)->fields[$field];

			if(!array_key_exists('multiMap', $data) && !array_key_exists('choiceMap', $data) && !array_key_exists('uploader', $data)) {
				try {
					$modelFieldValue = $modelField->getValue();
				} catch(\Exception $e) {
					$modelFieldValue = null;
				}

				$otherData = array();
				switch(get_class($modelField)) {
					case "FelixOnline\Core\Type\TextField":
						$widgetClass = 'FelixOnline\Admin\Widgets\TextWidget';
						$otherData = array('sirTrevor' => $data['sirTrevor'],
							'sirTrevorFields' => $data['sirTrevorFields'],
							'sirTrevorMaxFields' => $data['sirTrevorMaxFields'],
							'sirTrevorDefaultField' => $data['sirTrevorDefaultField']);
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
						}
						break;
					default:
						$widgetClass = 'FelixOnline\Admin\Widgets\UnimplementedWidget';
						break;
				}
			} elseif(array_key_exists('choiceMap', $data)) {
				$widgetClass = 'FelixOnline\Admin\Widgets\ForeignKeyChoiceMapWidget';

				try {
					$table = (new $data['choiceMap']['model'])->dbtable;

					$modelFieldValue = \FelixOnline\Core\BaseManager::build($data['choiceMap']['model'], $table)
						->filter($data['choiceMap']['this'].' = "%s"', array($this->pageInfo[1]))
						->values();

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
			} elseif(array_key_exists('multiMap', $data)) {
				$widgetClass = 'FelixOnline\Admin\Widgets\ForeignKeyMultiMapWidget';

				try {
					$table = (new $data['multiMap']['model'])->dbtable;

					$modelFieldValue = \FelixOnline\Core\BaseManager::build($data['multiMap']['model'], $table)
						->filter($data['multiMap']['this'].' = "%s"', array($this->pageInfo[1]))
						->values();

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
			} else {
				continue;
			}

			if(array_key_exists('readOnly', $this->pageData['modes']['details']) && $this->pageData['modes']['details']['readOnly'] == true) {
				$this->readOnly = true;
			} else {
				$this->readOnly = false;
			}

			$readOnly = $data['readOnly'];
			if($this->readOnly) {
				$readOnly = true;
			}

			$this->widgets[] = new $widgetClass(
				$field,
				$data['label'],
				$modelFieldValue,
				$readOnly,
				$data['required'],
				$data['help'],
				$otherData);
		}
	}

	public function render($hideTabs = false, $showTitle = false) {
		// Show audit log for "sysAdmin", "webMaster" roles only
		$showLog = false;
		$app = \FelixOnline\Core\App::getInstance();

		if(array_intersect(array('sysAdmin', 'webMaster'), $app['env']['session']->session['roles']) != 0) {
			$showLog = true;
		}

		$access = \FelixOnline\Admin\UXHelper::getAccess($this->pageData);

		$tabs = \FelixOnline\Admin\UXHelper::tabs(
					$this->pageName,
					$access,
					'details',
					$this->currentPk)
				.\FelixOnline\Admin\UXHelper::text(
					$this->pageData['auxHtml']);

		$pageName = $this->pageData['name'];

		if($hideTabs) {
			$tabs = '';
			$pageName = '';
		}

		if($showTitle) {
			$showTitle = '<h2>'.$pageName.'</h2>';
		}

		$formData = \FelixOnline\Admin\UXHelper::details(
					$this->pageName,
					$this->pageData,
					$this->record,
					$this->widgets,
					$this->pk,
					$this->readOnly,
					$showLog);

		return \FelixOnline\Admin\UXHelper::page(
			$pageName,
			array(
				'<div id="page-'.str_replace('/', '-', $this->pageName).'">',
				$showTitle,
				$tabs,
				\FelixOnline\Admin\UXHelper::text(
					'<h3>'.$formData['heading'].'</h3>'),
				\FelixOnline\Admin\UXHelper::text(
					$formData['string']),
				'</div>',
				\FelixOnline\Admin\UXHelper::renderMenu($app['env']['session']->session['menu'], $this->pageName, '', false, $this->pageInfo[1])
			),
			$this->pageName);
	}
}