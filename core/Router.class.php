<?php
namespace core;

/**
 * A router.
 * A router can determine the route matching to a given URL.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class Router {
	/**
	 * The routes.
	 * @var array
	 */
	protected $routes = array();

	/**
	 * Error code when no route is found.
	 */
	const NO_ROUTE = 1;

	/**
	 * Add a route to the router.
	 * @param Route $route The route to add.
	 */
	public function addRoute(Route $route) {
		if (!in_array($route, $this->routes)) {
			$this->routes[] = $route;
		}
	}

	/**
	 * Get the route associated to a given URL.
	 * @param  string $url The URL.
	 * @return Route       The route.
	 */
	public function getRoute($url) {
		foreach ($this->routes as $route) {
			//Si la route correspond à l'URL
			if (($varsValues = $route->match($url)) !== false) {
				// Si elle a des variables
				if ($route->hasVars()) {
					$varsNames = $route->varsNames();
					$listVars = array();

					foreach ($varsValues as $key => $match) {
						if (!empty($varsNames[$key])) {
							$listVars[$varsNames[$key]] = urldecode($match);
						}
					}

					$route->setVars($listVars);
				}

				return $route;
			}
		}

		throw new \RuntimeException('No route matches given URL', self::NO_ROUTE);
	}

	public function getUrl($module, $action, $vars = array()) {
		foreach ($this->routes as $route) {
			if ($route->module() == $module && $route->action() == $action) { //This route matches
				if ($route->hasVars()) {
					$route->setVars($vars);
				}

				return $route->buildUrl();
			}
		}

		throw new \RuntimeException('No route matches given module and action');
	}
}