<?php

namespace FelixOnline\Admin;

class UXHelper {
	public static function header(
		$pageName,
		$endpoint = '') {
		global $currentuser;

		$string = '<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>'.$pageName.' - '.SERVICE_NAME.'</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

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
<div class="container-header">
	<div class="container">
		<div class="row">
			<div class="col-md-10">
				<h1>'.SERVICE_NAME.'</h1>
			</div>';

		if($currentuser->isLoggedIn()) {
			$string .= '<div class="col-md-2">
				<button onClick="logout(); return false;" class="btn btn-primary logout-button pull-right"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Log out</button>
			</div>';
		}

		$string .= '</div></div>
	</div>';

		$app = \FelixOnline\Core\App::getInstance();
		$string .= self::menu($app['env']['session']->session['menu'], $endpoint);

		$string .= '<div class="container">
	<h2>'.$pageName.'</h2>';
		return $string;
	}

	public static function menu($menu, $endpoint) {
		if($endpoint == '') { // If no selected item
			return self::renderMenu($menu, '', $endpoint, true); // Render all root items in main style
		}

		// Build list of menus to render
		$active_item = $menu[$endpoint];

		$menus = array();

		$last_item = $endpoint; // The last selected item (for highlighting)
		$current_menu = $active_item['parent']; // Set the current menu to the one which contains the selected item

		while($current_menu != null) {
			$menus[] = array($current_menu, $last_item); // Set this menu to be rendered
			$last_item = $current_menu; // The next 'selected' item is the parent of the previous selected item

			$current_menu = $menu[$current_menu]['parent']; // Now find what menu contains this item
		}

		$menus[] = array($current_menu, $last_item); // Finally put the root menu in
		$menus = array_reverse($menus); // Reverse it so the root menu is top

		$root_menu = true;
		$string = '';

		foreach($menus as $menuToShow) {
			$string .= self::renderMenu($menu, $menuToShow[0], $menuToShow[1], $root_menu); // Render all root items in main style

			$root_menu = false;
		}

		$string .= self::renderMenu($menu, $menuToShow[1], '', $root_menu); // Then show any child items of the selected level

		return $string;
	}

	function renderMenu($menu, $levelToShow, $selectedItem, $root) {
		$items = 0;

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

			$string .= '<nav class="navbar navbar-default">
			<div class="container">
			<ul class="nav navbar-nav">
			<li'.$selected.'><a href="'.STANDARD_URL.'">Home</a></li>';
		} else {
			$string .= '<div class="container">
			<ul class="nav nav-tabs">';
		}

		foreach($menu as $key => $item) {
			if($item['parent'] != $levelToShow) {
				continue;
			}

			if($key == $selectedItem) {
				$selected = ' class="active"';
			} else {
				$selected = '';
			}

			$string .= '<li'.$selected.'><a href="'.STANDARD_URL.$key.'">'.$item['label'].'</a></li>';
		}

		if($root) {
			$string .= '</ul>
			</div>
			</nav>';
		} else {
			$string .= '</ul>
			</div>';
		}

