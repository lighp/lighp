<?php
namespace ctrl\backend\main;

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

			$backend = $this->_getBackendMetadata($module);

			if ($backend !== null) {
				$backends[] = $backend;
			}
		}
		$dir->close();

		return $backends;
	}

	protected function _searchItems($query, array $items, array $searchFields = null) {
		$accentChars = array(
			'A|À|Á|Â|Ã|Ä|Å',
			'a|à|á|â|ã|ä|å',
			'O|Ò|Ó|Ô|Õ|Ö|Ø',
			'o|ò|ó|ô|õ|ö|ø',
			'E|È|É|Ê|Ë',
			'e|é|è|ê|ë',
			'C|Ç',
			'c|ç',
			'I|Ì|Í|Î|Ï',
			'i|ì|í|î|ï',
			'U|Ù|Ú|Û|Ü',
			'u|ù|ú|û|ü',
			'y|ÿ',
			'N|Ñ',
			'n|ñ'
		);

		$escapedQuery = preg_quote($query);
		$escapedQuery = str_replace(' ', '|', $escapedQuery);
		foreach ($accentChars as $chars) {
			$regex = '('.$chars.')';
			$escapedQuery = preg_replace('#'.$regex.'#', $regex, $escapedQuery);
		}

		$matchingItems = array();

		$nbrFields = count($searchFields);

		$nbrItems = count($items);
		$hitsPower = strlen((string) $nbrItems);
		$hitsFactor = pow(10, $hitsPower);

		$i = 0;
		foreach($items as $item) {
			$itemHits = 0;

			$j = 0;
			foreach($searchFields as $field) {
				if (isset($item[$field])) {
					$item[$field] = preg_replace('#('.$escapedQuery.')#i', '<strong>$1</strong>', $item[$field], -1, $fieldHits);
					$itemHits += $fieldHits * ($nbrFields - $j);
				}

				$j++;
			}

			if ($itemHits > 0) {
				$matchingItems[$itemHits * $hitsFactor + ($nbrItems - $i)] = $item;
			}

			$i++;
		}

		krsort($matchingItems);
		$matchingItems = array_values($matchingItems);

		return $matchingItems;
	}

	public function executeIndex(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Espace d\'administration');

		$searchQuery = ($request->getExists('q')) ? trim($request->getData('q')) : null;

		$this->page()->addVar('searchQuery', $searchQuery);

		$router = $this->app->router();
		$backends = $this->_listBackends();

		if (empty($searchQuery)) {
			foreach ($backends as $key => $metadata) {
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

					if (isset($backend['icon'])) {
						$action['icon'] = $backend['icon'];
					}

					$actions[] = $action;
				}
			}

			$actions = $this->_searchItems($searchQuery, $actions, array('title'));

			$this->page()->addVar('actions', $actions);
		}
	}

	public function executeShowModule(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Afficher un module');
		$this->page()->addVar('breadcrumb', array(
			array()
		));

		$moduleName = $request->getData('module');
		$searchQuery = ($request->getExists('q')) ? trim($request->getData('q')) : null;

		$backend = $this->_getBackend($moduleName);

		$this->page()->addVar('searchQuery', $searchQuery);

		if (!empty($searchQuery) && isset($backend['actions'])) {
			$backend['actions'] = $this->_searchItems($searchQuery, $backend['actions'], array('title'));
		}

		if (!empty($backend)) {
			$this->page()->addVar('backend', $backend);
			$this->page()->addVar('title', $backend['title']);
			$this->page()->addVar('breadcrumb', array(
				array('title' => $backend['title'])
			));
		}
	}

	public function executeShowAction(\core\HTTPRequest $request) {
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
						$items = $this->_searchItems($searchQuery, $items, array('title', 'shortDescription'));
					}

					$this->page()->addVar('items', $items);
				}
			}
		}
	}
}