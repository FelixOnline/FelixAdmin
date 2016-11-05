<?php

namespace FelixOnline\Admin;

class UXHelper {
	public static function header(
		$endpoint = '') {
		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

		// Generate CSRF token
		if(!isset($_COOKIE['felixonline_csrf_admin'])) {
			$csrf = \FelixOnline\Core\Utility::generateCSRFToken('admin');
		} else {
			$csrf = $_COOKIE['felixonline_csrf_admin'];
		}

		$string = '<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>'.SERVICE_NAME.'</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<base href="'.STANDARD_URL.'">

	<link rel="stylesheet" href="'.STANDARD_URL.'css/bootstrap.min.css">
	<link rel="stylesheet" href="'.STANDARD_URL.'css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="'.STANDARD_URL.'css/styles.css?v=1.0">
	<link rel="stylesheet" href="'.STANDARD_URL.'css/bootstrap-datetimepicker.min.css?v=1.0">

	<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="'.STANDARD_URL.'css/select2-bootstrap.css?v=1.0">
	<link rel="stylesheet" href="'.STANDARD_URL.'css/sir-trevor.css?v=1.0">
	<link rel="stylesheet" href="'.STANDARD_URL.'css/sir-trevor-icons.css?v=1.0">

	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>
<div id="csrf-key" data-csrf="'.$csrf.'"></div>
<div id="full-wrap">
<div class="container-header">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-8 hidden-xs">
				<h1>'.SERVICE_NAME.'</h1>
			</div>';

		if($currentuser->isLoggedIn()) {
			$string .= '<div class="col-sm-4">
				<button onClick="logout(); return false;" class="btn btn-primary logout-button pull-right"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Log out</button>
			</div>';
		}

		$string .= '</div></div>
	</div>';

		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

		if($currentuser->isLoggedIn()) {
			$string .= self::renderMenu($app['env']['session']->session['menu'], '', $endpoint, true);
		}

		$string .= '<div class="container-fluid">';
		return $string;
	}

	public static function renderMenu($menu, $levelToShow, $selectedItem, $root, $recordId = "null") {
		$items = 0;

		$menuId = rand();

		if(!isset($menu)) {
			return;
		}

		foreach($menu as $key => $item) {
			if($item['parent'] != $levelToShow) {
				continue;
			}
			$items++;
		}

		if($items == 0) {
			return; // No items - do not show menu
		}

		if($root) {
			if('home' == $selectedItem) {
				$selected = ' class="active"';
			} else {
				$selected = '';
			}

			$menuId = 'root';

			$string .= '<nav class="navbar navbar-default">
			<div class="container-fluid">
			<ul class="nav navbar-nav" id="menu-'.$menuId.'">';
		} else {
			$string .= '<ul class="nav nav-tabs" id="menu-'.$menuId.'">';
		}

		foreach($menu as $item) {
			$key = $item['key'];

			if($item['parent'] != $levelToShow) {
				continue;
			}

			if($key == $selectedItem) {
				$selected = ' class="active"';
			} else {
				$selected = '';
			}

			$menuItemId = rand();

			if(!$recordId || $recordId == 'null') {
				$updateNav = 'true';
			} else {
				$updateNav = 'false';
			}

			if($root) {
				$showTitle = 'true';
			} else {
				$showTitle = 'false';
			}

			$string .= '<li'.$selected.' id="menu-'.$menuId.'-item-'.$menuItemId.'"><a href="'.STANDARD_URL.$key.'" onClick="if(window.pageIsLoading) { return false; } loadPage(\''.$key.'\', \'#render-'.$menuId.'\', '.$updateNav.', null, \''.$recordId.'\', '.$showTitle.'); $(\'#menu-'.$menuId.' li\').each(function() { $(this).removeClass(\'active\'); }); $(\'#menu-'.$menuId.'-item-'.$menuItemId.'\').addClass(\'active\'); return false;">'.$item['label'].'</a></li>';
		}

		if($root) {
			$string .= '</ul>
			</div>
			</nav>';
		} else {
			$string .= '</ul><div id="render-'.$menuId.'" data-parentrecord="'.$recordId.'"></div>';
		}

		return $string;
	}

