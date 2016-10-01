<?php
/**
 * Generate a diagram of all pages, constraints, and access positions
 */

if(php_sapi_name() !== 'cli') {
	die('CLI only');
}

date_default_timezone_set('Europe/London');

require dirname(__FILE__) . '/../core/setup.php';

$app = \FelixOnline\Core\App::getInstance();

// Get page from menu file
$menu = json_decode(file_get_contents(dirname(__FILE__) . '/../menu.json'), true);

if($menu == null) {
	echo("The menu file is invalid.\n");
	return -1;
}

$finalMenu = array();

foreach($menu as $key => $node) {
	$finalMenu = array_merge($finalMenu, buildMenu($key, $node));
}

function buildMenu($key, $node, $parent = null) {
	if($key == 'home') {
		$name = 'home';
	} else {
		$name = $key;
	}

	$return = array();
	$return[] = array("key" => $key, "parent" => $parent);

	if(isset($node['children'])) {
		foreach($node['children'] as $chKey => $chNode) {
			if($children = buildMenu($chKey, $chNode, $key)) {
				$return = array_merge($return, $children);
			}
		}
	}

	return $return;
}

$string = "digraph {\n";

$string .= "\trankdir=\"LR\";\n\tedge [weight=1.2];\n\tlabelloc=\"t\";\n\tlabel=< <b>Page Chart as at ".date('r')."</b><br />Pages - <font color=\"blue\">Constraints</font> - <font color=\"red\">Modes (assumed all are granted unless explicitly marked as disabled or role shown)</font> - <font color=\"darkgreen\">Parent Record Reference</font> - <font color=\"purple\">Actions</font><br /> >;\n";

$pageStuffThatBelongsToIt = array();
$pageCat = array();
$pageMap = array();

foreach($finalMenu as $page) {
	// Load page
	$pageInfo = new \FelixOnline\Admin\Page($page['key'], true);

	if($page['key'] == 'home') {
		$pageCat[$page['key']] = '<i>Root page</i>';
	} elseif($pageInfo->lightLoad($page['key'], false)) {
		$roleText = '<i>Requires at least: '.implode(' or ', $pageInfo->getPageData()['baseRole']).'</i>';

		if($pageInfo->getPageData()['modes'] && $pageInfo->getPageData()['modes']['list'] && $pageInfo->getPageData()['modes']['list']['canDelete']) {
			$roleText .= '<br /><b>May delete records</b>';
		}

		$pageCat[$page['key']] = '<b>'.$pageInfo->getName().' ('.$pageInfo->getPageData()['model'].')</b><br />'.$page['key'].'<br />'.$roleText;
	} else {
		$pageCat[$page['key']] = '<i>Invalid JSON on '.$page['key'].'</i>';
	}

	if($page['parent'] != null) {
		$pageMap[] = array($page['parent'], $page['key']);
	} elseif($page['key'] != 'home') {
		$pageMap[] = array('home', $page['key']);
	}
}

