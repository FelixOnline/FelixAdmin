<?php
/**
 * Generate a diagram of all users roles
 */

if(php_sapi_name() !== 'cli') {
	die('CLI only');
}

date_default_timezone_set('Europe/London');

require dirname(__FILE__) . '/../core/setup.php';

$app = \FelixOnline\Core\App::getInstance();

$roles = $app['db']->get_results("SELECT * FROM roles");
$roleCat = array();
$roleMap = array();

$string = "digraph {\n";

$string .= "\tlabelloc=\"t\";\n\tlabel=< <b>Roles Chart as at ".date('r')."</b><br /> >;\n";

foreach($roles as $role) {
	$roleCat['role_'.$role->id] = '<b>'.$role->name.'</b><br />'.$role->description;

	if($role->parent != null) {
		$roleMap['role_'.$role->id] = 'role_'.$role->parent;
	}
}

foreach($roleCat as $roleId => $roleName) {
	$string .= "\t".$roleId.'[label=<'.$roleName.'>]'.";\n";
}

foreach($roleMap as $roleId => $parentId) {
	$string .= "\t".$roleId."->".$parentId.";\n";
}

$string .= "}\n";

$proc = proc_open(
	'dot -Tpdf -o roles.pdf',
	[ [ 'pipe', 'r' ], [ 'pipe', 'w' ]],
	$pipes
);

fwrite($pipes[0], $string);
fclose($pipes[0]);
fclose($pipes[1]);
$return_value = proc_close($proc);

echo "process code $return_value, if zero generated roles.pdf\n";
exit(0);