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

	protected function _getInstalled() {
		$dirPath = Pathfinder::getRoot().'/etc/core/dao';

		$handle = opendir($dirPath);
		$daos = array();

		while (false !== ($entry = readdir($handle))) {
			$configPath = $dirPath . '/' . $entry;

			if (pathinfo($entry, PATHINFO_EXTENSION) !== 'json') {
				continue;
			}

			$daoName = pathinfo($entry, PATHINFO_FILENAME);
			$configFile = new Config($configPath);
			$config = $configFile->read();

			$daos[$daoName] = array(
				'factory' => (isset($config['factory'])) ? $config['factory'] : null
			);
		}

		closedir($handle);

		return $daos;
	}

	/**
	 * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
	 * keys to arrays rather than overwriting the value in the first array with the duplicate
	 * value in the second array, as array_merge does. I.e., with array_merge_recursive,
	 * this happens (documented behavior):
	 *
	 * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
	 *     => array('key' => array('org value', 'new value'));
	 *
	 * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
	 * Matching keys' values in the second array overwrite those in the first array, as is the
	 * case with array_merge, i.e.:
	 *
	 * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
	 *     => array('key' => array('new value'));
	 *
	 * Parameters are passed by reference, though only for performance reasons. They're not
	 * altered by this function.
	 * 
	 * Source: http://php.net/manual/function.array-merge-recursive.php#92195
	 *
	 * @param array $a
	 * @param array $b
	 * @return array
	 * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
	 * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
	 * @author emersion <contact (at) emersion (dot) fr>
	 */
	protected function _array_merge_recursive(array &$a, array &$b, $depth = 0, $_currentDepth = 0) {
		$merged = $a;

		foreach ($b as $key => &$value) {
			if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
				if ($depth !== 0 && $_currentDepth > $depth) {
					$merged[$key] = $value;
				} else {
					$merged[$key] = $this->_array_merge_recursive($merged[$key], $value, $depth, $_currentDepth + 1);
				}
			} else {
				$merged[$key] = $value;
			}
		}

		return $merged;
	}

	/**
	 * Load the DAOs' configuration.
	 */
	protected function _loadConfig() {
		if (empty($this->config)) { //If config isn't already loaded
			$configFile = $this->_getConfig();
			$config = $configFile->read();

			$installed = $this->_getInstalled();

			$this->config = $this->_array_merge_recursive($installed, $config, 2);
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
		if (!isset($daoData['factory']) && isset($daoData['callback'])) {
			$daoData['factory'] = $daoData['callback'];
		}
		if (!isset($daoData['config'])) {
			$daoData['config'] = array();
		}
		if (empty($daoData['factory'])) {
			throw new \RuntimeException('Unable to load DAO "'.$api.'" (no factory specified)');
		}

		try {
			$dao = call_user_func($daoData['factory'], $daoData['config']);
		} catch (\Exception $e) {
			throw new \RuntimeException('Unable to load DAO "'.$api.'" (error when calling factory: '.$e->getMessage().')');
		}

		if ($dao === false) { //Check for errors
			throw new \RuntimeException('Unable to load DAO "'.$api.'" (error when calling factory)');
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