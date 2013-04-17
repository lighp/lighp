<?php
namespace lib;

abstract class Package extends \core\ApplicationComponent {
	protected $repository, $metadata;

	public function __construct(Repository $repository, \lib\entities\PackageMetadata $metadata) {
		$this->repository = $repository;
		$this->metadata = $metadata;
	}

	public function repository() {
		return $this->repository;
	}

	public function metadata() {
		return $this->metadata;
	}

	abstract public function files();

	public function unsafeFiles() {
		$files = $this->files();

		$modulesDirs = array('ctrl','etc/app','lib','tpl','share','public/css/app','public/img','public/js/app');
		$unsafeFiles = array();

		foreach($files as $path => $data) {
			$safePath = false;

			if ($data['noextract']) {
				continue;
			}

			if (preg_match('#/\.\.+/#', $path)) {
				$unsafeFiles[] = $path;
				continue;
			}

			foreach($modulesDirs as $dir) {
				$dirPos = strpos($path, $dir . '/');

				if ($dirPos === 0 || ($dirPos == 1 && substr($path, 0, 1) == '/')) {
					$safePath = true;
				}
			}

			if (!$safePath) {
				$unsafeFiles[] = $path;
			}
		}

		return $unsafeFiles;
	}

	public function unsafe() {
		if (isset($this->metadata()['hasScripts']) && $this->metadata()['hasScripts'] == true) {
			return true;
		}

		$unsafeFiles = $this->unsafeFiles();
		if (count($unsafeFiles) > 0) {
			return true;
		}

		return false;
	}

	public function depends() {
		$dependencies = array();

		$dependsString = $this->metadata()['depends'];

		if (!empty($dependsString)) {
			$packages = explode(' ', $dependsString);

			foreach($packages as $pkgName) {
				$dep = array('name' => $pkgName);

				if (preg_match('#^([a-z0-9.-_]+)(<|>|<=|>=|=)([0-9a-z.-_]+)$#i', $pkgName, $matches)) {
					$dep = array(
						'name' => $matches[1],
						'versionOperator' => $matches[2],
						'version' => $matches[3]
					);
				}

				$dependencies[] = $dep;
			}
		}

		return $dependencies;
	}
}