<?php
namespace lib\entities;

class PortfolioLeadingItem extends \core\Entity {
	protected $name, $kind, $place;

	// SETTERS //

	public function setName($name) {
		if (!is_string($name) || empty($name) || !preg_match('#^[a-zA-Z0-9-_.]+$#', $name)) {
			throw new \InvalidArgumentException('Invalid leading item name');
		}

		$this->name = $name;
	}

	public function setKind($kind) {
		if (!is_string($kind) || empty($kind) || !preg_match('#^[a-zA-Z]+$#', $kind)) {
			throw new \InvalidArgumentException('Invalid leading item kind');
		}

		$this->kind = $kind;
	}

	public function setPlace($place) {
		if (!is_string($place) || empty($place) || !preg_match('#^[a-zA-Z]+$#', $place)) {
			throw new \InvalidArgumentException('Invalid leading item kind');
		}

		$this->place = $place;
	}

	// GETTERS //

	public function name() {
		return $this->name;
	}

	public function kind() {
		return $this->kind;
	}

	public function place() {
		return $this->place;
	}
}