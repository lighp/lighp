<?php

namespace core\fs;

/**
 * Path finder
 * @author Jean THOMAS
 * @since 1.0alpha2
 */
class Pathfinder {
	protected static $_rootdir = null;
	
	protected static $_domains = [
		'cache' => 'var/cache/',
		'tmp' => 'var/tmp/',
		'public' => 'public',
		'locale' => 'share/locale/',
		'tpl' => 'tpl/',
		'config' => 'etc/'
	];


	public static function setRootDir($rootdir) {
		self::$_rootdir = $rootdir;
	}

	public static function getPathFor($domain) {
		if (isset(self::$_domains[$domain])) {
			return self::$_rootdir.'/'.self::$_domains[$domain];
		} else {
			throw new \InvalidArgumentException('Domain "'.$domain.'" not recognized');
		}
	}

	public static function getRoot() {
		return self::$_rootdir;
	}
}