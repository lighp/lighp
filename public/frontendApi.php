<?php
if (!ini_get('display_errors')) {
	ini_set('display_errors', '1');
}
date_default_timezone_set('Europe/Paris');

require '../core/autoload.php';

$app = new core\FrontendApiApplication;
$app->run();