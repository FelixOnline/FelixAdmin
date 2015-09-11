<?php

namespace FelixOnline\Admin;

class Page {
	private $page = null;
	private $pageInfo = null;
	private $userRoles = array();

	private $pageData = null; // JSON describing page
	private $manager = null; // What is the manager used to iterate over data

	private $pk = null; // What is the PK used in tables and forms and managers

	private $pageHelper;
	private $defaultPageType;

	public function __construct($page, $skip = false) {
		if($skip) { return; }

		$page = explode(':', $page);

		$app = \FelixOnline\Core\App::getInstance();

		$this->page = $page[0];
		$this->pageInfo = $page[1];
		$this->userRoles = $app['env']['session']->session['roles'];
		$this->userExplicitRoles = $app['env']['session']->session['explicitRoles'];

		$this->loadPage();

		$this->checkPermissions();

		$this->applyConstraints();
	}

	// For checking access
	public function lightLoad($page) {
		try {
			$page = explode(':', $page);

			$app = \FelixOnline\Core\App::getInstance();

			$this->page = $page[0];
			$this->pageInfo = $page[1];
			$this->userRoles = $app['env']['session']->session['roles'];
			$this->userExplicitRoles = $app['env']['session']->session['explicitRoles'];

			$this->loadPage();

			$this->checkPermissions();
		} catch(\Exception $e) {
			return false;
		}

		return true;
	}

	// Check if we have permission to do a specific thing
	public function canDo($action) {
		if(isset($this->pageData['modes']) && array_key_exists($action, $this->pageData['modes']) &&
			$this->pageData['modes'][$action]['enabled'] != TRUE) {
				return false;
		} elseif($action == 'text') {
			return true;
		} else {
			if(array_key_exists('roles', $this->pageData['modes'][$action]) && count($this->pageData['modes'][$action]['roles']) > 0) {
				if(count(array_intersect($this->pageData['modes'][$action]['roles'], $this->userRoles)) > 0) {
					return true;
				}
			} else {
				return true;
			}
		}
	}

	// Load the page into memory
	private function loadPage() {
		if(preg_match('/[^a-zA-Z0-9_]/', $this->page)) {
			throw new Exceptions\PageNotFoundException($this->page);
		}

		// check and load file
		if(!file_exists(BASE_DIRECTORY.'/files/'.$this->page.'.json')) {
			throw new Exceptions\PageNotFoundException($this->page);
		}

		$this->pageData = json_decode(file_get_contents(BASE_DIRECTORY.'/files/'.$this->page.'.json'), true);

		if($this->pageData == null) {
			throw new \FelixOnline\Exceptions\InternalException('The JSON for this page is invalid.');
		}

		if(!isset($this->pageData['defaultTab'])) {
			$this->defaultPageType = 'list';
		} else {
			$this->defaultPageType = $this->pageData['defaultTab'];
		}
	}

	private function checkPermissions() {
		// Assess permissions to access page
		$access = false;

		if(!array_key_exists('baseRole', $this->pageData) || count($this->pageData['baseRole']) == 0) {
			return;
		}

		if(count(array_intersect($this->pageData['baseRole'], $this->userRoles)) > 0) {
			$access = true;
		}

		if(!$access) {
			throw new Exceptions\ForbiddenPageException($this->page);
		}
	}