	public static function footer() {
		$string = '</div>
		<div class="container-footer">
			<footer>
				<div class="container-fluid">
					<p>&copy; Felix Online - <a href="#" data-toggle="modal" data-target="#rap">Report a Problem</a> - <a href="https://github.com/FelixOnline/FelixAdmin">Github</a></p>
					<p>Icons are kindly provided by <a href="http://glyphicons.com/">Glyphicons</a> via Twitter Bootstrap.</p>
				</div>
			</footer>
		</div>';

		$app = \FelixOnline\Core\App::getInstance();
		$currentuser = $app['currentuser'];

		$string .= '<div class="modal fade" id="rap">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Report a Problem</h4>
				</div>
				<div class="modal-body">';

		/*if($currentuser->isLoggedIn()):
					$string .= '<p>When you report a problem, details about the page you are accessing and who you are will be sent to Felix Online developers in addition to details about your web browser.</p>
				<p>Please specify as much information as possible in the box below about <b>exactly</b> what you are trying to do, the steps you have taken, what happened, and what you expected to happen.</p>
				<p>You may not receive a reply to your problem report however all feedback will be used to improve this service.</p>
				<textarea class="form-control" name="rap-feedback" rows="15" id="rap-feedback" placeholder="Provide details about the problem you are experiencing here"></textarea>';
		else:
			$string .= '<p>You must be logged in to report a problem. If you are unable to log in, please email <a href="felix@imperial.ac.uk">felix@imperial.ac.uk</a>';
		endif;*/
				$string .= '<b>This functionality is currently disabled. Please report any problems to the Editor noting, in detail, the exact steps needed to cause the problem you are experiencing, any error messages you obtain, and what you expected to happen.</b>';

				$string .= '</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<!--<button onClick="rap(); return false;" class="btn btn-primary rap-button"><span class="glyphicon glyphicon-send" aria-hidden="true"></span> Submit</button>-->
				</div>
			</div>
		</div>
	</div>
	</div>
	<script src="'.STANDARD_URL.'js/bootstrap.min.js"></script>
	<script src="'.STANDARD_URL.'js/moments-2.10.3.js"></script>
	<script src="'.STANDARD_URL.'js/bootstrap-datetimepicker-4.14.30.min.js"></script>
	<script src="'.STANDARD_URL.'js/ux.js?v=1.0"></script>
	<script src="'.STANDARD_URL.'js/dropzone.js?v=1.0"></script>
	<script src="'.STANDARD_URL.'js/jquery-tablesorter.js?v=1.0"></script>
	<script src="'.STANDARD_URL.'js/lodash.min.js?v=1.0"></script>
	<script src="'.STANDARD_URL.'js/sir-trevor.js?v=1.0"></script>
	<script src="'.STANDARD_URL.'js/ol.sir-trevor.js?v=1.0"></script>
	<script src="'.STANDARD_URL.'js/quote.sir-trevor.js?v=1.0"></script>
	<script src="'.STANDARD_URL.'js/factoid.sir-trevor.js?v=1.0"></script>
	<script src="'.STANDARD_URL.'js/feliximage.sir-trevor.js?v=1.0"></script>
	<script src="'.STANDARD_URL.'js/bootstrap-notify.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.min.js"></script>

	<script>
		function getAjaxEndpoint() {
			return "ajax.php";
		}
		function getImageUploadEndpoint() {
			return "uploadImage.php";
		}
	</script>
	</body>

</html>';

	return $string;
	}

	public static function notfound() {
		return '<p>Sorry, what you\'ve asked for doesn\'t seem to exist.</p>';
	}

	public static function forbidden() {
		return '<p>Sorry, you can\'t access that.</p>';
	}

	public static function login() {
		$string = '<h2>Login</h2>';
		$string .= '<p>Welcome to '.SERVICE_NAME.'. Please log in with your IC credentials.</p>';
		$string .= '<p>You can only log in to this website if you have been granted access, for example because you have written an article or you edit a section.</p>';

		$required = ' <span class="text-danger" title="Required">*</span>';

		$string .= '<form class="form-horizontal">';

		$string .= '<div class="form-group" id="grp-username">
	<label for="username" class="col-sm-2 control-label">Username'.$required.'</label>
	<div class="col-sm-10">
		<input type="text" class="form-control" name="username" id="username">
	</div>
</div>';

		$string .= '<div class="form-group" id="grp-password">
	<label for="password" class="col-sm-2 control-label">Password'.$required.'</label>
	<div class="col-sm-10">
		<input type="password" class="form-control" name="password" id="password">
	</div>
</div>';

		$string .= '<div class="form-group">
<div class="col-sm-12">';
		$string .= '<button onClick="login()"); return false;" class="btn btn-primary login-button"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Log in</button>';
		$string .= '</div></div>';

		return $string;
	}

	public static function error(\Exception $e) {
		$string = '<p>Something went wrong. An error report has been automatically created.</p>';

		if(LOCAL) {
			$string .= '<p>'.$e->getMessage().' at '.$e->getFile().':'.$e->getLine().'</p>';
			$string .= '<pre>'.$e->getTraceAsString().'</pre>';
		}

		return $string;
	}

	public static function page(
		$name,
		$content,
		$endpoint) {

		foreach($content as $item) {
			$output .= $item;
		}

		return $output;
	}

