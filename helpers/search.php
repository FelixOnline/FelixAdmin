<?php

namespace FelixOnline\Admin\Pages;

class searchHelper {
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
			if(isset($this->pageData['modes']['search']['fields']) && array_search($field, $this->pageData['modes']['search']['fields']) === FALSE) {
				continue;
			}

			if(array_key_exists('multiMap', $data)) {
				$this->widgets[] = new \FelixOnline\Admin\Widgets\ErrorWidget(
					$field,
					$data['label'],
					'MultiMap widgets are not currently supported in Search forms and will be ignored.',
					false,
					false,
					$data['help'],
					$otherData);
		 		continue;
			}

			$modelField = $nullRecord->fields[$field];

			$otherData = array();
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
					$widgetClass = 'FelixOnline\Admin\Widgets\CheckboxAlternativeWidget';
					break;
				case "FelixOnline\Core\Type\ForeignKey":
					$widgetClass = 'FelixOnline\Admin\Widgets\ForeignKeyWidget';

					if($modelField->class == 'FelixOnline\Core\Text') {
						$widgetClass = 'FelixOnline\Admin\Widgets\StringWidget';
					} else {
						$otherData = array('field' => $data['foreignKeyField'], 'page' => $this->pageName, 'class' => $modelField->class);
					}
					break;
				default:
					$widgetClass = 'FelixOnline\Admin\Widgets\UnimplementedWidget';
					break;
			}

			$this->widgets[] = new $widgetClass(
				$field,
				$data['label'],
				'',
				false,
				false,
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
					'search'),
				\FelixOnline\Admin\UXHelper::text(
					$this->pageData['auxHtml']),
				\FelixOnline\Admin\UXHelper::search(
					$this->pageName,
					$this->pageData,
					$this->widgets,
					$this->pk)
			),
			$this->pageName);
	}
}