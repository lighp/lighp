<?php
namespace core;

abstract class Entity implements \ArrayAccess {
	protected $id;

	public function __construct($data = array()) {
		if (!$data instanceof \Traversable && !is_array($data)) {
			throw new \InvalidArgumentException('Invalid data : variable must be an array or traversable');
		}

		if (!empty($data)) {
			$this->hydrate($data);
		}
	}

	public function isNew() {
		return empty($this->id);
	}

	public function id() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = (int) $id;
	}

	public function hydrate($data) {
		if (!$data instanceof \Traversable && !is_array($data)) {
			throw new \InvalidArgumentException('Invalid data : variable must be an array or traversable');
		}

		foreach ($data as $key => $value) {
			$method = 'set'.ucfirst($key);

			if (is_callable(array($this, $method))) {
				$this->$method($value);
			}
		}
	}

	public function offsetGet($var) {
		if (isset($this->$var) && is_callable(array($this, $var))) {
			return $this->$var();
		}
	}

	public function offsetSet($var, $value) {
		$method = 'set'.ucfirst($var);

		if (isset($this->$var) && is_callable(array($this, $method))) {
			$this->$method($value);
		}
	}

	public function offsetExists($var) {
		return isset($this->$var) && is_callable(array($this, $var));
	}

	public function offsetUnset($var) {
		throw new \Exception('Cannot delete any field');
	}

	public function toArray() {
		$data = array();

		foreach(get_object_vars($this) as $key => $value) {
			if (isset($this[$key])) {
				$data[$key] = $this[$key];
			}
		}

		return $data;
	}
}