foreach($pageCat as $pageId => $pageName) {
	$pageModes = array();
	$pageConstraints = array();
	$pagePullThrough = array();
	$pageActions = array();
	$pageStuffThatBelongsToIt = array();

	if($pageName == '<i>Root page</i>' && $pageId == 'home') {
		$colour = ' style=filled, fontcolor=white, fillcolor=black, ';
	} else {
		$colour = '';
	}

	$string .= "\tsubgraph \"".uniqid("sg", true)."\" {\n";
	$string .= "\t\t\"".$pageId.'"[shape=rectangle,'.$colour.'label=<'.$pageName.'>]'.";\n";

	// Load page
	$pageInfo = new \FelixOnline\Admin\Page($pageId, true);
	$pageInfo->lightLoad($pageId, false);

	// PullThrough
	if($pageInfo->getPageData()['pullThrough']) {
		$id = uniqid("", true);

		$pagePullThrough[$id] = $pageInfo->getPageData()['pullThrough'];
		$pageMap[] = array($id, $pageId);

		$pageStuffThatBelongsToIt[$id] = $id;
	}

	// Modes
	if($pageInfo->getPageData()['modes']) {
		foreach($pageInfo->getPageData()['modes'] as $modeName => $info) {
			$id = uniqid("", true);

			if(!$info['roles'] || count($info['roles']) == 0) {
				$roles = false;

				if($info['enabled']) {
					continue;
				}
			} else {
				$roles = 'Requires at least: '.implode(' or ', $info['roles']);
			}

			$pageModes[$id] = array('name' => ucfirst($modeName), 'enabled' => $info['enabled'], 'roles' => $roles);
			$pageMap[] = array($id, $pageId);

			$pageStuffThatBelongsToIt[$id] = $id;
		}
	}

	// Actions
	if($pageInfo->getPageData()['actions']) {
		foreach($pageInfo->getPageData()['actions'] as $actionName => $info) {
			$id = uniqid("", true);

			if(!$info['roles'] || count($info['roles']) == 0) {
				$roles = false;
			} else {
				$roles = 'Requires at least: '.implode(' or ', $info['roles']);
			}

			$pageActions[$id] = array('name' => $actionName, 'description' => $info['label'], 'roles' => $roles);
			$pageMap[] = array($id, $pageId);

			$pageStuffThatBelongsToIt[$id] = $id;
		}
	}

	// Constraints
	if($pageInfo->getPageData()['constraints']) {
		foreach($pageInfo->getPageData()['constraints'] as $info) {
			$id = uniqid("", true);

			if(!$info['roles'] || count($info['roles']) == 0) {
				$roles = false;
			} else {
				$roles = 'If has: '.implode(' or ', $info['roles']);
			}

			if(!$info['notRoles'] || count($info['notRoles']) == 0) {
				$antiroles = false;
			} else {
				$antiroles = 'If does not have: '.implode(' or ', $info['notRoles']);
			}

			if($info['specialConstraint']) {
				switch($info['specialConstraint']) {
					case 'isPublished':
						$detail = 'Article is published';
						break;
					case 'isEditor':
						$detail = 'User is editor of section';
						break;
					case 'isAuthor':
						$detail = 'User is author of article';
						break;
					case 'isMe':
						$detail = 'User is subject of record';
						break;
					default:
						$detail = 'Unknown special constraint';
						break;
				}
			} else {
				$detail = $info['field'].' '.$info['operator'].' '.$info['test'];
			}

			$pageConstraints[$id] = array('detail' => $detail, 'antiroles' => $antiroles, 'roles' => $roles);
			$pageMap[] = array($id, $pageId);

			$pageStuffThatBelongsToIt[$id] = $id;
		}
	}

	foreach($pageModes as $id => $modeInfo) {
		if(!$modeInfo['roles']) {
			$text = '<b>'.$modeInfo['name'].'</b>';
		} else {
			$text = '<b>'.$modeInfo['name'].'</b><br /><i>'.$modeInfo['roles'].'</i>';
		}

		if($modeInfo['enabled']) {
			$color = 'red';
		} else {
			$color = 'grey';
			$text .= '<br />Disabled';
		}

		$string .= "\t\t\"".$id.'"[shape=rectangle, color='.$color.' fontcolor='.$color.' label=<'.$text.'>]'.";\n";
	}

	foreach($pagePullThrough as $id => $pullInfo) {
		if($pullInfo['childField']) {
			$text = '<b>Source: '.$pullInfo['class'].' then model for <i>'.$pullInfo['childField'].'</i> foreign key field</b><br />';
		} else {
			$text = '<b>Source: '.$pullInfo['class'].'</b><br />';
		}

		switch($pullInfo['mode']) {
			case 'single':
				$text .= 'Type: Record to Source Record.<br />Getting record with PK equal to <i>'.$pullInfo['keyField'].'</i> on source record.';
				break;
			case 'multi':
				$text .= 'Type: Many Records to Source Record.<br />Getting records with <i>'.$pullInfo['pullField'].'</i> equal to PK on source record.';
				break;
			case 'map':
				$text .= 'Type: Many Records to Source Record via Map.<br />Where source record is <i>'.$pullInfo['pullField'].'</i> on map, and this is <i>'.$pullInfo['keyField'].'</i>.';
				break;
		}

		if($pullInfo['optional']) {
			$text .= '<br /><i>Optional</i>';
		}

		$string .= "\t\t\"".$id.'"[shape=rectangle, color=darkgreen fontcolor=darkgreen label=<'.$text.'>]'.";\n";
	}

	foreach($pageConstraints as $id => $constraintInfo) {
		$text = '<b>'.$constraintInfo['detail'].'</b>';

		if($constraintInfo['roles']) {
			$text .= '<br /><i>'.$constraintInfo['roles'].'</i>';
		}

		if($constraintInfo['antiroles']) {
			$text .= '<br /><i>'.$constraintInfo['antiroles'].'</i>';
		}

		$string .= "\t\t\"".$id.'"[shape=rectangle, color=blue fontcolor=blue label=<'.$text.'>]'.";\n";
	}

	foreach($pageActions as $id => $actionsInfo) {
		$text = '<b>'.$actionsInfo['name'].' ('.$actionsInfo['description'].')</b>';

		if($constraintInfo['roles']) {
			$text .= '<br /><i>'.$constraintInfo['roles'].'</i>';
		}

		$string .= "\t\t\"".$id.'"[shape=rectangle, color=purple fontcolor=purple label=<'.$text.'>]'.";\n";
	}

	foreach($pageMap as $parentId) {
		if(array_key_exists($parentId[0], $pageStuffThatBelongsToIt)) {
			$pageId = $parentId[0];
			$parentId = $parentId[1];
			$string .= "\t\t\"".$parentId."\"->\"".$pageId."\";\n";
		}
	}

	$string .= "\t}\n";
}

foreach($pageMap as $parentId) {
	if(array_key_exists($parentId[0], $pageCat)) {
		$pageId = $parentId[0];
		$parentId = $parentId[1];
		$string .= "\t\"".$pageId."\"->\"".$parentId."\";\n";
	}
}

// ranks
$ranks = array();
foreach($finalMenu as $page) {
	if($page['key'] == 'home') {
		continue;
	}

	if(!array_key_exists($page['parent'], $ranks)) {
		$ranks[$page['parent']] = array();
	}

	$ranks[$page['parent']][] = $page['key'];

}

//$string .= "\t{rank=same home}\n";

foreach($ranks as $rankable) {
	if(count($rankable) < 2) {
		continue;
	}

	//$string .= "\t{rank=same \"".implode("\" \"", $rankable)."\"}\n";
}

$string .= "}\n";

$proc = proc_open(
	'dot -Tpdf -o pages.pdf',
	[ [ 'pipe', 'r' ], [ 'pipe', 'w' ]],
	$pipes
);

echo $string;

fwrite($pipes[0], $string);
fclose($pipes[0]);
fclose($pipes[1]);
$return_value = proc_close($proc);

echo "process code $return_value, if zero generated pages.pdf\n";
exit(0);