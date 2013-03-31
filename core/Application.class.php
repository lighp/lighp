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
	 * The user.
	 * @var User
	 */
	protected $user;

	/**
	 * The router.
	 * @var Router
	 */
	protected $router;

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

		$this->user = new User($this);
	}

	/**
	 * Get the back controller matching with the HTTP request.
	 * @return BackController The controller.
	 */
	public function getController() {
		$router = $this->router();

		$requestURI = $this->httpRequest->requestURI();
		$websiteConfigFile = new Config(__DIR__.'/../etc/core/website.json');
		$websiteConfig = $websiteConfigFile->read();
		$requestURI = preg_replace('#^'.preg_quote($websiteConfig['root']).'#', '$1', $requestURI);

		try { //Let's get the route matching with the URL
			$matchedRoute = $router->getRouteFromUrl($requestURI);
		} catch (\RuntimeException $e) {
			if ($e->getCode() == Router::NO_ROUTE) { //No route matching, the page doesn't exist
				$this->httpResponse->redirect404($this);
			}
		}

		//Add variables to the $_GET array
		$_GET = array_merge($_GET, $matchedRoute->vars());

		//And then create the controller
		return $this->buildController($matchedRoute->module(), $matchedRoute->action());
	}

	public function buildController($module, $action) {
		$controllerClass = 'ctrl\\'.$this->name.'\\'.$module.'\\'.ucfirst($module).'Controller';

		return new $controllerClass($this, $module, $action);
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
	 * Get the user.
	 * @return User
	 */
	public function user() {
		return $this->user;
	}

	/**
	 * Get the router.
	 * @return Router The router.
	 */
	public function router() {
		if (empty($this->router)) {
			$router = new Router;

			$configPath = __DIR__ . '/../etc/app/' . $this->name;
			$dir = opendir($configPath);

			if ($dir === false) {
				throw new \RuntimeException('Failed to open config directory "'.$configPath.'"');
			}

			$modules = array();

			while (false !== ($module = readdir($dir))) {
				// Notice that "." is a valid module name
				// In fact, you can create a routes file like this : $configPath.'/routes.json'
				if ($module == '..') {
					continue;
				}

				if (!is_dir($configPath . '/' . $module)) {
					continue;
				}

				$modules[] = $module;
			}
			closedir($dir);

			//Sorting modules is important :
			//"." must be at the begining, because it has the greatest priority 
			sort($modules);

			foreach($modules as $module) {
				$routesPath = $configPath . '/' . $module . '/routes.json';

				if (file_exists($routesPath)) {
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

			$this->router = $router;
		}

		return $this->router;
	}

	/**
	 * Get this application's name.
	 * @return string
	 */
	public function name() {
		return $this->name;
	}
}