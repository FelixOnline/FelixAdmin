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

	public function render() {
		$access = \FelixOnline\Admin\UXHelper::getAccess($this->pageData);

		\FelixOnline\Admin\UXHelper::page(
			$this->pageData['name'],
			array(
				\FelixOnline\Admin\UXHelper::tabs(
					$this->pageName,
					$access,
					'list'),
				\FelixOnline\Admin\UXHelper::text(
					$this->pageData['auxHtml']),
				\FelixOnline\Admin\UXHelper::listStub(
					$this->pageName,
					$this->currentPage)
			),
			$this->pageName);
	}
}