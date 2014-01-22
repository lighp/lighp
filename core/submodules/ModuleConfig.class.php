<?php

namespace core\submodules;

use core\apps\Application;
use core\fs\Pathfinder;
use core\Config;

/**
 * A module's configuration file.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class ModuleConfig extends ModuleComponent {
	/**
	 * Configuration files.
	 * @var array
	 */
	protected $configs = array();

	/**
	 * Initialize the configuration file.
	 * @param Application $app    The application.
	 * @param string      $module The module.
	 */
	public function __construct(Application $app, $module) {
		parent::__construct($app, $module);
	}

	/**
	 * Get this configuration file.
	 * @param string $appName The app's name. If not specified, set as the current app name.
	 * @return Config
	 */
	protected function _config($appName = null) {
		if (empty($appName)) {
			$appName = $this->app->name();
		}

		if (isset($this->configs[$appName])) {
			return $this->configs[$appName];
		}

		$configPath = Pathfinder::getRoot().'/etc/app/'.$appName.'/'.$this->module.'/config.json';

		$config = new Config($configPath);
		$this->configs[$appName] = $config;

		return $config;
	}

	/**
	 * Get the configuration.
	 * @param string $appName The app's name. If not specified, set as the current app name.
	 * @return array The configuration.
	 */
	public function read($appName = null) {
		return $this->_config($appName)->read();
	}

	/**
	 * Update the configuration.
	 * @param array $data The new configuration.
	 * @param string $appName The app's name. If not specified, set as the current app name.
	 */
	public function write($data, $appName = null) {
		return $this->_config($appName)->write($data);
	}
}