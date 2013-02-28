<?php
namespace ctrl\backend\main;

class MainController extends \core\BackController {
	protected function _getBackend($module) {
		$backendPath = __DIR__.'/../../../etc/app/backend/';

		$metadataPath = $backendPath . '/' . $module . '/metadata.json';

		if (!file_exists($metadataPath)) {
			return;
		}

		$json = file_get_contents($metadataPath);
		if ($json === false) { return; }

		$metadata = json_decode($json, true);
		if ($metadata === null) { return; }

		$metadata['name'] = $module;
		$metadata['actions'] = array_values($metadata['actions']);

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

			$backend = $this->_getBackend($module);

			if ($backend !== null) {
				$backends[] = $backend;
			}
		}
		$dir->close();

		return $backends;
	}

	public function executeIndex(\core\HTTPRequest $request) {
		$backends = $this->_listBackends();

		$this->page->addVar('backends', $backends);
	}

	public function executeShowModule(\core\HTTPRequest $request) {
		$backend = $this->_getBackend($request->getData('name'));

		$this->page->addVar('backend', $backend);
	}

	public function executeSearch(\core\HTTPRequest $request) {
		$query = $request->getData('q');

		if (empty($query)) {
			$this->page->addVar('emptyQuery?', true);
			return;
		}

		$escapedQuery = preg_quote($query);

		$backends = $this->_listBackends();
		$matchingBackends = array();

		$nbrBackends = count($backends);
		$backendsHitsPower = strlen((string) $nbrBackends);
		$backendsHitsFactor = pow(10, $backendsHitsPower);

		foreach($backends as $i => $backend) {
			$backendHits = 0;

			$actions = array();

			$nbrActions = count($backend['actions']);
			$actionsHitsPower = strlen((string) $nbrActions);
			$actionsHitsFactor = pow(10, $actionsHitsPower);

			foreach ($backend['actions'] as $j => $action) {
				preg_match_all('#'.$escapedQuery.'#i', $action['title'], $matches);
				
				if (is_array($matches[0]) && count($matches[0]) > 0) {
					$actionHits = count($matches[0]);
					$backendHits += $actionHits;

					foreach ($matches[0] as $match) {
						$action['title'] = str_replace($match, '<strong>'.$match.'</strong>', $action['title']);
					}

					$actions[$actionHits * $actionsHitsFactor + ($nbrActions - $j)] = $action;
				}
			}

			if ($backendHits > 0) {
				krsort($actions);
				$backend['actions'] = array_values($actions);
				$backend['backendTitle'] = $backend['title']; //Rename backend title to manage duplicates with actions' titles

				$matchingBackends[$backendHits * $backendsHitsFactor + ($nbrBackends - $i)] = $backend;
			}
		}

		krsort($matchingBackends);
		$matchingBackends = array_values($matchingBackends);

		$this->page->addVar('backends', $matchingBackends);
	}
}