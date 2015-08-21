<?php

namespace FelixOnline\Admin\Ajax;

class loginAjaxHelper extends Core {
	public function run() {
		if(!array_key_exists('username', $_POST) || $_POST['username'] == '') {
			$this->error("Username not specified.", 400);
		}

		if(!array_key_exists('password', $_POST) || $_POST['password'] == '') {
			$this->error("Password not specified.", 400);
		}

		if(!\pam_auth($_POST['username'], $_POST['password'])) {
			if(!SSH_LOGIN) {
				$this->error("Your login details were not accepted.", 403);
			} else {
				$resource = \ssh2_connect(SSH_HOST);

				if(!\ssh2_auth_password($resource, $_POST['username'], $_POST['password'])) {
					$this->error("Your login details were not accepted.", 403);
				}
			}
		}

		// Login accepted now see if the user exists
		$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\User', 'user', 'user');
		$manager->filter('user = "%s"', array(\strtolower($_POST['username'])));

		$count = $manager->count();

		if($count == 0) {
			$this->error("You do not have an account. Please login on the main Felix Online website to create one.", 403);
		}

		$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\UserRole', 'user_roles', 'id');
		$manager->filter('user = "%s"', array(\strtolower($_POST['username'])));

		$count = $manager->count();

		if($count == 0) {
			$this->error("You do not have any roles. Please ask a member of the Felix team to set you up on the administration website if you believe this is incorrect.", 403);
		}

		// Check if the menu exists and is ready
		if(!file_exists(BASE_DIRECTORY.'/menu.json')) {
			$this->error("The menu file is missing.", 500);
		}

		$menu = json_decode(file_get_contents(BASE_DIRECTORY.'/menu.json'), true);

		if($menu == null) {
			$this->error("The menu file is invalid.", 500);
		}

		// Register the login
		global $currentuser;

		$currentuser->setUser($_POST['username']);
		$currentuser->createSession();

		$app = \FelixOnline\Core\App::getInstance();

		$roles = array();

		foreach($currentuser->getRoles() as $role) {
			$roles[] = $role->getName();
		}

		$app['env']['session']->session['roles'] = $roles;

		// Test menu
		$finalMenu = array();

		foreach($menu as $key => $node) {
			$finalMenu = array_merge($finalMenu, $this->checkMenuAccess($key, $node));
		}

		if(count($finalMenu) == 0) {
			$currentuser->resetSession();
			$currentuser->removeCookie();
			$this->error("You do not have permission to access any pages in this service.");
		}

		$app['env']['session']->session['menu'] = $finalMenu;

		$this->success('Logged in');
	}

	private function checkMenuAccess($key, $node, $parent = null) {
		$page = new \FelixOnline\Admin\Page($key, true); // Do not run constructor - we do this to avoid constraining data and wasting lots of time

		if($page->lightLoad($key)) {

			// We have access to this page

			$return = array();
			$return[$key] = array("label" => $page->getName(), "parent" => $parent);

			if(isset($node['children'])) {
				foreach($node['children'] as $chKey => $chNode) {
					if($children = $this->checkMenuAccess($chKey, $chNode, $key)) {
						$return = array_merge($return, $children);
					}
				}
			}

			return $return;
		} else {
			return array();
		}
	}
}