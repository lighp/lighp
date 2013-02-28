<?php
namespace core;

class Page extends ApplicationComponent {
	protected $templatePath;
	protected $vars = array();

	public function addVar($name, $value) {
		if (!is_string($name) || is_numeric($name) || empty($name)) {
			throw new \InvalidArgumentException('Invalid variable name');
		}

		$this->vars[$name] = $value;
	}

	public function _getGlobalVars() {
		$json = file_get_contents(__DIR__.'/../etc/core/website.json');
		$data = json_decode($json, true);

		$vars = array();
		foreach($data as $index => $value) {
			$vars['WEBSITE_'.strtoupper($index)] = $value;
		}

		return $vars;
	}

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

	public function setTemplate($templatePath) {
		if (!is_string($templatePath) || empty($templatePath)) {
			throw new \InvalidArgumentException('Invalid template path');
		}

		$this->templatePath = $templatePath;
	}

	protected function _getTemplatesEngine() {
		$mustache = new mustache\Engine();

		$mustache->addHelper('filesize', function($value) {
			$bytes = (int) $value;

			$suffixes = array('octets', 'Kio', 'Mio', 'Gio', 'Tio');
			$precision = 2;

			$base = log($bytes) / log(1024);

			return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
		});

		return $mustache;
	}
}