	// Set up the manager and apply page constraints
	private function applyConstraints() {
		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

		if($this->pageData['model'] == '') {
			return; // null pages
		}

		// Load the Manager
		$table = (new $this->pageData['model']())->dbtable;
		$this->pk = (new $this->pageData['model']())->pk;

		$this->manager = \FelixOnline\Core\BaseManager::build($this->pageData['model'], $table, $this->pk);

		$constraints = array();

		$toTest = $this->pageData['constraints'];

		if(count($toTest) != 0) {
			foreach($toTest as $constraint) {
				if(count($constraint['roles']) > 0) {
					if(count(array_intersect($constraint['roles'], $this->userExplicitRoles)) == 0) {
						continue; // Does not apply
					}
				}

				if(array_key_exists('specialConstraint', $constraint)) {
					switch($constraint['specialConstraint']) {
						case 'isAuthor':
							if($this->pageData['model'] == 'FelixOnline\Core\Article') {
								if($constraint['reverse']) {
									$op = '!=';
								} else {
									$op = '=';
								}

								$manager2 = \FelixOnline\Core\BaseManager::build('\FelixOnline\Core\ArticleAuthor', 'article_author', 'article');
								$manager2->filter('author '.$op.' "%s"', array($currentuser->getUser()));

								$this->manager->join($manager2);
							} elseif($this->pageData['model'] == 'FelixOnline\Core\Poll') {
								$this->manager->filter('author = "'.$currentuser->getUser().'"');
							}

							continue;
						case 'isEditor':
							if($this->pageData['model'] == 'FelixOnline\Core\Category') {
								if($constraint['reverse']) {
									$op = '!=';
								} else {
									$op = '=';
								}

								$manager2 = \FelixOnline\Core\BaseManager::build('\FelixOnline\Core\CategoryAuthor', 'category_author', 'category');
								$manager2->filter('user '.$op.' "%s"', array($currentuser->getUser()));

								$this->manager->join($manager2);
							} elseif($this->pageData['model'] == 'FelixOnline\Core\Article') {
								if($constraint['reverse']) {
									$op = '!=';
								} else {
									$op = '=';
								}

								$manager2 = \FelixOnline\Core\BaseManager::build('\FelixOnline\Core\Category', 'category');

								$manager3 = \FelixOnline\Core\BaseManager::build('\FelixOnline\Core\CategoryAuthor', 'category_author', 'id');
								$manager3->filter('user '.$op.' "%s"', array($currentuser->getUser()));

								$manager2->join($manager3, null, null, "category");
								$this->manager->join($manager2, null, "category");
							}
							continue;
						default:
							throw new \FelixOnline\Core\Exceptions\InternalException("This special constraint is not understood");
					}
					continue; // dealt with later
				}

				if(is_float($constraint['test'])) {
					$op = '%f';
				} elseif(is_int($constraint['test'])) {
					$op = '%i';
				} else {
					$op = '"%s"';
				}

				if(!is_array($constraint['test'])) {
					$test = array($constraint['test']);
				} else {
					$test = $constraint['test'];
				}
				$constraints[] = array('field' => $constraint['field'],
					'operator' => $constraint['operator'],
					'op' => $op,
					'test' => $test);
			}
		}

		foreach($constraints as $constraint) {
			if($constraint['operator'] == 'IS NULL' || $constraint['operator'] == 'IS NOT NULL') {
				$this->manager->filter($constraint['field'].' '.$constraint['operator']);
			} else {
				$this->manager->filter($constraint['field'].' '.$constraint['operator'].' '.$constraint['op'], $constraint['test']);
			}
		}

		// Sort the records
		$orderBy = array();
		foreach($this->pageData['order'] as $order) {
			$orderBy[] = array($order['column'], $order['direction']);
		}

		$this->manager->multiOrder($orderBy);
	}

	public function getPageData() {
		return $this->pageData;
	}

	public function getPk() {
		return $this->pk;
	}

	public function getName() {
		return $this->pageData['name'];
	}

	public function getManager() {
		return $this->manager;
	}

	public function isRecordAccessible($key) {
		$tempManager = $this->manager;
		$tempManager->filter($this->pk.' = "%s"', array($key));

		$count = $tempManager->count();
		if($count == 0) {
			throw new Exceptions\ForbiddenRecordException($key);
		}
	}

	public function render() {
		$this->setupPageHelper();

		// Render the form
		$this->pageHelper->render();
	}

	public function getSpecificPageHelper($info) {
		try {
			$oldHelper = $this->pageHelper;

			$this->setupPageHelper($info);
		} catch(Exceptions\ForbiddenPageException $e) {
			$this->pageHelper = $oldHelper;

			return false;
		}

		$newHelper = $this->pageHelper;
		$this->pageHelper = $oldHelper;

		return $newHelper;
	}

	private function setupPageHelper($info = false) {
		if($info) {
			$finalInfo = $info;
		} else {
			$finalInfo = $this->pageInfo;
		}

		if($this->manager == null) {
			$pageType = 'text';
		} else {
			// munge page info
			if($finalInfo == '') {
				$pageType = $this->defaultPageType;
			} elseif($finalInfo == 'list') {
				$pageType = 'list';
			} elseif ($finalInfo == 'new') {
				$pageType = 'new';
			} elseif ($finalInfo == 'search') {
				$pageType = 'search';
			} else {
				$pageInfo = explode('/', $finalInfo);

				if($pageInfo[0] == 'details') {
					$pageType = 'details';
				} else {
					$pageType = $this->defaultPageType;
				}
			}
		}

		// Can we access this type of form?
		$access = $this->canDo($pageType);

		if($access == false) {
			throw new Exceptions\ForbiddenPageException($this->page, $pageType);
		}

		$this->pageHelper = '\FelixOnline\Admin\Pages\\'.$pageType.'Helper';

		if(!class_exists($this->pageHelper)) {
			throw new Exceptions\HelperNotFoundException($this->pageHelper);
		}

		$this->pageHelper = new $this->pageHelper($this->page, $this->pageData, $this->manager, $this->pk, $finalInfo);
	}
}