<?php

namespace core\fs;

/**
 * Path finder
 * @author Jean THOMAS
 * @since 1.0alpha2
 */
class Pathfinder {
	protected static $_rootdir;
	
	protected static $_domains = [
		'cache' => 'var/cache/',
		'tmp' => 'var/tmp/',
		'public' => 'public'
	];


	public static function setRootDir($rootdir) {
		self::$_rootdir = $rootdir;
	}

	public static function getPathFor($domain) {
		return self::$_rootdir.'/'.self::$_domains[$domain];
	}

	public static function getRoot() {
		return self::$_rootdir;
	}
}

?>