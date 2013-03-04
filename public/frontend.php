<?php
if (!ini_get('display_errors')) {
	ini_set('display_errors', '1');
}

require '../core/autoload.php';

$app = new core\FrontendApplication;
$app->run();