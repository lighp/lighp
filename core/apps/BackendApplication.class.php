<?php

namespace core\apps;

/**
 * A backend (where you can manage the framework).
 * @author Simon Ser
 * @since 1.0alpha1
 */
class BackendApplication extends Application {
	/**
	 * Initialize this backend.
	 */
	public function __construct() {
		parent::__construct();

		$this->name = 'backend';
	}

	public function run() {
		if ($this->user->isAdmin()) {
			$controller = $this->getController();
		} else {
			$controller = new \ctrl\backend\login\LoginController($this, 'login', 'index');
		}

		$controller->execute();

		$this->httpResponse->setContent($controller->page());
	}
}