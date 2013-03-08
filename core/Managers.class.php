<?php
namespace core;

/**
 * A class providing access to managers.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class Managers {
	/**
	 * The managers' config.
	 * @var array
	 */
	protected $config;

	/**
	 * Data Access Objects.
	 * @var Daos
	 */
	protected $daos;

	/**
	 * Cache to store initialized managers.
	 * @var array
	 */
	protected $managers = array();

	/**
	 * Initialize the managers.
	 * @param Daos $daos The DAOs.
	 */
	public function __construct(Daos $daos) {
		$this->daos = $daos;
	}

	/**
	 * Get the API's name for a specified module.
	 * @param  string $module The module's name.
	 * @return string         The API's name.
	 */
	protected function _getApiOf($module) {
		if (empty($this->config)) { //Config not loaded yet
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

	/**
	 * Get the manager associated to a given module.
	 * @param  string $module The module's name.
	 * @return Manager        The manager.
	 */
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