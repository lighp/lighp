<?php
namespace core;

/**
 * A frontend API.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class FrontendApiApplication extends \core\Application {
	/**
	 * Initialize this frontend.
	 */
	public function __construct() {
		parent::__construct();

		$this->name = 'frontendApi';
	}

	public function run() {
		$controller = $this->getController();
		$controller->execute();

		$this->httpResponse->setContent($controller->responseContent());
	}
}