	public static function tabs(
		$pageSlug,
		$access,
		$currentView,
		$currentKey = null) {

		$tabsId = rand();
		$string = '';
		$validTabs = 0;

		foreach($access as $key => $status) {
			if($status) {
				if(($key == 'details' && $currentView == 'details') || $key != 'details') {
					$validTabs++;
				}
			}
		}

		if($validTabs > 1) {
			$string .= '<ul class="nav nav-pills" id="modetabs-'.$tabsId.'">';

			if($access['list']) {
				if($currentView == 'list') {
					$current = 'class="active"';
				} else {
					$current = '';
				}

				$string .= '<li role="presentation" '.$current.' id="modetabs-'.$tabsId.'-list"><a href="'.STANDARD_URL.$pageSlug.':list" onClick="if(window.pageIsLoading) { return false; } loadPage(\''.$pageSlug.':list\', \'#\'+$(\'#modetabs-'.$tabsId.'\').parent().parent().attr(\'id\'), (\'#\'+$(\'#modetabs-'.$tabsId.'\').parent().parent().attr(\'data-parentrecord\') == \'null\'), false, $(\'#modetabs-'.$tabsId.'\').parent().parent().attr(\'data-parentrecord\'), ($(\'#modetabs-'.$tabsId.'\').parent().parent().attr(\'id\') == \'render-root\')); return false;" '.$current.'><span class="glyphicon glyphicon-list" aria-hidden="true"></span> List</a></li>';
			}

			if($access['search']) {
				if($currentView == 'search') {
					$current = 'class="active"';
				} else {
					$current = '';
				}

				$string .= '<li role="presentation" '.$current.' id="modetabs-'.$tabsId.'-search"><a href="'.STANDARD_URL.$pageSlug.':search" onClick="if(window.pageIsLoading) { return false; } loadPage(\''.$pageSlug.':search\', \'#\'+$(\'#modetabs-'.$tabsId.'\').parent().parent().attr(\'id\'), (\'#\'+$(\'#modetabs-'.$tabsId.'\').parent().parent().attr(\'data-parentrecord\') == \'null\'), false, $(\'#modetabs-'.$tabsId.'\').parent().parent().attr(\'data-parentrecord\'), ($(\'#modetabs-'.$tabsId.'\').parent().parent().attr(\'id\') == \'render-root\')); return false;"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search</a></li>';
			}

			if($access['details'] && $currentView == 'details') {
				if($currentView == 'details') {
					$current = 'class="active"';
				} else {
					$current = '';
				}

				$string .= '<li role="presentation" '.$current.' id="modetabs-'.$tabsId.'-details"><a href="'.STANDARD_URL.$pageSlug.':details/'.$currentKey.'" onClick="if(window.pageIsLoading) { return false; } loadPage(\''.$pageSlug.':details/'.$currentKey.'\', \'#\'+$(\'#modetabs-'.$tabsId.'\').parent().parent().attr(\'id\'), (\'#\'+$(\'#modetabs-'.$tabsId.'\').parent().parent().attr(\'data-parentrecord\') == \'null\'), false, $(\'#modetabs-'.$tabsId.'\').parent().parent().attr(\'data-parentrecord\'), ($(\'#modetabs-'.$tabsId.'\').parent().parent().attr(\'id\') == \'render-root\')); return false;" '.$current.'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</a></li>';
			}

			if($access['new']) {
				if($currentView == 'new') {
					$current = 'class="active"';
				} else {
					$current = '';
				}

				$string .= '<li role="presentation" '.$current.' id="modetabs-'.$tabsId.'-new"><a href="'.STANDARD_URL.$pageSlug.':new" onClick="if(window.pageIsLoading) { return false; } loadPage(\''.$pageSlug.':new\', \'#\'+$(\'#modetabs-'.$tabsId.'\').parent().parent().attr(\'id\'), (\'#\'+$(\'#modetabs-'.$tabsId.'\').parent().parent().attr(\'data-parentrecord\') == \'null\'), false, $(\'#modetabs-'.$tabsId.'\').parent().parent().attr(\'data-parentrecord\'), ($(\'#modetabs-'.$tabsId.'\').parent().parent().attr(\'id\') == \'render-root\')); return false;" '.$current.'><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> New</a></li>';
			}

			$string .= '</ul>';
		}

		return $string;
	}

	public static function text($html) {
		return $html;
	}

