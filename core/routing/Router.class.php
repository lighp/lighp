<?php

namespace core\routing;

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
	 * @param  string $url         The URL.
	 * @param  bool   $forceDirect True to force the route to be direct (no redirection).
	 * @return Route               The route.
	 */
	public function getRouteFromUrl($url, $forceDirect = false) {
		foreach ($this->routes as $route) {
			if ($forceDirect && $route->redirect()) {
				continue;
			}

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

	/**
	 * Get the route corresponding to a module and an action.
	 * @param  string $module The module name.
	 * @param  string $action The action name.
	 * @return Route          The route.
	 */
	public function getRoute($module, $action) {
		foreach ($this->routes as $route) {
			if ($route->redirect()) {
				continue;
			}

			if ($route->module() == $module && $route->action() == $action) { //This route matches
				return $route;
			}
		}

		throw new \RuntimeException('No route matches given module and action', self::NO_ROUTE);
	}

	/**
	 * Get the URL corresponding to a module and an action.
	 * @param  string $module The module name.
	 * @param  string $action The action name.
	 * @param  array  $vars   The route variables.
	 * @return string         The URL.
	 */
	public function getUrl($module, $action, $vars = array()) {
		$route = $this->getRoute($module, $action);

		if ($route->hasVars()) {
			$route->setVars($vars);
		}

		return $route->buildUrl();
	}
}