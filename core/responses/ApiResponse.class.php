<?php

namespace core\responses;

/**
 * An API response.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class ApiResponse extends ResponseContent {
	/**
	 * The response's data.
	 * @var array
	 */
	protected $data = array();

	public function generate() {
		return json_encode($this->data);
	}

	public function data() {
		return $this->data;
	}

	public function setData(array $data) {
		$this->data = $data;
	}
}