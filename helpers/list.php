<?php

namespace FelixOnline\Admin\Pages;

class listHelper {
	private $page;
	private $manager;
	private $pageData;
	private $pk;

	private $records;
	private $numRecords;
	private $currentPage;

	public function __construct($pageName, $pageData, $manager, $pk, $pageInfo) {
		$this->pageName= $pageName;
		$this->manager = $manager;
		$this->pageData = $pageData;
		$this->pageInfo = $pageInfo;
		$this->pk = $pk;

		$pageInfo = explode("/", $this->pageInfo);
		$currentPage = $pageInfo[1];

		if($currentPage == '' || $currentPage < 1 || preg_match('/[^0-9]/', $currentPage)) {
			$currentPage = 1;
		}

		$this->currentPage = $currentPage;
	}

	public function render($hideTabs = false, $showTitle = false) {
		$access = \FelixOnline\Admin\UXHelper::getAccess($this->pageData);

		$tabs = \FelixOnline\Admin\UXHelper::tabs(
					$this->pageName,
					$access,
					'list')
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

		$resultsArray = \FelixOnline\Admin\Page::loadResults($this->manager, $this->pageData, $this->currentPage);
		$access = \FelixOnline\Admin\UXHelper::getAccess($this->pageData);

		return \FelixOnline\Admin\UXHelper::page(
			$pageName,
			array(
				'<div id="page-'.str_replace('/', '-', $this->pageName).'">',
				$showTitle,
				$tabs,
				\FelixOnline\Admin\UXHelper::listStub(
					$this->pageName,
					$this->pageData,
					$resultsArray['records'],
					$resultsArray['actions'],
					$resultsArray['numRecords'],
					$this->pk,
					$this->currentPage,
					$access['details'],
					$this->pageData['modes']['list']['canDelete'],
					false),
				'</div>'
			),
			$this->pageName);
	}
}