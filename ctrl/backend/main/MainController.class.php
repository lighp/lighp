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
		$query = $request->getData('query');
		$this->page->addVar('searchQuery', $query);

		$module = $request->getData('module');
		if (!empty($module)) {
			$backend = $this->_getBackend($module);
			$this->page->addVar('backend', $backend);
		}

		if (empty($query)) {
			$this->page->addVar('emptyQuery?', true);
			return;
		}

		$escapedQuery = preg_quote($query);

		if (!empty($module)) {
			$backends = array($backend);
		} else {
			$backends = $this->_listBackends();
		}
		
		$matchingActions = array();

		$nbrActions = 0;
		foreach($backends as $i => $backend) {
			$nbrActions += count($backend['actions']);
		}
		$actionsHitsPower = strlen((string) $nbrActions);
		$actionsHitsFactor = pow(10, $actionsHitsPower);

		$i = 0;
		foreach($backends as $backend) {
			$backend['title'] = preg_replace('#('.$escapedQuery.')#i', '<strong>$1</strong>', $backend['title'], -1, $backendHits);

			foreach ($backend['actions'] as $action) {
				$action['title'] = preg_replace('#('.$escapedQuery.')#i', '<strong>$1</strong>', $action['title'], -1, $actionHits);

				$totalHits = $backendHits + $actionHits;
				if ($totalHits > 0) {
					$action['backend'] = $backend;
					$matchingActions[$totalHits * $actionsHitsFactor + ($nbrActions - $i)] = $action;
				}

				$i++;
			}
		}

		krsort($matchingActions);
		$matchingActions = array_values($matchingActions);

		$this->page->addVar('actions', $matchingActions);
	}
}