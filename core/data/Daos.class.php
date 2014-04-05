<?php

namespace core\data;

use core\fs\Pathfinder;
use core\Config;

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

	protected function _getConfig() {
		$configPath = Pathfinder::getRoot().'/etc/core/daos.json';

		return new Config($configPath);
	}

	/**
	 * Load the DAOs' configuration.
	 */
	protected function _loadConfig() {
		if (empty($this->config)) { //If config isn't already loaded
			$config = $this->_getConfig();

			$this->config = $config->read();
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
		$dao = call_user_func($daoData['callback'], $daoData['config']);

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
			if (isset($daoData['default']) && $daoData['default'] == true) {
				return $api;
			}
		}

		//No default API found, return the first one
		if (count($this->config) > 0) {
			$apisList = array_keys($this->config);
			return $apisList[0];
		}
	}

	/**
	 * List all registered APIs.
	 * @return array A list of APIs.
	 */
	public function listApis() {
		$this->_loadConfig(); //Load config

		$apis = array(); //APIs list

		foreach($this->config as $api => $daoData) {
			$apis[] = $api;
		}

		return $apis;
	}

	/**
	 * Register a new DAO.
	 * @param  string   $name     The new DAO's name.
	 * @param  callable $callback The DAO's callback (i.e. the function which creates an instance of it).
	 * @param  array    $config   The DAO's config.
	 */
	public function registerDao($name, $callback) {
		if (!is_string($name) || empty($name)) {
			throw new \InvalidArgumentException('Invalid DAO name');
		}

		if (!is_callable($callback)) {
			throw new \InvalidArgumentException('Invalid DAO callback');
		}

		$this->_loadConfig(); //Load config

		$daoItem = array(
			'callback' => $callback,
			'config' => array()
		);

		if (isset($this->config[$name])) {
			$daoItem['config'] = $this->config[$name]['config'];

			if (isset($this->config[$name]['default']) && $this->config[$name]['default'] == true) {
				$daoItem['default'] = true;
			}
		}

		$this->config[$name] = $daoItem;

		$config = $this->_getConfig();
		$config->write($this->config);
	}

	/**
	 * Remove a DAO.
	 * @param  string $name The DAO's name.
	 */
	public function unregisterDao($name) {
		if (!is_string($name) || empty($name)) {
			throw new \InvalidArgumentException('Invalid DAO name');
		}

		$this->_loadConfig(); //Load config

		if (isset($this->config[$name])) {
			unset($this->config[$name]);
		}

		$config = $this->_getConfig();
		$config->write($this->config);
	}
}