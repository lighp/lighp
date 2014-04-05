<?php
namespace lib;

use core\fs\CacheDirectory;
use \RuntimeException;

class RemoteRepository implements Repository {
	protected $name, $url, $packages;

	public function __construct($name, $url) {
		$this->name = $name;
		$this->url = $url;
	}

	public function name() {
		return $this->name;
	}

	public function url() {
		return $this->url;
	}

	public function _cacheFilePath() {
		$cacheDir = new CacheDirectory('app/packagecontrol');
		$cacheFile = $cacheDir->path().'/'.$this->name().'.json';
		return $cacheFile;
	}

	public function getPackagesList() {
		if ($this->packages !== null) { //Packages already listed
			return $this->packages;
		}

		//Cache file
		$cacheFile = $this->_cacheFilePath();
		$isCached = file_exists($cacheFile);
		if ($isCached) {
			$indexPath = $cacheFile;
		} else {
			$indexPath = $this->url . '/index.json';
		}

		//Open index file
		$json = file_get_contents($indexPath);
		if ($json === false) {
			throw new RuntimeException('Cannot open index file : "'.$indexPath.'"');
		}

		$list = json_decode($json, true);
		if ($list === false || json_last_error() != JSON_ERROR_NONE) {
			throw new RuntimeException('Cannot load index file (malformed JSON) : "'.$indexPath.'"');
		}

		if (!$isCached) { //If repo is not cached
			file_put_contents($cacheFile, $json);
			chmod($cacheFile, 0777);
		}

		$this->packages = array();

		foreach($list as $pkgData) {
			try {
				$this->packages[$pkgData['name']] = new \lib\entities\PackageMetadata($pkgData);
			} catch(\Exception $e) {
				continue;
			}
		}

		return $this->packages;
	}

	public function updatePackagesList() {
		$this->packages = null;

		$cacheFile = $this->_cacheFilePath();
		if (file_exists($cacheFile)) {
			unlink($this->_cacheFilePath());
		}

		return $this->getPackagesList();
	}

	public function getPackageMetadata($pkgName) {
		if ($this->packages === null) {
			$this->getPackagesList();
		}

		return (isset($this->packages[$pkgName])) ? $this->packages[$pkgName] : null;
	}

	public function getPackage($pkgName) {
		if ($this->packages === null) {
			$this->getPackagesList();
		}

		if (!$this->packageExists($pkgName)) {
			return;
		}

		$metadata = $this->getPackageMetadata($pkgName);

		return new RemotePackage($this, $metadata);
	}

	public function packageExists($pkgName) {
		if ($this->packages === null) {
			$this->getPackagesList();
		}

		return isset($this->packages[$pkgName]);
	}
}