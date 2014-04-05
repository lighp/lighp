<?php

namespace core\responses;

use core\apps\Application;
use core\submodules\ModuleComponent;

/**
 * A response's content.
 * @author Simon Ser
 * @since 1.0alpha1
 */
abstract class ResponseContent extends ModuleComponent {
	/**
	 * The response's action.
	 * @var string
	 */
	protected $action;

	public function __construct(Application $app, $module, $action) {
		parent::__construct($app, $module);

		$this->setAction($action);
	}

	/**
	 * Set the response's action.
	 * @param string $action The action's name.
	 */
	public function setAction($action) {
		if (!is_string($action) || empty($action)) {
			throw new \InvalidArgumentException('Invalid action name');
		}

		$this->action = $action;
	}

	/**
	 * Get the response's action.
	 * @return string The action's name.
	 */
	public function action() {
		return $this->action;
	}

	/**
	 * Generate the response.
	 * @return string The generated response.
	 */
	abstract public function generate();
}