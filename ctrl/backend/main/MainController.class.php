<?php

namespace ctrl\backend\main;

use core\http\HTTPRequest;

class MainController extends \core\BackController {
	protected function _buildAction($module, $actionName, $actionData) {
		$router = $this->app->router();

		try {
			if (isset($actionData['list'])) {
				$actionUrl = $router->getUrl($this->module(), 'showAction', array(
					'module' => $module,
					'action' => $actionName
				));
			} else {
				$actionUrl = $router->getUrl($module, $actionName);
			}
		} catch(\RuntimeException $e) {
			$actionUrl = '#';
		}

		$metadata = array(
			'name' => $actionName,
			'title' => $actionData['title'],
			'url' => $actionUrl
		);

		if (isset($actionData['list'])) {
			$metadata['list'] = $actionData['list'];
		}

		return $metadata;
	}

	protected function _getAction($module, $actionName) {
		$metadataPath = __DIR__.'/../../../etc/app/backend/' . $module . '/metadata.json';

		if (!file_exists($metadataPath)) {
			return;
		}

		$conf = new \core\Config($metadataPath);
		$metadata = $conf->read();

		return $this->_buildAction($module, $actionName, $metadata['actions'][$actionName]);
	}

	protected function _getBackendMetadata($module) {
		$backendPath = __DIR__.'/../../../etc/app/backend/';
		$metadataPath = $backendPath . '/' . $module . '/metadata.json';

		if (!file_exists($metadataPath)) {
			return;
		}

		$conf = new \core\Config($metadataPath);
		$metadata = $conf->read();

		$metadata['name'] = $module;

		return $metadata;
	}

	protected function _getBackend($module) {
		$metadata = $this->_getBackendMetadata($module);

		if (empty($metadata)) {
			return;
		}

		$router = $this->app->router();
		$actions = array();

		if (isset($metadata['actions']) && is_array($metadata['actions'])) {
			foreach($metadata['actions'] as $actionName => $actionData) {
				$actions[] = $this->_buildAction($module, $actionName, $actionData);
			}
		}
		$metadata['actions'] = $actions;

		return $metadata;
	}

	protected function _listBackends() {
		$backendPath = __DIR__.'/../../../etc/app/backend/';
		$dir = dir($backendPath);

		if ($dir === false) {
			throw new \RuntimeException('Failed to open backend directory "'.$backendPath.'"');
		}

		$backends = array();

		while (false !== ($module = $dir->read())) {
			if (in_array($module, array('.', '..', 'main'))) {
				continue;
			}
			if (!is_dir($backendPath . '/' . $module)) {
				continue;
			}

			$backend = $this->_getBackendMetadata($module);

			if ($backend !== null) {
				$backends[] = $backend;
			}
		}
		$dir->close();

		return $backends;
	}

	protected function _listItems($moduleName, $itemsToList, $searchQuery = '') {
		$request = $this->app->httpRequest();

		$ctrl = $this->app->buildController($moduleName);
		$methodName = 'list' . ucfirst($itemsToList);

		if (method_exists($ctrl, $methodName)) {
			$items = $ctrl->$methodName($request);

			//Search query
			if (!empty($searchQuery)) {
				$searcher = new \lib\ArraySearcher($items);
				$items = $searcher->search($searchQuery, array('title', 'shortDescription'));
			}

			return $items;
		}
	}

	protected function _insertUrlToItems($moduleName, $actionName, $items) {
		$router = $this->app->router();
		$route = $router->getRoute($moduleName, $actionName);

		foreach($items as $key => $item) {
			if (isset($item['vars']) && $route->hasVars()) {
				if (isset($action['list']['vars'])) {
					$routeVarsNames = $route->varsNames();
					$joinVarsNames = $action['list']['vars'];
					$itemsVars = $item['vars'];
					$vars = array();

					foreach($routeVarsNames as $routeVarName) {
						if (isset($joinVarsNames[$routeVarName])) {
							$itemVarName = $joinVarsNames[$routeVarName];
							$vars[$routeVarName] = $itemsVars[$itemVarName];
						}
					}
				} else {
					$vars = $item['vars'];
				}

				$route->setVars($vars);
			}

			$items[$key]['url'] = $route->buildUrl();
		}

		return $items;
	}

	public function executeIndex(HTTPRequest $request) {
		$this->page()->addVar('title', 'Espace d\'administration');

		$searchQuery = ($request->getExists('q')) ? trim($request->getData('q')) : null;

		$this->page()->addVar('searchQuery', $searchQuery);

		$router = $this->app->router();
		$backends = $this->_listBackends();

		if (empty($searchQuery)) {
			foreach ($backends as $key => $metadata) {
				$backends[$key] = $this->_getBackend($metadata['name']);

				$backends[$key]['url'] = $router->getUrl($this->module(), 'showModule', array(
					'module' => $metadata['name']
				));
			}

			$this->page()->addVar('backends', $backends);
		} else {
			$actions = array();

			foreach($backends as $backendMetadata) {
				$backend = $this->_getBackend($backendMetadata['name']);

				foreach ($backend['actions'] as $action) {
					$action['title'] = $backend['title'] . ' : ' . $action['title'];
					$action['backend'] = $backend['name'];

					if (isset($backend['icon'])) {
						$action['icon'] = $backend['icon'];
					}

					$actions[] = $action;
				}
			}

			$searcher = new \lib\ArraySearcher($actions);
			$actions = $searcher->search($searchQuery, array('title', 'backend', 'name'));

			$this->page()->addVar('actions', $actions);
		}
	}

