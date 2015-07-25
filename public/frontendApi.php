<?php
if (!ini_get('display_errors')) {
	ini_set('display_errors', '1');
}
date_default_timezone_set('Europe/Paris');

require __DIR__.'/../core/autoload.php';

use core\apps\FrontendApiApplication;
use core\fs\Pathfinder;

Pathfinder::setRootDir(__DIR__.'/../');

$app = new FrontendApiApplication;
$app->render();