	public static function getAccess($pageData) {
		// What can we access

		$app = \FelixOnline\Core\App::getInstance();

		// NEW
		if(array_key_exists('new', $pageData['modes']) &&
			$pageData['modes']['new']['enabled'] != TRUE) {
				$newAccess = false;
		} else {
			if(array_key_exists('roles', $pageData['modes']['new']) && count($pageData['modes']['new']['roles']) > 0) {
				$newAccess = false;
				if(count(array_intersect($app['env']['session']->session['roles'], $pageData['modes']['new']['roles'])) > 0) {
					$newAccess = true;
				}
			} else {
				$newAccess = true;
			}
		}

		// DETAILS
		if(array_key_exists('details', $pageData['modes']) &&
			$pageData['modes']['details']['enabled'] != TRUE) {
				$detailsAccess = false;
		} else {
			if(array_key_exists('roles', $pageData['modes']['details']) && count($pageData['modes']['details']['roles']) > 0) {
				$detailsAccess = false;
				if(count(array_intersect($app['env']['session']->session['roles'], $pageData['modes']['details']['roles'])) > 0) {
					$detailsAccess = true;
				}
			} else {
				$detailsAccess = true;
			}
		}
		
		// LIST
		if(array_key_exists('list', $pageData['modes']) &&
			$pageData['modes']['list']['enabled'] != TRUE) {
				$listAccess = false;
		} else {
			if(array_key_exists('roles', $pageData['modes']['list']) && count($pageData['modes']['list']['roles']) > 0) {
				$listAccess = false;
				if(count(array_intersect($app['env']['session']->session['roles'], $pageData['modes']['list']['roles'])) > 0) {
					$listAccess = true;
				}
			} else {
				$listAccess = true;
			}
		}

		// SEARCH
		if(array_key_exists('search', $pageData['modes']) &&
			$pageData['modes']['search']['enabled'] != TRUE) {
				$searchAccess = false;
		} else {
			if(array_key_exists('roles', $pageData['modes']['search']) && count($pageData['modes']['search']['roles']) > 0) {
				$searchAccess = false;
				if(count(array_intersect($app['env']['session']->session['roles'], $pageData['modes']['search']['roles'])) > 0) {
					$searchAccess = true;
				}
			} else {
				$searchAccess = true;
			}
		}

		return array('new' => $newAccess, 'list' => $listAccess, 'search' => $searchAccess, 'details' => $detailsAccess);
	}

	public static function listStub(
		$pageSlug,
		$pageData,
		$records,
		$actions,
		$numRecords,
		$primaryKey,
		$currentPage,
		$canDetails,
		$canDelete,
		$surpressPaginator,
		$searchView = false) {

		$string = '
			<div class="alert alert-info load-msg" style="display: none;"><span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span> Refreshing list...</div>
			<div class="dataarea">';
		$string .= self::recordList($pageSlug,
			$pageData,
			$records,
			$actions,
			$numRecords,
			$primaryKey,
			$currentPage,
			$canDetails,
			$canDelete,
			$surpressPaginator,
			$searchView);
		$string .= '</div>';

		return $string;
	}

