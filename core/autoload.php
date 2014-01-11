<?php
/**
 * Load a class.
 * @param  string $class The class' name.
 */
function autoload($class) {
	$classPath = __DIR__.'/../'.str_replace('\\', '/', $class).'.class.php';

	if (file_exists($classPath)) {
		require $classPath;
	}
}

spl_autoload_register('autoload');

require __DIR__.'/../lib/vendor/autoload.php';