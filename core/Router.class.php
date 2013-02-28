<?php
namespace core;

class Router {
	protected $routes = array();

	const NO_ROUTE = 1;

	public function addRoute(Route $route) {
		if (!in_array($route, $this->routes)) {
			$this->routes[] = $route;
		}
	}

	public function getRoute($url) {
		foreach ($this->routes as $route) {
			//Si la route correspond à l'URL
			if (($varsValues = $route->match($url)) !== false) {
				// Si elle a des variables
				if ($route->hasVars()) {
					$varsNames = $route->varsNames();
					$listVars = array();

					foreach ($varsValues as $key => $match) {
						$listVars[$varsNames[$key]] = $match;
					}

					$route->setVars($listVars);
				}

				return $route;
			}
		}

		throw new \RuntimeException('No route matches given URL', self::NO_ROUTE);
	}
}