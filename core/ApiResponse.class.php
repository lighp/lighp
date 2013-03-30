<?php
namespace core;

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
		return '...';
	}
}