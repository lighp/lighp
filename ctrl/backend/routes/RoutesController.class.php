<?php

namespace ctrl\backend\routes;

use core\http\HTTPRequest;

class RoutesController extends \core\BackController {
	protected function _addBreadcrumb($page = array()) {
		$breadcrumb = array(
			array(
				'url' => $this->app->router()->getUrl('main', 'showModule', array(
					'module' => $this->module()
				)),
				'title' => 'Routes'
			)
		);

		$this->page()->addVar('breadcrumb', array_merge($breadcrumb, array($page)));
	}

	protected function _listRoutes($appName) {
		$routes = array();

		$configPath = __DIR__ . '/../../../etc/app/' . $appName;
		$dir = dir($configPath);

		if ($dir === false) {
			throw new \RuntimeException('Failed to open config directory "'.$configPath.'"');
		}

		while (false !== ($module = $dir->read())) {
			if ($module == '..') {
				continue;
			}

			$modulePath = $configPath . '/' . $module;
			$routesPath = $modulePath . '/routes.json';
			if (is_dir($modulePath) && file_exists($routesPath)) {
				$json = file_get_contents($routesPath);
				if ($json === false) { continue; }

				$moduleRoutes = json_decode($json, true);
				if ($moduleRoutes === null) { continue; }

				if ($module == '.') { $module = ''; }

				$routes[$module] = $moduleRoutes;
			}
		}
		$dir->close();

		return $routes;
	}

	public function executeListRoutes(HTTPRequest $request) {
		$this->page()->addVar('title', 'G&eacute;rer une route');
		$this->_addBreadcrumb();

		$app = 'frontend';
		$routes = $this->_listRoutes($app);

		$tplRoutes = array();

		foreach($routes as $moduleName => $moduleRoutes) {
			foreach($moduleRoutes as $id => $route) {
				if (!empty($moduleName)) {
					$route['module'] = $moduleName;
					$route['editable?'] = false;
				} else {
					$route['editable?'] = true;
				}

				$route['id'] = $id;
				$route['app'] = $app;
				$route['varsList'] = implode(',', (isset($route['vars'])) ? $route['vars'] : array());

				$tplRoutes[] = $route;
			}
		}

		$this->page()->addVar('routes', $tplRoutes);
	}

	public function executeInsertRoute(HTTPRequest $request) {
		$this->page()->addVar('title', 'Ajouter une route');
		$this->_addBreadcrumb();

		if ($request->postExists('route-url')) {
			$routeApp = $request->postData('route-app');
			$routeVarsList = $request->postData('route-vars');
			$routeVars = explode(',', $routeVarsList);
			foreach ($routeVars as $i => $var) {
				$routeVars[$i] = trim($var);
			}

			$route = array(
				'url' => $request->postData('route-url'),
				'module' => $request->postData('route-module'),
				'action' => $request->postData('route-action'),
				'vars' => $routeVars
			);

			$this->page()->addVar('route', $route);
			$this->page()->addVar('routeApp', $routeApp);
			$this->page()->addVar('routeVarsList', $routeVarsList);

			$configPath = __DIR__ . '/../../../etc/app/' . $routeApp . '/routes.json';

			try {
				$conf = new \core\Config($configPath);

				$routes = $conf->read();

				$routes[] = $route;

				$conf->write($routes);
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('inserted?', true);
		}
	}

	public function executeDeleteRoute(HTTPRequest $request) {
		$this->page()->addVar('title', 'Supprimer une route');
		$this->_addBreadcrumb();

		$routeApp = $request->getData('app');
		$routeId = (int) $request->getData('id');

		$configPath = __DIR__ . '/../../../etc/app/' . $routeApp . '/routes.json';

		try {
			$conf = new \core\Config($configPath);

			$routes = $conf->read();

			foreach ($routes as $id => $route) {
				if ($id == $routeId) {
					unset($routes[$id]);
					break;
				}
			}

			$conf->write($routes);
		} catch(\Exception $e) {
			$this->page()->addVar('error', $e->getMessage());
			return;
		}

		$this->page()->addVar('deleted?', true);
	}
}