	public static function recordList(
		$pageSlug,
		$pageData,
		$records,
		$actions,
		$numRecords,
		$primaryKey,
		$currentPage,
		$canDetails,
		$canDelete,
		$surpressPaginator,
		$searchView = false) {

		if($searchView) {
			$string = '<div class="searchResults">';
		} else {
			$string = '';
		}

		if(count($records) == 0) {
			return $string.'<h3>Nothing found</h3></div>';
		}

		$string .= '<div class="alert alert-info action-msg" style="display: none;"><span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span> Working on it...</div>';

		// What are our headings
		$columns = array();

		foreach($pageData['modes']['list']['columns'] as $column) {
			if(!array_key_exists($column, $pageData['fields'])) {
				throw new \Exception('You have requested column '.$column.' in this list view but it does not exist');
			}

			$columns[$column] = $pageData['fields'][$column]['label'];
		}

		$string .= '<div class="page-well affix2">';
		if(!$surpressPaginator) {
			$string .= UXHelper::recordPaginator(
				$pageSlug,
				$numRecords,
				$currentPage,
				$searchView);
		}

		if(count($actions) > 0) {
			$string .= '<div class="action-area"><div class="btn-group" role="group">';

			$string .= '<button class="btn btn-default toggler" onClick="toggleSelect(\''.str_replace('/', '-', $pageSlug).'\');">Select all on page</button>';

			$string .= '<button class="btn btn-default" disabled onClick="return: false;">With selected items:</button>';

			foreach($actions as $key => $action) {
				$string .= '<button class="btn btn-primary" onClick="runAction(\''.$key.'\', \''.$pageSlug.'\'); return false;"><span class="glyphicon glyphicon-'.$action['icon'].'"></span>&nbsp;&nbsp;'.$action['label'].'</button>';
			}

			$string .= '</div></div>';
		} else {
			$string .= '<div class="float: left; clear: right;"><br></div>';
		}

		$string .= '</div>';

		$string .= '<div class="table-responsive datatable-area">';
		$string .= '<table class="table table-bordered table-hover sortable datatable" data-currentpage="'.$currentPage.'">';
		$string .= '<thead><tr>';

		if(count($actions) > 0) {
			$string .= '<th data-sorter="false"></th>'; // Checkbox for actions
		}

		foreach($columns as $column) {
			$string .= '<th>'.$column.'</th>';
		}

		if($canDelete) {
			$string .= '<th data-sorter="false"></th>';
		}

		$string .= '</tr></thead><tbody>';

		foreach($records as $record) {
			$string .= '<tr id="row-'.$currentpage.'-'.$record->fields[$primaryKey]->getValue().'">';

			if(count($actions) > 0) {
				$string .= '<td><input type="checkbox" class="recordBox" name="record[]" data-record="'.$record->fields[$primaryKey]->getValue().'"></td>'; // Checkbox for actions
			}

			foreach($columns as $colid => $label) {
				if(array_key_exists('multiMap', $pageData['fields'][$colid])) {
					try {
						$table = (new $pageData['fields'][$colid]['multiMap']['model'])->dbtable;

						$modelFieldValue = \FelixOnline\Core\BaseManager::build($pageData['fields'][$colid]['multiMap']['model'], $table)
							->filter($pageData['fields'][$colid]['multiMap']['this'].' = "%s"', array($record->getPk()->getValue()))
							->values();

						$value = '';

						if(is_array($modelFieldValue) && count($modelFieldValue) > 0) {
							foreach($modelFieldValue as $fieldVal) {
								if($value != '') {
									$value .= ', ';
								}
								$tValue = $fieldVal->fields[$pageData['fields'][$colid]['multiMap']['foreignKey']];

								if(!($tValue instanceof \FelixOnline\Core\Type\ForeignKey)) {
									$value .= $fieldVal->fields[$pageData['fields'][$colid]['multiMap']['foreignKey']]->getRawValue();
								} else {
									$value .= $fieldVal->fields[$pageData['fields'][$colid]['multiMap']['foreignKey']]->getValue()
													   ->fields[$pageData['fields'][$colid]['multiMap']['foreignKeyField']]->getValue();
								}
							}

							$value = "<td>$value</td>";
						} else {
							$value = '<td>&nbsp;</td>';
						}
					} catch(\Exception $e) {
						$value = '<td><span class="text-danger">Error loading multiple field data</span></td>';
					}

					$string .= $value;

					continue;
				}

				if(array_key_exists('choiceMap', $pageData['fields'][$colid])) {
					try {
						$table = (new $pageData['fields'][$colid]['choiceMap']['model'])->dbtable;

						$modelFieldValue = \FelixOnline\Core\BaseManager::build($pageData['fields'][$colid]['choiceMap']['model'], $table)
							->filter($pageData['fields'][$colid]['choiceMap']['this'].' = "%s"', array($record->getPk()->getValue()))
							->values();

						$value = '';

						if(is_array($modelFieldValue) && count($modelFieldValue) > 0) {
							foreach($modelFieldValue as $fieldVal) {
								if($value != '') {
									$value .= ', ';
								}
								$value .= $fieldVal->fields[$pageData['fields'][$colid]['choiceMap']['field']]->getValue();
							}

							$value = "<td>$value</td>";
						} else {
							$value = '<td>&nbsp;</td>';
						}
					} catch(\Exception $e) {
						$value = '<td><span class="text-danger">Error loading multiple field data</span></td>';
					}

					$string .= $value;

					continue;
				}

				if(array_key_exists('uploader', $pageData['fields'][$colid])) {
					$value = '<td><span class="text-danger">This field cannot be shown - it is for new record entry only</span></td>';

					$string .= $value;

					continue;
				}

				if(!array_key_exists($colid, $record->fields)) {
					throw new \Exception('You are trying to render a field '.$colid.' which is not in the model');
					continue;
				}

				// Type transformations
				$type = get_class($record->fields[$colid]);

				if($type == "FelixOnline\Core\Type\ForeignKey") {
					if($record->fields[$colid]->getValue() == null) {
						$string .= '<td></td>';
						continue; // No value set
					}

					try {
						if($record->fields[$colid]->class == 'FelixOnline\Core\Image') {
							$value = '<a href="'.$record->fields[$colid]->getValue()->getUrl().'">'.$record->fields[$colid]->getValue()->getUrl().'</a>';
						} elseif($record->fields[$colid]->class == 'FelixOnline\Core\Text') {
							$value = '<span class="text-danger">Text fields cannot be shown here</span>';
						} else {
							$value = $record->fields[$colid]->getValue(); // This gets the object itself

							if(is_null($value->fields[$pageData['fields'][$colid]['foreignKeyField']])) {
								throw new \Exception('No value found');
							}

							if(!array_key_exists('foreignKeyField', $pageData['fields'][$colid])) {
								$value = '<span class="text-danger">No foreign key field specified</span>';
							} else {
								$value = $value->fields[$pageData['fields'][$colid]['foreignKeyField']]->getValue(); // This gets the desired field from the object
							}
						}
					} catch(\Exception $e) {
						$value = '<span class="text-danger">Error finding data</span>';
					}
				} else {
					$value = $record->fields[$colid]->getValue();
				}

				switch($type) {
					case "FelixOnline\Core\Type\TextField":
						$value = mb_strimwidth($value, 0, 200, '...', 'utf-8');
						break;
					case "FelixOnline\Core\Type\DateTimeField":
						if($value == null) {
							$value = '';
						} else {
							$value = date('Y-m-d H:i:s', $value);
						}
						break;
					case "FelixOnline\Core\Type\BooleanField":
						if($value) {
							$value = '<span class="text-success">✔</span>';
						} else {
							$value = '<span class="text-danger">✘</span>';
						}
						break;
				}

				if($colid == $primaryKey && $canDetails) {
					$editable = 'pencil';
					$searchViewModifier = 'false';

					if($pageData['modes']['details']['readOnly']) {
						$editable = 'info-sign';
					}

					$value = '<center><a href="'.STANDARD_URL.$pageSlug.':details/'.$value.'" onClick="if(window.pageIsLoading) { return false; } loadPage(\''.$pageSlug.':details/'.$value.'\', \'#\'+$(\'#page-'.str_replace('/', '-', $pageSlug).'\').parent().attr(\'id\'), ($(\'#page-'.str_replace('/', '-', $pageSlug).'\').parent().attr(\'data-parentrecord\') == \'null\'), '.$searchViewModifier.', $(\'#page-'.str_replace('/', '-', $pageSlug).'\').parent().attr(\'data-parentrecord\'), ($(\'#page-'.str_replace('/', '-', $pageSlug).'\').parent().attr(\'id\') == \'render-root\')); return false;"><span class="glyphicon glyphicon-'.$editable.'" aria-hidden="true"></span> '.$value.'</a></center>';
				}

				$string .= '<td>'.$value.'</td>';
			}

			if($canDelete) {
				$string .= '<td><span class="glyphicon glyphicon-trash delete-icon text-danger del-'.$record->fields[$primaryKey]->getValue().'" onClick="del(\''.$pageSlug.'\', \''.$record->fields[$primaryKey]->getValue().'\');"></span></td>';
			}

			$string .= '</tr>';
		}
		$string .= '</tbody></table></div>';

		$plural = '';

		if($numRecords != 1) {
			$plural = 's';
		}

		$string .= '<p><span class="label label-default">'.$numRecords.' record'.$plural.' found</span></p></div>';

		return $string;
	}

