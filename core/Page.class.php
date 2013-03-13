<?php
namespace core;

/**
 * A page.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class Page extends ApplicationComponent {
	/**
	 * The path to the page's template.
	 * @var string
	 */
	protected $templatePath;

	/**
	 * Page's variables.
	 * @var array
	 */
	protected $vars = array();

	/**
	 * Add a page variable.
	 * @param string|int $name  The variable's name.
	 * @param mixed      $value The variable's value.
	 */
	public function addVar($name, $value) {
		if (!is_string($name) || is_numeric($name) || empty($name)) {
			throw new \InvalidArgumentException('Invalid variable name');
		}

		$this->vars[$name] = $value;
	}

	/**
	 * Get global variables.
	 * @return array
	 */
	public function _getGlobalVars() {
		$json = file_get_contents(__DIR__.'/../etc/core/website.json');
		$data = json_decode($json, true);

		$vars = array();
		foreach($data as $index => $value) {
			$vars['WEBSITE_'.strtoupper($index)] = $value;
		}

		return $vars;
	}

	/**
	 * Generate the page.
	 * @return string The generated page.
	 */
	public function generate() {
		if (!file_exists($this->templatePath)) {
			throw new \RuntimeException('"'.$this->templatePath.'" : template doesn\'t exist');
		}

		$contentTpl = file_get_contents($this->templatePath);
		if ($contentTpl === false) {
			throw new \RuntimeException('Cannot read template "'.$this->templatePath.'"');
		}

		$layoutPath = __DIR__.'/../tpl/'.$this->app->name().'/layout.html';
		$layoutTpl = file_get_contents($layoutPath);
		if ($layoutTpl === false) {
			throw new \RuntimeException('Cannot read template "'.$layoutPath.'"');
		}

		$layoutControllerClass = 'ctrl\\'.$this->app->name().'\\LayoutController';
		if (class_exists($layoutControllerClass)) {
			$layoutController = new $layoutControllerClass($this->app, $this);
			$layoutController->execute($this->app->httpRequest());
		}

		$globalVars = $this->_getGlobalVars();

		$contentLoader = new mustache\loader\FilesystemLoader(dirname($this->templatePath), array('extension' => '.html'));
		$layoutLoader = new mustache\loader\FilesystemLoader(dirname($layoutPath), array('extension' => '.html'));

		$engine = $this->_getTemplatesEngine();

		$contentVars = array_merge($this->vars, $globalVars);
		$engine->setPartialsLoader($contentLoader);
		$content = $engine->render($contentTpl, $contentVars);

		$layoutVars = array_merge($this->vars, array('content' => $content), $globalVars);
		$engine->setPartialsLoader($layoutLoader);
		return $engine->render($layoutTpl, $layoutVars);
	}

	/**
	 * Set this page's template.
	 * @param string $templatePath The template path.
	 */
	public function setTemplate($templatePath) {
		if (!is_string($templatePath) || empty($templatePath)) {
			throw new \InvalidArgumentException('Invalid template path');
		}

		$this->templatePath = $templatePath;
	}

	/**
	 * Get the template's engine.
	 * @return object
	 */
	protected function _getTemplatesEngine() {
		$mustache = new mustache\Engine();

		$mustache->addHelper('filesize', function($value) {
			$bytes = (int) $value;

			$suffixes = array('octets', 'Kio', 'Mio', 'Gio', 'Tio');
			$precision = 2;

			$base = 0;
			$roundedBytes = 0;
			if ($bytes != 0) {
				$base = log(abs($bytes)) / log(1024);
				$roundedBytes = round(pow(1024, $base - floor($base)), $precision);
			}

			return (($bytes < 0) ? '-' : '') . $roundedBytes . ' ' . $suffixes[floor($base)];
		});

		return $mustache;
	}
}