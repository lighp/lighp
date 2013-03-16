<?php
namespace lib\entities;

class PortfolioProject extends \core\Entity {
	protected $name, $title, $subtitle, $url, $shortDescription, $description, $hasImage;

	// SETTERS //

	public function setName($name) {
		if (!is_string($name) || empty($name) || !preg_match('#^[a-zA-Z0-9-_.]+$#', $name)) {
			throw new \InvalidArgumentException('Invalid project name');
		}

		$this->name = $name;
	}

	public function setTitle($title) {
		if (!is_string($title) || empty($title)) {
			throw new \InvalidArgumentException('Invalid project title');
		}

		$this->title = $title;
	}

	public function setSubtitle($subtitle) {
		if (!is_string($subtitle)) {
			throw new \InvalidArgumentException('Invalid project subtitle');
		}

		$this->subtitle = $subtitle;
	}

	public function setCategory($category) {
		if (!is_string($category) || empty($category)) {
			throw new \InvalidArgumentException('Invalid project category');
		}

		$this->category = $category;
	}

	public function setUrl($url) {
		if (!is_string($url) || (!empty($url) && !preg_match('#https?://[a-z0-9._/-?&=%]+#i', $url))) {
			throw new \InvalidArgumentException('Invalid project url');
		}

		$this->url = $url;
	}

	public function setShortDescription($shortDescription) {
		if (!is_string($shortDescription)) {
			throw new \InvalidArgumentException('Invalid project short description');
		}

		$this->shortDescription = $shortDescription;
	}

	public function setDescription($description) {
		if (!is_string($description)) {
			throw new \InvalidArgumentException('Invalid project description');
		}

		$this->description = $description;
	}

	public function setHasImage($hasImage) {
		if (!is_bool($hasImage)) {
			throw new \InvalidArgumentException('Invalid project image');
		}

		$this->hasImage = $hasImage;
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

	public function category() {
		return $this->category;
	}

	public function url() {
		return $this->url;
	}

	public function shortDescription() {
		return $this->shortDescription;
	}

	public function description() {
		return $this->description;
	}

	public function hasImage() {
		return ($this->hasImage) ? true : false;
	}
}