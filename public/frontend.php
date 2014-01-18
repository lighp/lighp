<?php
if (!ini_get('display_errors')) {
	ini_set('display_errors', '1');
}
date_default_timezone_set('Europe/Paris');

require '../core/autoload.php';

use core\apps\FrontendApplication;

$app = new FrontendApplication;
$app->render();