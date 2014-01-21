<?php

namespace core\fs;

use core\fs\Pathfinder;

/**
 * A cache directory.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class CacheDirectory {
	/**
	 * The directory's name.
	 * @var string
	 */
	protected $name;

	/**
	 * Initialize the temporary directory.
	 */
	public function __construct($name) {
		$this->_setName($name);
	}

	/**
	 * Set this directory's name.
	 * @param string $name The name.
	 */
	protected function _setName($name) {
		if (!is_string($name) || empty($name)) {
			throw new \InvalidArgumentException('Invalid cache directory name');
		}

		$this->name = $name;

		$path = $this->path();
		if (!is_dir($path)) {
			if (!mkdir($path, 0777, true)) {
				throw new \RuntimeException('Cannot create cache directory "'.$path.'"');
			}
			chmod($path, 0777);
		}
	}

	/**
	 * Get this directory's path.
	 * @return string The path to the cache directory.
	 */
	public function path() {
		return Pathfinder::getPathFor('cache').$this->name.'/';
	}

	/**
	 * Flush this temporary directory.
	 */
	public function flush() {
		$path = $this->path();

		$this->_flushTree($path);
	}

	/**
	 * Delete a whole directory.
	 * @param  string  $dir       The directory's path.
	 * @param  boolean $deleteDir If set to true, the given directory will be deleted.
	 */
	protected function _flushTree($dir, $deleteDir = false) {
		$files = array_diff(scandir($dir), array('.','..'));

		foreach ($files as $file) {
			(is_dir($dir.'/'.$file)) ? $this->_deleteTree($dir.'/'.$file, true) : unlink($dir.'/'.$file); 
		}

		if ($deleteDir) {
			rmdir($dir);
		}
	}

	/**
	 * @see self#path()
	 */
	public function __toString() {
		return $this->path();
	}
}