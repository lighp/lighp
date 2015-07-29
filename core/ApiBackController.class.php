<?php

namespace core;

use core\apps\Application;
use core\responses\ApiResponse;

/**
 * An API back controller.
 * @author Simon Ser
 * @since 1.0alpha1
 */
abstract class ApiBackController extends BackController {
	/**
	 * Initialize the back controller.
	 * @param Application $app    The application.
	 * @param string      $module The module.
	 * @param string      $action The action.
	 */
	public function __construct(Application $app, $module, $action) {
		parent::__construct($app, $module, $action);

		$this->app()->httpResponse()->addHeader('Content-Type: application/json');

		$this->setResponseType('ApiResponse');
	}
}