	public static function recordPaginator(
		$pageSlug,
		$numRecords,
		$currentPage,
		$searchMode = false) {

		// Paginator
		$finalPage = ceil($numRecords / LISTVIEW_PAGINATION_LIMIT);
		$firstPage = ((($currentPage - 5) > 0) ? ($currentPage - 5) : 1);
		$lastPage = ((($currentPage + 5) <= $finalPage) ? ($currentPage + 5) : $finalPage);

		if($searchMode) {
			$pageType = 'search';
			$previousPageLink = '#';
			$nextPageLink = '#';
			$firstPageLink = '#';
			$lastPageLink = '#';
			$function = 'runSearch';
		} else {
			$pageType = 'list';
			$previousPageLink = STANDARD_URL.$pageSlug.':'.$pageType.'/'.($currentPage - 1);
			$nextPageLink = STANDARD_URL.$pageSlug.':'.$pageType.'/'.($currentPage + 1);
			$firstPageLink = STANDARD_URL.$pageSlug.':'.$pageType.'/1';
			$lastPageLink = STANDARD_URL.$pageSlug.':'.$pageType.'/'.$finalPage;
			$function = 'refreshList';
		}

		$string = '<nav><ul class="pagination">';

		if($currentPage != 1) {
			$string .= '<li><a href="'.$previousPageLink.'" onClick="'.$function.'(\''.$pageSlug.'\', \''.($currentPage - 1).'\'); return false;"><span class="glyphicon glyphicon-chevron-left" aria-label="Next"></span></a></li>';
		} else {
			$string .= '<li class="disabled"><a><span class="glyphicon glyphicon-chevron-left" aria-label="Next"></span></a></li>';
		}

		if(1 < $firstPage) {
			$string .= '<li><a href="'.$firstPageLink.'" onClick="'.$function.'(\''.$pageSlug.'\', \'1\'); return false;">1</a></li>';
			$string .= '<li class="disabled"><span>...</span></li>';
		}

		for($i = $firstPage; $i <= $lastPage; $i++) {
			if($i == $currentPage) {
				$current = 'class="active"';
			} else {
				$current = '';
			}

			if($searchMode) {
				$specificPageLink = '#';
			} else {
				$specificPageLink = STANDARD_URL.$pageSlug.':'.$pageType.'/'.$i;
			}

			$string .= '<li '.$current.'><a href="'.$specificPageLink.'" onClick="'.$function.'(\''.$pageSlug.'\', \''.$i.'\'); return false;">'.$i.'</a></li>';
		}

		if($lastPage < $finalPage) {
			$string .= '<li class="disabled"><span>...</span></li>';
			$string .= '<li><a href="'.$finalPageLink.'" onClick="'.$function.'(\''.$pageSlug.'\', \''.$finalPage.'\'); return false;">'.$finalPage.'</a></li>';
		}

		if($currentPage != $finalPage) {
			$string .= '<li><a href="'.$nextPageLink.'" onClick="'.$function.'(\''.$pageSlug.'\', \''.($currentPage + 1).'\'); return false;"><span class="glyphicon glyphicon-chevron-right" aria-label="Next"></span></a></li>';
		} else {
			$string .= '<li class="disabled"><a><span class="glyphicon glyphicon-chevron-right" aria-label="Next"></span></a></li>';
		}

		$string .= '<li class="disabled"><a>Page '.$currentPage.' of '.$finalPage.'</a></li>';

		$string .= '</ul></nav>';

		return $string;
	}

