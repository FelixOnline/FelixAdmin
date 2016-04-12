<?php

namespace FelixOnline\Admin\Pages;

class textHelper {
	private $page;
	private $pageData;

	public function __construct($pageName, $pageData, $manager, $pk, $pageInfo) {
		$this->pageName = $pageName;
		$this->pageData = $pageData;
	}

	public function render($hideTabs = false, $showTitle = false) {
		$app = \FelixOnline\Core\App::getInstance();

		$tabs = \FelixOnline\Admin\UXHelper::text($this->pageData['auxHtml']);
		$pageName = $this->pageData['name'];

		if($hideTabs) {
			$tabs = '';
			$pageName = '';
		}

		if($showTitle) {
			$showTitle = '<h2>'.$pageName.'</h2>';
		}

		return \FelixOnline\Admin\UXHelper::page(
			$pageName,
			array(
				'<div id="page-'.str_replace('/', '-', $this->pageName).'">',
				$showTitle,
				$tabs,
				'</div>',
				\FelixOnline\Admin\UXHelper::renderMenu($app['env']['session']->session['menu'], $this->pageName, '', false)
			),
			$this->pageName);
	}
}