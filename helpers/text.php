<?php

namespace FelixOnline\Admin\Pages;

class textHelper {
	private $page;
	private $pageData;

	public function __construct($pageName, $pageData, $manager, $pk, $pageInfo) {
		$this->pageName= $pageName;
		$this->pageData = $pageData;
	}

	public function render() {
		\FelixOnline\Admin\UXHelper::page(
			$this->pageData['name'],
			array(
				\FelixOnline\Admin\UXHelper::text(
					$this->pageData['auxHtml']),
			),
			$this->pageName);
	}
}