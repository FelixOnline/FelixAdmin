<?php

namespace FelixOnline\Admin;

class HomePage {
	private $userName;
	private $userRoles;

	public function __construct() {
		global $currentuser;

		$this->userName = $currentuser->getName();
		$this->userRoles = $currentuser->getRoles();
	}

	public function render() {
		\FelixOnline\Admin\UXHelper::page(
			'Welcome to '.SERVICE_NAME,
			array(
				\FelixOnline\Admin\UXHelper::home(
					$this->userName,
					$this->userRoles)
			),
			'home');
	}
}