<?php
namespace lib\entities;

class PackageMetadata extends \core\Entity {
	protected $name, $title, $subtitle, $version, $description, $url, $maintainer, $license, $depends, $size, $extractedSize, $updateDate, $hasScripts;

	// SETTERS //
	
	public function setName($name) {
		if (!is_string($name) || empty($name) || !preg_match('#^[a-zA-Z0-9-_.]+$#', $name)) {
			throw new \InvalidArgumentException('Invalid package name');
		}

		$this->name = $name;
	}

	public function setTitle($title) {
		if (!is_string($title) || empty($title)) {
			throw new \InvalidArgumentException('Invalid package title');
		}

		$this->title = $title;
	}

	public function setSubtitle($subtitle) {
		if (!is_string($subtitle)) {
			throw new \InvalidArgumentException('Invalid package subtitle');
		}

		$this->subtitle = $subtitle;
	}

	public function setVersion($version) {
		if (!is_string($version) || empty($version)) {
			throw new \InvalidArgumentException('Invalid package version');
		}

		$this->version = $version;
	}

	public function setDescription($description) {
		if (!is_string($description) || empty($description)) {
			throw new \InvalidArgumentException('Invalid package short description');
		}

		$this->description = $description;
	}

	public function setUrl($url) {
		if (!is_string($url) || (!empty($url) && !preg_match('#https?://[a-z0-9._/-?&=%]+#i', $url))) {
			throw new \InvalidArgumentException('Invalid project url');
		}

		$this->url = $url;
	}

	public function setMaintainer($maintainer) {
		if (!is_string($maintainer) || empty($maintainer)) {
			throw new \InvalidArgumentException('Invalid package maintainer');
		}

		$this->maintainer = $maintainer;
	}

	public function setLicense($license) {
		if (!is_string($license) || empty($license)) {
			throw new \InvalidArgumentException('Invalid package license');
		}

		$this->license = $license;
	}

	public function setDepends($depends) {
		if (!is_string($depends)) {
			throw new \InvalidArgumentException('Invalid package depends');
		}

		$this->depends = $depends;
	}

	public function setSize($size) {
		if (!is_int($size) || $size < 0) {
			throw new \InvalidArgumentException('Invalid package size');
		}

		$this->size = $size;
	}

	public function setExtractedSize($extractedSize) {
		if (!is_int($extractedSize) || $extractedSize < 0) {
			throw new \InvalidArgumentException('Invalid package extracted size');
		}

		$this->extractedSize = $extractedSize;
	}

	public function setUpdateDate($updateDate) {
		if (!is_string($updateDate)) {
			throw new \InvalidArgumentException('Invalid package update date');
		}

		$this->updateDate = $updateDate;
	}

	public function setHasScripts($hasScripts) {
		if (!is_bool($hasScripts)) {
			throw new \InvalidArgumentException('Invalid package scripts indicator');
		}

		$this->hasScripts = $hasScripts;
	}

	// GETTERS //

	public function name() {
		return $this->name;
	}

	public function title() {
		return $this->title;
	}

	public function subtitle() {
		return $this->subtitle;
	}

	public function version() {
		return $this->version;
	}

	public function description() {
		return $this->description;
	}

	public function url() {
		return $this->url;
	}

	public function maintainer() {
		return $this->maintainer;
	}

	public function license() {
		return $this->license;
	}

	public function depends() {
		return $this->depends;
	}

	public function size() {
		return $this->size;
	}

	public function extractedSize() {
		return $this->extractedSize;
	}

	public function updateDate() {
		return $this->updateDate;
	}
	
	public function hasScripts() {
		return $this->hasScripts;
	}
}