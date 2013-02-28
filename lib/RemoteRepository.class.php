<?php
namespace lib;

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

	public function getPackagesList() {
		if ($this->packages !== null) {
			return $this->packages;
		}

		$indexPath = $this->url . '/index.json';
		$json = file_get_contents($indexPath);
		if ($json === false) { throw new \RuntimeException('Cannot open index file : "'.$indexPath.'"'); }

		$list = json_decode($json, true);
		if ($list === false || json_last_error() != JSON_ERROR_NONE) { throw new \RuntimeException('Cannot load index file (malformed JSON) : "'.$indexPath.'"'); }

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

	public function getPackageMetadata($pkgName) {
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