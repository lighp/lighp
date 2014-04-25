<?php
/**
 * Load a class.
 * @param  string $class The class' name.
 */
function autoload($class) {
	$classPath = __DIR__.'/../'.str_replace('\\', '/', $class).'.class.php';

	if (file_exists($classPath)) {
		require($classPath);
	}
}

spl_autoload_register('autoload');

$vendorAutoloadPath = __DIR__.'/../lib/vendor/autoload.php';
if (!file_exists($vendorAutoloadPath)) {
	throw new \RuntimeException('Cannot load Composer autoload "'.$vendorAutoloadPath.'", please run "composer install" to install dependencies');
}

require($vendorAutoloadPath);