<?php
namespace core;

/**
 * An back controller.
 * Back controllers are stored in /ctrl.
 * @author Simon Ser
 * @since 1.0alpha1
 */
abstract class BackController extends ApplicationComponent {
	/**
	 * The action.
	 * @var string
	 */
	protected $action = '';

	/**
	 * The module.
	 * @var string
	 */
	protected $module = '';

	/**
	 * The response's content.
	 * @var ResponseContent
	 */
	protected $responseContent;

	/**
	 * The configuration.
	 * @var ModuleConfig
	 */
	protected $config;

	/**
	 * The module's translation.
	 * @var ModuleTranslation
	 */
	protected $translation;

	/**
	 * The view (the template).
	 * @var string
	 */
	protected $view = '';

	/**
	 * Initialize the back controller.
	 * @param Application $app    The application.
	 * @param string      $module The module.
	 * @param string      $action The action.
	 */
	public function __construct(Application $app, $module, $action) {
		parent::__construct($app);

		$daos = new Daos;
		$this->managers = new Managers($daos);
		$this->config = new ModuleConfig($app, $module);
		$this->translation = new ModuleTranslation($app, $module, $action);
		$this->responseContent = new Page($app);
		$this->responseContent->setTranslation($this->translation);

		$this->setModule($module);
		$this->setAction($action);
		$this->setView($action);
	}

	/**
	 * Execute the back controller.
	 */
	public function execute() {
		$method = 'execute'.ucfirst($this->action);

		if (!is_callable(array($this, $method))) {
			throw new \RuntimeException('Unknown action "'.$this->action().'" in this controller "'.$this->module().'"');
		}

		$this->$method($this->app->httpRequest());
	}

	/**
	 * Get this back controller's module.
	 * @return string
	 */
	public function module() {
		return $this->module;
	}

	/**
	 * Get this back controller's action.
	 * @return string
	 */
	public function action() {
		return $this->action;
	}

	/**
	 * Get this back controller's response's content.
	 * @return ResponseContent
	 */
	public function responseContent() {
		return $this->responseContent;
	}

	/**
	 * Get this back controller's page.
	 * @return Page
	 */
	public function page() {
		return $this->responseContent;
	}

	/**
	 * Get this back controller's configuration.
	 * @return ModuleConfig
	 */
	public function config() {
		return $this->config;
	}

	/**
	 * Get this back controller's translation.
	 * @return ModuleTranslation
	 */
	public function translation() {
		return $this->translation;
	}

	/**
	 * Set this back controller's module.
	 * @param string $module The module.
	 */
	public function setModule($module) {
		if (!is_string($module) || empty($module)) {
			throw new \InvalidArgumentException('Invalid module name');
		}

		$this->module = $module;
	}

	/**
	 * Set this back controller's action.
	 * @param string $module The action.
	 */
	public function setAction($action) {
		if (!is_string($action) || empty($action)) {
			throw new \InvalidArgumentException('Invalid action name');
		}

		$this->action = $action;
	}

	/**
	 * Set this back controller's view.
	 * @param string $module The view.
	 */
	public function setView($view) {
		if (!is_string($view) || empty($view)) {
			throw new \InvalidArgumentException('Invalid view name');
		}

		$this->view = $view;

		$this->page()->setTemplate(__DIR__.'/../tpl/'.$this->app->name().'/'.$this->module.'/'.$this->view.'.html');
	}
}