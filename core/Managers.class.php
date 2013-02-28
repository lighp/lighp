<?php
namespace core;

class Managers {
	protected $config;

	protected $daos;

	protected $managers = array();

	public function __construct(Daos $daos) {
		$this->daos = $daos;
	}

	protected function _getApiOf($module) {
		if (empty($this->config)) {
			$configPath = __DIR__.'/../etc/core/managers.json';
			$json = file_get_contents($configPath);

			if ($json === false) {
				throw new \RuntimeException('Unable to load configuration file "'.$configPath.'"');
			}

			$this->config = json_decode($json, true);
		}

		$api = null;
		
		if (isset($this->config[$module])) {
			$api = $this->config[$module];
		}

		return $api;
	}

	public function getManagerOf($module) {
		if (!is_string($module) || empty($module)) {
			throw new \InvalidArgumentException('Invalid module name');
		}

		if (!isset($this->managers[$module])) {
			$api = $this->_getApiOf($module);
			if (empty($api)) {
				$api = $this->daos->getDefaultApi();
			}

			$dao = $this->daos->getDao($api);

			$manager = '\\lib\\manager\\'.ucfirst($module).'Manager_'.$api;
			$this->managers[$module] = new $manager($dao);
		}

		return $this->managers[$module];
	}
}