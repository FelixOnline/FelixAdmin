<?php

namespace FelixOnline\Admin\Ajax;

class logoutAjaxHelper extends Core {
	public function run() {
		// Register the logout
		global $currentuser;

		$currentuser->resetSession();
		$currentuser->removeCookie();

		$this->success(array("url" => STANDARD_URL));
	}
}