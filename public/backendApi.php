<?php
if (!ini_get('display_errors')) {
	ini_set('display_errors', '1');
}
date_default_timezone_set('Europe/Paris');

require '../core/autoload.php';

use core\apps\BackendApiApplication;
use core\fs\Pathfinder;

Pathfinder::setRootDir(__DIR__.'/../');

$app = new BackendApiApplication;
$app->render();