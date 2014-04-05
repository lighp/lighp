<?php

namespace core\apps;

/**
 * A frotend (the public part).
 * @author Simon Ser
 * @since 1.0alpha1
 */
class FrontendApplication extends Application {
	/**
	 * Initialize this frontend.
	 */
	public function __construct() {
		parent::__construct();

		$this->name = 'frontend';
	}

	public function run() {
		$controller = $this->getController();
		$controller->execute();

		$this->httpResponse->setContent($controller->page());
	}
}