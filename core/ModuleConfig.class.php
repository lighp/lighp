<?php
namespace core;

/**
 * A model's configuration file.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class ModuleConfig extends ApplicationComponent {
	/**
	 * The associated module.
	 * @var string
	 */
	protected $module;

	/**
	 * The config data.
	 * @var array
	 */
	protected $data = null;

	/**
	 * Initialize the configuration file.
	 * @param Application $app    The application.
	 * @param string      $module The module.
	 */
	public function __construct(Application $app, $module) {
		$this->module = $module;
	}

	/**
	 * Get the configuration.
	 * @return array The configuration.
	 */
	public function get() {
		if (empty($this->data)) {
			$configPath = __DIR__.'/../etc/app/'.$this->app->name().'/'.$this->module.'/config.json';

			if (!file_exists($configPath)) {
				$this->data = array();
			} else {
				$json = file_get_contents($configPath);

				if ($json === false) {
					$this->data = array();
				} else {
					$this->data = json_decode($json, true);
				}
			}
		}

		return $this->data;
	}
}