	public function executeShowModule(HTTPRequest $request) {
		$this->page()->addVar('title', 'Afficher un module');
		$this->page()->addVar('breadcrumb', array(
			array()
		));

		$moduleName = $request->getData('module');
		$searchQuery = ($request->getExists('q')) ? trim($request->getData('q')) : null;

		$backend = $this->_getBackend($moduleName);

		$this->page()->addVar('searchQuery', $searchQuery);

		if (!empty($backend)) {
			if (isset($backend['actions'])) {
				$lists = array();
				foreach($backend['actions'] as $i => $action) {
					if (isset($action['list']) && isset($action['list']['items'])) {
						$itemsName = $action['list']['items'];

						if (isset($lists[$itemsName])) {
							$lists[$itemsName][] = $i;
						} else {
							$lists[$itemsName] = array($i);
						}
					}
				}

				$items = array();
				$itemsLimit = 10;
				foreach($lists as $listName => $actionIndexes) {
					$list = $this->_listItems($moduleName, $listName, $searchQuery);
					$kind = array('title' => null);
					if (isset($backend['lists']) && isset($backend['lists'][$listName])) {
						$kind = $backend['lists'][$listName];
					}

					foreach($actionIndexes as $actionIndex) {
						$actionList = array();
						$action = $backend['actions'][$actionIndex];

						$actionList = $this->_insertUrlToItems($moduleName, $action['name'], $list);
						foreach ($actionList as $itemName => $item) {
							if (!isset($list[$itemName]['actions'])) {
								$list[$itemName]['actions'] = array();
							}

							$list[$itemName]['actions'][] = array(
								'action' => $action,
								'url' => $item['url']
							);
							$list[$itemName]['kind'] = array(
								'name' => $listName,
								'title' => $kind['title']
							);
						}
					}

					$list = array_slice(array_reverse($list), 0, $itemsLimit);
					$items = array_merge($items, $list);
				}

				$backend['items'] = $items;
			}

			if (!empty($searchQuery) && isset($backend['actions'])) {
				$searcher = new \lib\ArraySearcher($backend['actions']);
				$backend['actions'] = $searcher->search($searchQuery, array('title', 'name'));
			}

			$this->page()->addVar('backend', $backend);
			$this->page()->addVar('title', $backend['title']);
			$this->page()->addVar('breadcrumb', array(
				array('title' => $backend['title'])
			));
		}
	}

	public function executeShowAction(HTTPRequest $request) {
		$this->page()->addVar('title', 'Afficher une action');
		$this->page()->addVar('breadcrumb', array(
			array()
		));

		$moduleName = $request->getData('module');
		$actionName = $request->getData('action');
		$searchQuery = ($request->getExists('q')) ? trim($request->getData('q')) : null;

		$backend = $this->_getBackendMetadata($moduleName);
		$action = $this->_getAction($moduleName, $actionName);

		$this->page()->addVar('searchQuery', $searchQuery);

		if (!empty($backend) && !empty($action)) {
			$this->page()->addVar('backend', $backend);
			$this->page()->addVar('action', $action);
			$this->page()->addVar('title', $action['title']);
			$this->page()->addVar('breadcrumb', array(
				array(
					'title' => $backend['title'],
					'url' => $this->app->router()->getUrl($this->module(), 'showModule', array(
						'module' => $moduleName
					))
				),
				array('title' => $action['title'])
			));

			if (isset($action['list'])) {
				if (!isset($action['list']['items'])) {
					return;
				}

				$itemsToList = $action['list']['items'];

				$ctrl = $this->app->buildController($moduleName, $actionName);
				$methodName = 'list' . ucfirst($itemsToList);

				if (method_exists($ctrl, $methodName)) {
					$items = $ctrl->$methodName($request);

					$router = $this->app->router();
					$route = $router->getRoute($moduleName, $actionName);

					foreach($items as $key => $item) {
						if (isset($item['vars']) && $route->hasVars()) {
							if (isset($action['list']['vars'])) {
								$routeVarsNames = $route->varsNames();
								$joinVarsNames = $action['list']['vars'];
								$itemsVars = $item['vars'];
								$vars = array();

								foreach($routeVarsNames as $routeVarName) {
									if (isset($joinVarsNames[$routeVarName])) {
										$itemVarName = $joinVarsNames[$routeVarName];
										$vars[$routeVarName] = $itemsVars[$itemVarName];
									}
								}
							} else {
								$vars = $item['vars'];
							}

							$route->setVars($vars);
						}

						$items[$key]['url'] = $route->buildUrl();
					}

					//Search query
					if (!empty($searchQuery)) {
						$searcher = new \lib\ArraySearcher($items);
						$items = $searcher->search($searchQuery, array('title', 'shortDescription'));
					}

					$this->page()->addVar('items', $items);
				}
			}
		}
	}
}