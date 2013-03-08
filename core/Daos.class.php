<?php
namespace core;

/**
 * A Data Access Objects manager.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class Daos {
	/**
	 * Configuration for DAOs.
	 * @var array
	 */
	protected $config;

	/**
	 * DAOs.
	 * @var array
	 */
	protected $daos = array();

	/**
	 * Initialize this DAO manager.
	 */
	public function __construct() {}

	/**
	 * Load the DAOs' configuration.
	 */
	protected function _loadConfig() {
		if (empty($this->config)) { //If config isn't already loaded
			$configPath = __DIR__.'/../etc/core/daos.json';
			$json = file_get_contents($configPath);

			if ($json === false) { //Check for reading errors
				throw new \RuntimeException('Unable to load configuration file "'.$configPath.'"');
			}

			$this->config = json_decode($json, true);

			if ($this->config === false || $this->config === null) { //Check for JSON parse errorss
				throw new \RuntimeException('Unable to parse configuration file "'.$configPath.'"');
			}
		}
	}

	/**
	 * Get a DAO.
	 * @param  string $api The API name.
	 * @return object      The DAO.
	 */
	public function getDao($api) {
		if (isset($this->daos[$api])) { //DAO already stored in cache
			return $this->daos[$api];
		}

		$this->_loadConfig(); //Load config
		
		if (!isset($this->config[$api])) { //Unknown DAO
			throw new \InvalidArgumentException('Unrecognized DAO "'.$api.'"');
		}

		//Create the DAO
		$daoData = $this->config[$api];
		$dao = call_user_func_array($daoData['callback'], $daoData['callback']);

		if ($dao === false) { //Check for errors
			throw new \RuntimeException('Unable to load DAO "'.$api.'"');
		}

		$this->daos[$api] = $dao; //Store the DAO in cache

		return $dao;
	}

	/**
	 * Get the default API's name.
	 * @return string
	 */
	public function getDefaultApi() {
		$this->_loadConfig(); //Load config

		//Return the API which is defined as the default one
		foreach($this->config as $api => $daoData) {
			if (isset($daoData['default']) && (int) $daoData['default'] == 1) {
				return $api;
			}
		}
	}
}