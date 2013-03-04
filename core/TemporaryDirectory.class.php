<?php
namespace core;

class TemporaryDirectory {
	protected $name;

	public function __construct() {
		$this->name = date('Y-m-d-H-i-s').'_'.substr(sha1(microtime() + rand(0, 100)), 0, 10);
	}

	public function _rootPath() {
		return __DIR__.'/../cache/tmp/'.$this->name.'/';
	}

	public function root() {
		$root = $this->_rootPath();

		if (!is_dir($root)) {
			if (!mkdir($root)) {
				throw new \RuntimeException('Cannot create temporary folder "'.$root.'"');
			}
			chmod($root, 0777);
		}

		return $root;
	}

	public function flush() {
		$root = $this->root();

		$this->_flushTree($root);
	}

	protected function _flushTree($dir, $deleteDir = false) {
		$files = array_diff(scandir($dir), array('.','..'));

		foreach ($files as $file) {
			(is_dir($dir.'/'.$file)) ? $this->_deleteTree($dir.'/'.$file, true) : unlink($dir.'/'.$file); 
		}

		if ($deleteDir) {
			return rmdir($dir);
		}
	}

	public function __toString() {
		try {
			return $this->root();
		} catch (\Exception $e) {
			return '';
		}
	}

	public function __destruct() {
		$this->flush();
	}
}