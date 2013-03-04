<?php
namespace core;

class Config extends ApplicationComponent {
	protected $module;

	protected $data = null;

	public function __construct(Application $app, $module) {
		$this->module = $module;
	}

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