	public static function details(
		$pageSlug,
		$pageData,
		$currentRecord,
		$widgets,
		$pk,
		$readOnly = false,
		$showTrail = false) {

		$hint = $pageData['modes']['details']['headerHint'];

		$currentRecord = $currentRecord[0];

		$string = '<div id="page-render-'.str_replace('/', '-', $pageSlug).'"><form class="form-horizontal">';


		if(!$readOnly) {
			$mode = 'Editing';
		} else {
			$mode = 'Viewing';
		}

		if($showTrail) {
			$mode = '<button class="btn btn-default auditButton" onClick="toggleAudit(\''.$pageSlug.'\'); return false;"><span class="glyphicon glyphicon-time"></span> Audit log</button>'.$mode;
		}

		if(!$readOnly) {
			$mode = '<button onClick="save(\''.$pageSlug.'\', \''.$currentRecord->fields[$pk]->getValue().'\', \'#\'+$(\'#page-'.str_replace('/', '-', $pageSlug).'\').parent().attr(\'id\'), $(\'#page-'.str_replace('/', '-', $pageSlug).'\').parent().attr(\'data-parentrecord\'), ($(\'#page-'.str_replace('/', '-', $pageSlug).'\').parent().attr(\'id\') == \'render-root\')); return false;" class="btn btn-primary save-button"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Save</button>'.$mode;
		}

		$string .= '<div class="page-well affix1" data-spy="affix"><h3>'.$mode.': '.$currentRecord->fields[$hint]->getRawValue().' ('.$currentRecord->fields[$pk]->getRawValue().')</h3></div>';

		$string .= '<div class="widgetForm">';

		$string .= self::widgetForm($widgets);

		$string .= '</div></form>';

		if($showTrail) {
			$string .= '<div class="auditForm" style="display: none">'.self::auditForm($currentRecord, $pk).'</div>';
		}

		$string .= '</div>';

		return array('string' => $string);
	}

	public static function creator(
		$pageSlug,
		$pageData,
		$widgets,
		$pk) {

		$string = '<div class="page-well affix1" data-spy="affix"><button onClick="create(\''.$pageSlug.'\', \'#\'+$(\'#page-'.str_replace('/', '-', $pageSlug).'\').parent().attr(\'id\'), $(\'#page-'.str_replace('/', '-', $pageSlug).'\').parent().attr(\'data-parentrecord\'), ($(\'#page-'.str_replace('/', '-', $pageSlug).'\').parent().attr(\'id\') == \'render-root\')); return false;" class="btn btn-primary new-button"><span class="glyphicon glyphicon-saved" aria-hidden="true"></span> Create</button><h3>New Entry</h3></div>';
		$string .= '<form class="form-horizontal">';

		$string .= '<div class="form-group">
<div class="col-sm-12">';
		$string .= '</div></div>';

		$string .= self::widgetForm($widgets);

		return $string;
	}

