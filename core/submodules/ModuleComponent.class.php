<?php

namespace core\submodules;

use core\ApplicationComponent;
use core\apps\Application;

/**
 * A module's component.
 * @author Simon Ser
 * @since 1.0alpha1
 */
abstract class ModuleComponent extends ApplicationComponent {
	/**
	 * The associated module.
	 * @var string
	 */
	protected $module;

	/**
	 * Initialize the module's component.
	 * @param Application $app    The application.
	 * @param string      $module The module.
	 */
	public function __construct(Application $app, $module) {
		parent::__construct($app);

		$this->module = $module;
	}

	public function module() {
		return $this->module;
	}
}