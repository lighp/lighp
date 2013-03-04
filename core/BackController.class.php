<?php
namespace core;

abstract class BackController extends ApplicationComponent {
	protected $action = '';
	protected $module = '';
	protected $page = null;
	protected $view = '';

	public function __construct(Application $app, $module, $action) {
		parent::__construct($app);

		$daos = new Daos;
		$this->managers = new Managers($daos);
		$this->page = new Page($app);

		$this->setModule($module);
		$this->setAction($action);
		$this->setView($action);
	}

	public function execute() {
		$method = 'execute'.ucfirst($this->action);

		if (!is_callable(array($this, $method))) {
			throw new \RuntimeException('Unknown action "'.$this->action.'" in this controller');
		}

		$this->$method($this->app->httpRequest());
	}

	public function page() {
		return $this->page;
	}

	public function setModule($module) {
		if (!is_string($module) || empty($module)) {
			throw new \InvalidArgumentException('Invalid module name');
		}

		$this->module = $module;
	}

	public function setAction($action) {
		if (!is_string($action) || empty($action)) {
			throw new \InvalidArgumentException('Invalid action name');
		}

		$this->action = $action;
	}

	public function setView($view) {
		if (!is_string($view) || empty($view)) {
			throw new \InvalidArgumentException('Invalid view name');
		}

		$this->view = $view;

		$this->page->setTemplate(__DIR__.'/../tpl/'.$this->app->name().'/'.$this->module.'/'.$this->view.'.html');
	}
}