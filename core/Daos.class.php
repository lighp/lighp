<?php
namespace core;

class Daos {
	protected $config;

	protected $daos = array();

	public function __construct() {}

	protected function _loadConfig() {
		if (empty($this->config)) {
			$configPath = __DIR__.'/../etc/core/daos.json';
			$json = file_get_contents($configPath);

			if ($json === false) {
				throw new \RuntimeException('Unable to load configuration file "'.$configPath.'"');
			}

			$this->config = json_decode($json, true);
		}
	}

	public function getDao($api) {
		if (isset($this->daos[$api])) {
			return $this->daos[$api];
		}

		$this->_loadConfig();
		
		if (!isset($this->config[$api])) {
			throw new \InvalidArgumentException('Unrecognized DAO "'.$api.'"');
		}

		$daoData = $this->config[$api];
		$dao = call_user_func_array($daoData['callback'], $daoData['callback']);

		if ($dao === false) {
			throw new \RuntimeException('Unable to load DAO "'.$api.'"');
		}

		$this->daos[$api] = $dao;

		return $dao;
	}

	public function getDefaultApi() {
		$this->_loadConfig();

		foreach($this->config as $api => $daoData) {
			if (isset($daoData['default']) && (int) $daoData['default'] == 1) {
				return $api;
			}
		}
	}
}