		return $string;
	}

	public static function footer() {
		$string = '</div>
		<div class="container-footer">
			<footer>
				<div class="container">
					<p>&copy; Felix Online - <a href="#" data-toggle="modal" data-target="#rap">Report a Problem</a> - <a href="https://github.com/FelixOnline/FelixAdmin">Github</a></p>
					<p>Icons are kindly provided by <a href="http://glyphicons.com/">Glyphicons</a> via Twitter Bootstrap.</p>
				</div>
			</footer>
		</div>';

		global $currentuser;

		$string .= '<div class="modal fade" id="rap">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Report a Problem</h4>
				</div>
				<div class="modal-body">';

		if($currentuser->isLoggedIn()):
					$string .= '<p>When you report a problem, details about the page you are accessing and who you are will be sent to Felix Online developers in addition to details about your web browser.</p>
				<p>Please specify as much information as possible in the box below about <b>exactly</b> what you are trying to do, the steps you have taken, what happened, and what you expected to happen.</p>
				<p>You may not receive a reply to your problem report however all feedback will be used to improve this service.</p>
				<textarea class="form-control" name="rap-feedback" rows="15" id="rap-feedback" placeholder="Provide details about the problem you are experiencing here"></textarea>';
		else:
			$string .= '<p>You must be logged in to report a problem. If you are unable to log in, please email <a href="felix@imperial.ac.uk">felix@imperial.ac.uk</a>';
		endif;

				$string .= '</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button onClick="rap(); return false;" class="btn btn-primary rap-button"><span class="glyphicon glyphicon-send" aria-hidden="true"></span> Submit</button>
				</div>
			</div>
		</div>
	</div>
	<div id="csrf-key" data-csrf=""></div>
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
	<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.min.js"></script>

	<script>
		function getAjaxEndpoint() {
			return "'.STANDARD_URL.'ajax.php";
		}
		function getImageUploadEndpoint() {
			return "'.STANDARD_URL.'uploadImage.php";
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
		$string .= '<p>Welcome to '.SERVICE_NAME.'. Please log in with your IC credentials.</p>';
		$string .= '<p>You can only log in to this website if you have a Felix Online account and have been given access to this website.</p>';
		$string .= '<p>To create an account, simply log in on the main website. To be granted access, please contact a member of the Felix team.</p>';

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
		$endpoint = '') {

		echo self::header($name, $endpoint);

		foreach($content as $item) {
			echo $item;
		}

		echo self::footer();
	}

	public static function tabs(
		$pageSlug,
		$access,
		$currentView,
		$currentKey = null) {

		$string = '<ul class="nav nav-pills">';

		if($access['list']) {
			if($currentView == 'list') {
				$current = 'class="active"';
			} else {
				$current = '';
			}

			$string .= '<li role="presentation" '.$current.'><a href="'.STANDARD_URL.$pageSlug.':list" '.$current.'><span class="glyphicon glyphicon-list" aria-hidden="true"></span> List</a></li>';
		}

		if($access['search']) {
			if($currentView == 'search') {
				$current = 'class="active"';
			} else {
				$current = '';
			}

			$string .= '<li role="presentation" '.$current.'><a href="'.STANDARD_URL.$pageSlug.':search" '.$current.'><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search</a></li>';
		}

		if($access['details'] && $currentView == 'details') {
			if($currentView == 'details') {
				$current = 'class="active"';
			} else {
				$current = '';
			}

			$string .= '<li role="presentation" '.$current.'><a href="'.STANDARD_URL.$pageSlug.':details/'.$currentKey.'" '.$current.'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit</a></li>';
		}

		if($access['new']) {
			if($currentView == 'new') {
				$current = 'class="active"';
			} else {
				$current = '';
			}

			$string .= '<li role="presentation" '.$current.'><a href="'.STANDARD_URL.$pageSlug.':new" '.$current.'><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> New</a></li>';
		}

		$string .= '</ul>';

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
				foreach($app['env']['session']->session['roles'] as $role) {
					if(array_search($role, $pageData['modes']['new']['roles'])) {
						$newAccess = true;
					}
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
				foreach($app['env']['session']->session['roles'] as $role) {
					if(array_search($role, $pageData['modes']['details']['roles'])) {
						$detailsAccess = true;
					}
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
				foreach($app['env']['session']->session['roles'] as $role) {
					if(array_search($role, $pageData['modes']['list']['roles'])) {
						$listAccess = true;
					}
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
				foreach($app['env']['session']->session['roles'] as $role) {
					if(array_search($role, $pageData['modes']['search']['roles'])) {
						$searchAccess = true;
					}
				}
			} else {
				$searchAccess = true;
			}
		}

		return array('new' => $newAccess, 'list' => $listAccess, 'search' => $searchAccess, 'details' => $detailsAccess);
	}

	public static function listStub(
		$page,
		$currentPage) {

		$string = '
			<br>
			<b class="text text-success form-status form-status-no-indent" style="display: none"></b>
			<div class="alert alert-info load-msg"><span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span> Refreshing list...</div>
			<div id="list-area"></div>
			<script>
			document.addEventListener("DOMContentLoaded", function(event) {
				refreshList("'.$page.'", "'.$currentPage.'");
			});
			</script>';

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

		if(count($records) == 0) {
			return '<h3>No data found</h3>';
		}

		$string = '<div class="alert alert-info action-msg" style="display: none;"><span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span> Working on it...</div>
<div class="list-area">';

		$string .= '<b class="text text-success form-status" style="display: none"></b>';

		// What are our headings
		$columns = array();

		foreach($pageData['modes']['list']['columns'] as $column) {
			$columns[$column] = $pageData['fields'][$column]['label'];
		}

		if(!$surpressPaginator) {
			$string .= UXHelper::recordPaginator(
				$pageSlug,
				$numRecords,
				$currentPage,
				$searchView);
		}

		if(count($actions) > 0) {
			$string .= '<p><div class="btn-group" role="group">';

			$string .= '<button class="btn btn-default toggler" onClick="toggleSelect();">Select all</button>';

			$string .= '<button class="btn btn-default" disabled onClick="return: false;">With selected items:</button>';

			foreach($actions as $key => $action) {
				$string .= '<button class="btn btn-primary" onClick="runAction(\''.$key.'\', \''.$pageSlug.'\'); return false;"><span class="glyphicon glyphicon-'.$action['icon'].'"></span>&nbsp;&nbsp;'.$action['label'].'</button>';
			}

			$string .= '</div></p>';
		}

		$string .= '<div class="table-responsive">';
		$string .= '<table class="table table-bordered table-hover sortable" id="list-table" data-currentpage="'.$currentPage.'">';
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
			$string .= '<tr id="row-'.$record->fields[$primaryKey]->getValue().'">';

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
								$value .= $fieldVal->fields[$pageData['fields'][$colid]['multiMap']['foreignKey']]->getValue()
										->fields[$pageData['fields'][$colid]['multiMap']['foreignKeyField']]->getValue();
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


				// Type transformations
				$type = get_class($record->fields[$colid]);

				if($type == "FelixOnline\Core\Type\ForeignKey") {
					try {
						if($record->fields[$colid]->class == 'FelixOnline\Core\Image') {
							if($record->fields[$colid]->getValue() == null) {
								$value = ''; // No image set
							} else {
								$value = '<a href="'.$record->fields[$colid]->getValue()->getUrl().'">'.$record->fields[$colid]->getValue()->getUrl().'</a>';
							}
						} elseif($record->fields[$colid]->class == 'FelixOnline\Core\Text') {
							$value = '<span class="text-danger">Text fields cannot be shown here</span>';
						} else {
							$value = $record->fields[$colid]->getValue(); // This gets the object itself

							if(is_null($value->fields[$pageData['fields'][$colid]['foreignKeyField']])) {
								throw new \Exception('No value found');
							}

							$value = $value->fields[$pageData['fields'][$colid]['foreignKeyField']]->getValue(); // This gets the desired field from the object
						}
					} catch(\Exception $e) {
						$value = '<span class="text-danger">Error finding data</span>';
					}
				} else {
					$value = $record->fields[$colid]->getValue();
				}

				switch($type) {
					case "FelixOnline\Core\Type\TextField":
						$dots = '';

						if(strlen(strip_tags($value)) > 200) {
							$dots = '...';
						}
						
						$value = substr(strip_tags($value), 0, 200).$dots;
						break;
					case "FelixOnline\Core\Type\DateTimeField":
						$value = date('Y-m-d H:i:s', $value);
						break;
					case "FelixOnline\Core\Type\BooleanField":
						if($value) {
							$value = '<span class="glyphicon glyphicon-check text-success" aria-label="True"> Yes</span>';
						} else {
							$value = '<span class="glyphicon glyphicon-unchecked text-danger" aria-label="False"> No</span>';
						}
						break;
				}

				if($colid == $primaryKey && $canDetails) {
					$value = '<center><a href="'.STANDARD_URL.$pageSlug.':details/'.$value.'" onClick="show_details(\''.$pageSlug.'\', \''.$value.'\'); return false;"><span class="glyphicon glyphicon-'.$value.' glyphicon-pencil" aria-hidden="true"></span> '.$value.'</a></center>';
				}

				$string .= '<td>'.$value.'</td>';
			}

			if($canDelete) {
				$string .= '<td><span class="glyphicon glyphicon-trash delete-icon text-danger" id="del-'.$record->fields[$primaryKey]->getValue().'" onClick="del(\''.$pageSlug.'\', \''.$record->fields[$primaryKey]->getValue().'\');"></span></td>';
			}

			$string .= '</tr>';
		}
		$string .= '</tbody></table></div></div>';

		$plural = '';

		if($numRecords != 1) {
			$plural = 's';
		}

		$string .= '<p><span class="label label-default">'.$numRecords.' record'.$plural.' found</span></p>';

		// Modal for popup details forms
		$string .= '<div class="modal fade modal-wide" id="detailsview">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Loading</h4>
				</div>
				<div class="modal-body">
					Please wait...
				</div>
			</div>
		</div>
	</div>';

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
			$string .= '<li class="disabled"><a href="#"><span class="glyphicon glyphicon-chevron-left" aria-label="Next"></span></a></li>';
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
			$string .= '<li class="disabled"><a href="#"><span class="glyphicon glyphicon-chevron-right" aria-label="Next"></span></a></li>';
		}

		$string .= '<li class="disabled"><a href="#">Page '.$currentPage.' of '.$finalPage.'</a></li>';

		$string .= '</ul></nav>';

		return $string;
	}

	public static function details(
		$pageSlug,
		$pageData,
		$currentRecord,
		$widgets,
		$pk,
		$readOnly = false) {

		$hint = $pageData['modes']['details']['headerHint'];

		$currentRecord = $currentRecord[0];

		$string = '<form class="form-horizontal">';

		$string .= '<div class="form-group">
<div class="col-sm-12">';

		if(!$readOnly) {
			$string .= '<button onClick="save(\''.$pageSlug.'\', \''.$currentRecord->fields[$pk]->getValue().'\'); return false;" class="btn btn-primary save-button"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Save</button>';
			$string .= '<b class="text text-success form-status" style="display: none"></b>';
			$mode = 'Editing';
		} else {
			$mode = 'Viewing';
		}

		$string .= '</div></div>';

		$string .= self::widgetForm($widgets);

		if(!$readOnly) {
			$string .= '<button onClick="save(\''.$pageSlug.'\', \''.$currentRecord->fields[$pk]->getValue().'\'); return false;" class="btn btn-primary save-button"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Save</button>';
		}

		return array('string' => $string,
			'heading' => $mode.': '.$currentRecord->fields[$hint]->getValue().' ('.$currentRecord->fields[$pk]->getValue().')');
	}

	public static function creator(
		$pageSlug,
		$pageData,
		$widgets,
		$pk) {

		$string = '<h3>New Entry</h3>';
		$string .= '<form class="form-horizontal">';

		$string .= '<div class="form-group">
<div class="col-sm-12">';
		$string .= '<button onClick="create(\''.$pageSlug.'\'); return false;" class="btn btn-primary new-button"><span class="glyphicon glyphicon-saved" aria-hidden="true"></span> Create</button>';
		$string .= '<b class="text text-success form-status" style="display: none"></b>';
		$string .= '</div></div>';

		$string .= self::widgetForm($widgets);

		$string .= '<button onClick="create(\''.$pageSlug.'\'); return false;" class="btn btn-primary new-button"><span class="glyphicon glyphicon-saved" aria-hidden="true"></span> Create</button>';

		return $string;
	}

	public static function search(
		$pageSlug,
		$pageData,
		$widgets,
		$pk) {

		$string = '<h3>Search for something</h3>';
		$string .= '<form class="form-horizontal" id="search-form">';
		$string .= '<div class="form-group">
<div class="col-sm-12">';
		$string .= '<button onClick="runSearch(\''.$pageSlug.'\', 1); return false;" class="btn btn-primary search-button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search</button>';
		$string .= '<b class="text text-success form-status" style="display: none"></b>';
		$string .= '</div></div>';

		$string .= self::widgetForm($widgets);

		$string .= '<button onClick="runSearch(\''.$pageSlug.'\', 1); return false;" class="btn btn-primary search-button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search</button><br><br>';

		$string .= '<div id="search-results"></div>';

		return $string;
	}


	public static function widgetForm($widgets) {
		$string = '<script>
			document.addEventListener("DOMContentLoaded", function(event) {
				SirTrevor.runOnAllInstances("destroy");
			});
		</script>';

		foreach($widgets as $widget) {
			ob_start();
			$widget->render();
			$string .= ob_get_contents();
			ob_end_clean();
		}

		$string .= '</form>';
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