	public static function search(
		$pageSlug,
		$pageData,
		$widgets,
		$pk) {

		$string = '<form class="form-horizontal search-form">';
		$string .= '<div class="form-group">
<div class="col-sm-12">';
		$string .= '</div></div>';

		$string .= '<div class="panel-group" id="criteriabox-'.str_replace('/', '-', $pageSlug).'" role="tablist" aria-multiselectable="true">';
		$string .= '<div class="panel panel-primary">';
		$string .= '<div class="panel-heading" role="tab" id="criteriahead-'.str_replace('/', '-', $pageSlug).'">';
		$string .= '<h4 class="panel-title">';
		$string .= '<a role="button" data-toggle="collapse" data-parent="#criteriabox-'.str_replace('/', '-', $pageSlug).'" href="#criteria-'.str_replace('/', '-', $pageSlug).'" aria-expanded="true" aria-controls="criteria-'.str_replace('/', '-', $pageSlug).'">Search for something</a>';
		$string .= '</h4>';
		$string .= '</div>';
		$string .= '<div id="criteria-'.str_replace('/', '-', $pageSlug).'" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="criteriahead-'.str_replace('/', '-', $pageSlug).'">';
		$string .= '<div class="panel-body">';

		$string .= self::widgetForm($widgets);

		$string .= '<button onClick="runSearch(\''.$pageSlug.'\', 1); return false;" class="btn btn-primary search-button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search</button>';

		$string .= '</div>';
		$string .= '</div>';
		$string .= '</div>';
		$string .= '</div>';

		$string .= '<div class="searcharea"></div>';

		return $string;
	}

	public static function widgetForm($widgets) {
		$string = '';

		foreach($widgets as $widget) {
			ob_start();
			$widget->render();
			$string .= ob_get_contents();
			ob_end_clean();
		}

		return $string;
	}

	public static function auditForm($record, $pk) {
		$string = '';
		$string .= '<h4>Audit trail for this entry</h4>';

		try {
			$manager = \FelixOnline\Core\BaseManager::build('FelixOnline\Core\AuditLog', 'audit_log');
			$items = $manager->filter('table = "'.$record->dbtable.'"')
					->filter('key = "'.$record->fields[$pk]->getRawValue().'"')
					->order('timestamp', 'DESC')
					->values();

			if(count($items) == 0) {
				$string .= '<div class="alert alert-danger">No entries</div>';
			} else {
				$string .= '<table class="table table-bordered table-hover sortable" id="audit-list-table">';
				$string .= '<thead><tr>';

				$columns = array("timestamp" => "Timestamp", "user" => "By", "action" => "Action", "fields" => "Changed data");

				foreach($columns as $column) {
					$string .= '<th>'.$column.'</th>';
				}

				$string .= '</tr></thead><tbody>';

				foreach($items as $item) {
					$string .= '<tr>';
					foreach($columns as $key => $column) {
						$string .= '<td>'.$item->fields[$key]->getRawValue().'</td>';
					}
					$string .= '</tr>';
				}

				$string .= '</tbody></table>';
			}
		} catch(\Exception $e) {
			$string .= '<div class="alert alert-danger">Could not load audit trail</div>';
		}

		return $string;
	}

	public static function home($name, $roles) {
		$string = '<p>Welcome, '.$name.'.</p>';
		$string .= '<p>Your roles are as follows:</p>';
		$string .= '<ul>';

		foreach($roles as $role) {
			$string .= '<li>'.$role->getDescription().'</li>';
		}

		$string .= '</ul>';
		$string .= '<p>Use the tabs above to manage something.</p>';
		$string .= '<p>If you encounter any problems, just click Report a Problem at the bottom of any page.<p>';
		return $string;
	}

	public static function imageForm($fieldName, $image, $hasEditor) {
		$return =  '<div class="row">
					<div class="col-md-3">
						<p><img src="'.$image->getUrl().'" style="width: 100%" alt="Selected image"></p>
						<p><button class="btn btn-danger btn-xs" id="'.$fieldName.'-remove" onClick="removeImage(\''.$fieldName.'\'); return false;"><span class="glyphicon glyphicon-erase"></span> Remove image</button></p>
					</div>';

		if($hasEditor) {
			$return .= ' <div class="col-md-8">
						<div class="form-group">
							<label for="'.$fieldName.'-descr">Description</label>
							<input type="text" class="form-control" id="'.$fieldName.'-descr" name="'.$fieldName.'-descr" value="'.$image->getDescription().'">
						</div>
						<div class="form-group">
							<label for="'.$fieldName.'-attr">Attribution</label>
							<input type="text" class="form-control" id="'.$fieldName.'-attr" name="'.$fieldName.'-attr" value="'.$image->getAttribution().'">
						</div>
						<div class="form-group">
							<label for="'.$fieldName.'-attrlink">Attribution website link</label>
							<input type="text" class="form-control" id="'.$fieldName.'-attrlink" name="'.$fieldName.'-attrlink" value="'.$image->getAttrLink().'">
						</div>
					</div>';
		}

		$return .= '</div>';

		return $return;
	}
}