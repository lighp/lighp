<?php
namespace core;

class FrontendApplication extends \core\Application {
	public function __construct() {
		parent::__construct();

		$this->name = 'frontend';
	}

	public function run() {
		$controller = $this->getController();
		$controller->execute();

		$this->httpResponse->setPage($controller->page());
		$this->httpResponse->send();
	}
}