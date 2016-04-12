<?php

namespace FelixOnline\Admin;

class StubPage {
	private $pageInfo;

	public function __construct($pageInfo) {
		$app = \FelixOnline\Core\App::getInstance();
		$this->pageInfo = $pageInfo;
	}

	public function render() {
		$page = explode(':', $this->pageInfo);
		echo UXHelper::header($page[0]);
		echo '<div id="render-root"></div>';
		echo '<script>
			document.addEventListener("DOMContentLoaded", function(event) {
				loadPage("'.$this->pageInfo.'", "#render-root", false, null, true);
			});
			</script>';
		echo UXHelper::footer();
	}
}