<?php
namespace core;
 
abstract class Application {
	protected $httpRequest;
	protected $httpResponse;
	protected $name;

	public function __construct() {
		$this->httpRequest = new HTTPRequest;
		$this->httpResponse = new HTTPResponse;
	}

	public function getController() {
		$router = new Router;

		$configPath = __DIR__ . '/../etc/app/' . $this->name;
		$dir = dir($configPath);

		if ($dir === false) {
			throw new \RuntimeException('Failed to open config directory "'.$configPath.'"');
		}

		while (false !== ($module = $dir->read())) {
			if ($module == '.' || $module == '..') {
				continue;
			}

			$routesPath = $configPath . '/' . $module . '/routes.json';
			if (file_exists($routesPath)) {
				$json = file_get_contents($routesPath);
				if ($json === false) { continue; }

				$routes = json_decode($json, true);
				if ($routes === null) { continue; }

				foreach ($routes as $route) {
					$varsNames = (isset($route['vars']) && is_array($route['vars'])) ? $route['vars'] : array();
					$router->addRoute(new Route($route['url'], $module, $route['action'], $varsNames));
				}
			}
		}
		$dir->close();

		try { //On récupère la route correspondante à l'URL
			$matchedRoute = $router->getRoute($this->httpRequest->requestURI());
		} catch (\RuntimeException $e) {
			if ($e->getCode() == Router::NO_ROUTE) { //Si aucune route ne correspond, c'est que la page demandée n'existe pas
				$this->httpResponse->redirect404($this);
			}
		}

		//On ajoute les variables de l'URL au tableau $_GET
		$_GET = array_merge($_GET, $matchedRoute->vars());

		//On instancie le contrôleur
		$controllerClass = 'ctrl\\'.$this->name.'\\'.$matchedRoute->module().'\\'.ucfirst($matchedRoute->module()).'Controller';
		return new $controllerClass($this, $matchedRoute->module(), $matchedRoute->action());
	}

	abstract public function run();

	public function httpRequest() {
		return $this->httpRequest;
	}

	public function httpResponse() {
		return $this->httpResponse;
	}

	public function name() {
		return $this->name;
	}
}