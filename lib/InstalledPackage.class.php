<?php
namespace lib;

class InstalledPackage extends Package {
	public function __construct(Repository $repository, \lib\entities\PackageMetadata $metadata, $files) {
		parent::__construct($repository, $metadata);
		$this->files = $files;
	}

	public function files() {
		return $this->files;
	}
}