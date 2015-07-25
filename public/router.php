<?php
if (php_sapi_name() !== 'cli-server') {
	throw new Exception('This router must be called from the command line.');
}

chdir(__DIR__);

$reqPath = $_SERVER['REQUEST_URI'];

$handlers = array(
	'/api/admin' => './backendApi.php',
	'/api' => './frontendApi.php',
	'/admin' => './backend.php',
	//'' => './frontend.php'
);

foreach ($handlers as $path => $handler) {
	$len = strlen($path);
	if (($len === 0 || strpos($reqPath, $path) === 0) &&
		(strlen($reqPath) == $len || $reqPath[$len] === '/')) {
		require(__DIR__.'/'.$handler);
		return;
	}
}

$reqFile = __DIR__.$reqPath;
if (is_file($reqFile)) {
	return false;
}

require(__DIR__.'/frontend.php');
