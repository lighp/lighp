<?php
namespace core;

/**
 * An application (e.g. frontend/backend).
 * @author Simon Ser
 * @since 1.0alpha1
 */
abstract class Application {
	/**
	 * The HTTP request.
	 * @var HTTPRequest
	 */
	protected $httpRequest;

	/**
	 * The HTTP response.
	 * @var HTTPResponse
	 */
	protected $httpResponse;

	/**
	 * The application's name.
	 * @var string
	 */
	protected $name;

	/**
	 * Initialize a new application.
	 */
	public function __construct() {
		$this->httpRequest = new HTTPRequest;
		$this->httpResponse = new HTTPResponse;
	}

	/**
	 * Get the back controller matching with the HTTP request.
	 * @return BackController The controller.
	 */
	public function getController() {
		$router = new Router;

		$configPath = __DIR__ . '/../etc/app/' . $this->name;
		$dir = dir($configPath);

		if ($dir === false) {
			throw new \RuntimeException('Failed to open config directory "'.$configPath.'"');
		}

		while (false !== ($module = $dir->read())) {
			// Notice that "." is a valid module name
			// In fact, you can create a routes file like this : $configPath.'/routes.json'
			if ($module == '..') {
				continue;
			}

			$modulePath = $configPath . '/' . $module;
			$routesPath = $modulePath . '/routes.json';
			if (is_dir($modulePath) && file_exists($routesPath)) {
				$json = file_get_contents($routesPath);
				if ($json === false) { continue; }

				$routes = json_decode($json, true);
				if ($routes === null) { continue; }

				foreach ($routes as $route) {
					$varsNames = (isset($route['vars']) && is_array($route['vars'])) ? $route['vars'] : array();

					if ($module == '.') { //Global route
						$routeModule = $route['module'];
					} else {
						$routeModule = $module;
					}

					$router->addRoute(new Route($route['url'], $routeModule, $route['action'], $varsNames));
				}
			}
		}
		$dir->close();

		try { //Let's get the route matching with the URL
			$matchedRoute = $router->getRoute($this->httpRequest->requestURI());
		} catch (\RuntimeException $e) {
			if ($e->getCode() == Router::NO_ROUTE) { //No route matching, the page doesn't exist
				$this->httpResponse->redirect404($this);
			}
		}

		//Add variables to the $_GET array
		$_GET = array_merge($_GET, $matchedRoute->vars());

		//And then create the controller
		$controllerClass = 'ctrl\\'.$this->name.'\\'.$matchedRoute->module().'\\'.ucfirst($matchedRoute->module()).'Controller';
		return new $controllerClass($this, $matchedRoute->module(), $matchedRoute->action());
	}

	/**
	 * Launch the application.
	 */
	abstract public function run();

	/**
	 * Get the HTTP request.
	 * @return HTTPRequest
	 */
	public function httpRequest() {
		return $this->httpRequest;
	}

	/**
	 * Get the HTTP response.
	 * @return HTTPResponse
	 */
	public function httpResponse() {
		return $this->httpResponse;
	}

	/**
	 * Get this application's name.
	 * @return string
	 */
	public function name() {
		return $this->name;
	}
}