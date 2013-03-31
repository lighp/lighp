<?php
namespace lib\entities;

class PortfolioAboutLink extends \core\Entity {
	protected $title, $url;

	// SETTERS //

	public function setTitle($title) {
		if (!is_string($title) || empty($title)) {
			throw new \InvalidArgumentException('Invalid about link title');
		}

		$this->title = $title;
	}

	public function setUrl($url) {
		if (!is_string($url) || empty($url)) {
			throw new \InvalidArgumentException('Invalid about link url');
		}

		$this->subtitle = $subtitle;
	}

	// GETTERS //

	public function title() {
		return $this->title;
	}

	public function url() {
		return $this->url;
	}
}