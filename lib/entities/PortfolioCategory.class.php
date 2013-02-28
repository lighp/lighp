<?php
namespace lib\entities;

class PortfolioCategory extends \core\Entity {
	protected $name, $title, $subtitle, $shortDescription;

	// SETTERS //

	public function setName($name) {
		if (!is_string($name) || empty($name) || !preg_match('#^[a-zA-Z0-9-_.]+$#', $name)) {
			throw new \InvalidArgumentException('Invalid category name');
		}

		$this->name = $name;
	}

	public function setTitle($title) {
		if (!is_string($title) || empty($title)) {
			throw new \InvalidArgumentException('Invalid category title');
		}

		$this->title = $title;
	}

	public function setSubtitle($subtitle) {
		if (!is_string($subtitle) || empty($subtitle)) {
			throw new \InvalidArgumentException('Invalid category subtitle');
		}

		$this->subtitle = $subtitle;
	}

	public function setShortDescription($shortDescription) {
		if (!is_string($shortDescription) || empty($shortDescription)) {
			throw new \InvalidArgumentException('Invalid category short description');
		}

		$this->shortDescription = $shortDescription;
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

	public function shortDescription() {
		return $this->shortDescription;
	}
}