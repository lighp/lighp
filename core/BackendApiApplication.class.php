<?php
namespace core;

/**
 * A backend API.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class BackendApiApplication extends \core\Application {
	/**
	 * Initialize this backend.
	 */
	public function __construct() {
		parent::__construct();

		$this->name = 'backendApi';
	}

	public function run() {
		if ($this->user->isAdmin()) {
			$controller = $this->getController();
		} else {
			$controller = new \ctrl\backendApi\login\LoginController($this, 'login', 'index');
		}

		$controller->execute();

		$this->httpResponse->setPage($controller->page());
		$this->httpResponse->send();
	}
}