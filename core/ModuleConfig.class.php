<?php
namespace core;

/**
 * A module's configuration file.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class ModuleConfig extends ModuleComponent {
	/**
	 * The config file.
	 * @var Config
	 */
	protected $config;

	/**
	 * Initialize the configuration file.
	 * @param Application $app    The application.
	 * @param string      $module The module.
	 */
	public function __construct(Application $app, $module) {
		parent::__construct($app, $module);

		$this->config = new Config($this->_path());
	}

	/**
	 * Get this configuration file's path.
	 * @return string
	 */
	protected function _path() {
		return __DIR__.'/../etc/app/'.$this->app->name().'/'.$this->module.'/config.json';
	}

	/**
	 * Get the configuration.
	 * @return array The configuration.
	 */
	public function read() {
		return $this->config->read();
	}

	/**
	 * Update the configuration.
	 * @param array $data The new configuration.
	 */
	public function write($data) {
		return $this->config->write($data);
	}
}