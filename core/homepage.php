<?php

namespace FelixOnline\Admin;

class HomePage {
	private $userName;
	private $userRoles;

	public function __construct() {
		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

		$this->userName = $currentuser->getName();
		$this->userRoles = $currentuser->getRoles();
	}

	public function render() {
		return \FelixOnline\Admin\UXHelper::page(
			'Welcome to '.SERVICE_NAME,
			array(
				'<h2>'.'Welcome to '.SERVICE_NAME.'</h2>',
				\FelixOnline\Admin\UXHelper::home(
					$this->userName,
					$this->userRoles)
			),
			'home');
	}
}