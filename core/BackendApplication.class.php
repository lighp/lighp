<?php
namespace core;

class BackendApplication extends \core\Application {
	public function __construct() {
		parent::__construct();

		$this->name = 'backend';
	}

	public function run() {
		//TODO: check authentification
		if (true) {
			$controller = $this->getController();
		} else {
			$controller = new \ctrl\backend\login\LoginController($this, 'login', 'index');
		}

		$controller->execute();

		$this->httpResponse->setPage($controller->page());
		$this->httpResponse->send();
	}
}