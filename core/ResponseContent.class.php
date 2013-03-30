<?php
namespace core;

/**
 * A response's content.
 * @author Simon Ser
 * @since 1.0alpha1
 */
abstract class ResponseContent extends ApplicationComponent {
	/**
	 * Generate the response.
	 * @return string The generated response.
	 */
	abstract public function generate();
}