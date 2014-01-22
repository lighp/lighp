<?php

namespace core\responses;

use core\fs\CacheDirectory;
use core\Config;
use core\submodules\ModuleTranslation;
use \Mustache_Engine;
use \Mustache_Loader_FilesystemLoader;

/**
 * A page.
 * Page's templates are stored in /tpl.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class Page extends ResponseContent {
	/**
	 * Page's variables.
	 * @var array
	 */
	protected $vars = array();

	/**
	 * The page's translation.
	 * @var ModuleTranslation
	 */
	protected $translation;

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
	 * Get this page's variables.
	 * @return array
	 */
	public function vars() {
		return $this->vars;
	}

	/**
	 * Get global variables.
	 * @return array
	 */
	public function globalVars() {
		$json = file_get_contents(__DIR__.'/../../etc/core/website.json');
		$data = json_decode($json, true);

		$vars = array();
		foreach($data as $index => $value) {
			$vars['WEBSITE_'.strtoupper($index)] = $value;
		}

		return $vars;
	}

	protected function _getCoreLinkedFiles($type) {
		if ($type == 'js') {
			$configFilename = 'scripts';
		} else if ($type == 'css') {
			$configFilename = 'stylesheets';
		} else {
			return array();
		}
		$conf = new Config(__DIR__.'/../../etc/core/'.$configFilename.'.json');
		return $conf->read();
	}

	/**
	 * Generate the page.
	 * @return string The generated page.
	 */
	public function generate() {
		$templatePath = $this->_templatePath();

		if (!file_exists($templatePath)) {
			throw new \RuntimeException('"'.$templatePath.'" : template doesn\'t exist');
		}

		$contentTpl = file_get_contents($templatePath);
		if ($contentTpl === false) {
			throw new \RuntimeException('Cannot read template "'.$templatePath.'"');
		}

		$layoutPath = __DIR__.'/../../tpl/'.$this->app->name().'/layout.html';
		$layoutTpl = file_get_contents($layoutPath);
		if ($layoutTpl === false) {
			throw new \RuntimeException('Cannot read template "'.$layoutPath.'"');
		}

		$layoutControllerClass = 'ctrl\\'.$this->app->name().'\\LayoutController';
		if (class_exists($layoutControllerClass)) {
			$layoutController = new $layoutControllerClass($this->app, $this);
			$layoutController->execute($this->app->httpRequest());
		}

		$globalVars = $this->globalVars();

		$contentLoader = new Mustache_Loader_FilesystemLoader(dirname($templatePath), array('extension' => '.html'));
		$layoutLoader = new Mustache_Loader_FilesystemLoader(dirname($layoutPath), array('extension' => '.html'));

		$engine = $this->_templatesEngine();

		$contentVars = array_merge($this->vars, $globalVars);
		$engine->setPartialsLoader($contentLoader);
		$content = $engine->render($contentTpl, $contentVars);

		$layoutVars = array_merge($this->vars, array('content' => $content), $globalVars);
		$engine->setPartialsLoader($layoutLoader);
		return $engine->render($layoutTpl, $layoutVars);
	}

	/**
	 * Get this page's template path.
	 * @return string The template path.
	 */
	protected function _templatePath() {
		return __DIR__.'/../../tpl/'.$this->app->name().'/'.$this->module.'/'.$this->action.'.html';
	}

	/**
	 * Get the template's engine.
	 * @return object
	 */
	protected function _templatesEngine() {
		$mustacheOptions = array();

		//Cache
		try {
			$cacheDir = new CacheDirectory('core/mustache');
			$mustacheOptions['cache'] = $cacheDir->path();
		} catch(\Exception $e) {}

		//Create the engine
		$mustache = new Mustache_Engine($mustacheOptions);

		//Translate
		$translation = $this->translation();
		$mustache->addHelper('translate', function($path) use ($translation) {
			return $translation->get($path);
		});

		$mustache->addHelper('__', $mustache->getHelper('translate'));

		//URL builder
		$router = $this->app->router();
		$mustache->addHelper('buildUrl', function($rawData, $helper) use ($router) {
			$data = explode(' ', $helper->render($rawData));

			if (count($data) < 2) {
				return '#';
			}

			$module = $data[0];
			$action = $data[1];
			$vars = array();

			if (count($data) >= 3) {
				$vars = array_slice($data, 2);
			}

			try {
				$url = '{{WEBSITE_ROOT}}/' . $router->getUrl($module, $action, $vars);
			} catch(\Exception $e) {
				return '#';
			}

			return $url;
		});

		//Linked files
		$appName = $this->app->name();
		$module = $this->module();
		$action = $this->action();
		$globalVars = $this->globalVars();
		$pageVars = $this->vars();
		$coreLinkedFiles = array(
			'js' => $this->_getCoreLinkedFiles('js'),
			'css' => $this->_getCoreLinkedFiles('css')
		);
		$mustache->addHelper('getLinkedFiles', function($type) use ($appName, $module, $action, $globalVars, $pageVars, $coreLinkedFiles) {
			$linkedFiles = array();

			$filesBaseDir = $type;
			$relativePublicFilesDir = __DIR__.'/../../public/';

			if (!is_dir($relativePublicFilesDir.'/'.$filesBaseDir)) {
				return '';
			}

			//Core files
			$coreFilesPath = $filesBaseDir.'/core';
			if (isset($coreLinkedFiles[$type])) {
				foreach($coreLinkedFiles[$type] as $scriptData) {
					$filePath = $coreFilesPath.'/'.$scriptData['filename'];

					$linkedFiles[] = $filePath;
				}
			}

			//Module file
			$moduleFilePath = $filesBaseDir.'/app/'.$appName.'/'.$module.'.'.$type;
			if (file_exists($relativePublicFilesDir.'/'.$moduleFilePath)) {
				$linkedFiles[] = $moduleFilePath;
			}

			//Action file
			$actionFilePath = $filesBaseDir.'/app/'.$appName.'/'.$module.'/'.$action.'.'.$type;
			if (file_exists($relativePublicFilesDir.'/'.$actionFilePath)) {
				$linkedFiles[] = $actionFilePath;
			}

			$linkedFilesTags = '';
			foreach($linkedFiles as $filePath) {
				switch($type) {
					case 'js':
						$tag = '<script type="text/javascript" src="{{WEBSITE_ROOT}}/'.$filePath.'"></script>';
						break;
					case 'css':
						$tag = '<link href="{{WEBSITE_ROOT}}/'.$filePath.'" rel="stylesheet" media="screen" />';
						break;
					default:
						$tag = '';
				}

				$linkedFilesTags .= $tag;
			}

			if ($type == 'js') {
				$linkedFilesTags .= '<script type="text/javascript">Lighp.websiteConf = '.json_encode($globalVars).';Lighp.setVars('.json_encode($pageVars).');</script>';
			}

			return $linkedFilesTags;
		});

		//ucfirst
		$mustache->addHelper('ucfirst', function($text, $helper = null) {
			if (!empty($helper)) {
				$text = $helper->render($text);
			}

			return ucfirst($text);
		});

		//Date & time
		$mustache->addHelper('strtotime', function($text, $helper = null) {
			if (!empty($helper)) {
				$text = $helper->render($text);
			}

			return $text;
		});
		$mustache->addHelper('date', function($text, $helper = null) {
			if (!empty($helper)) {
				$text = $helper->render($text);
			}

			return $text;
		});
		

		//File size
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

	/**
	 * Set the page's translation.
	 * @param ModuleTranslation $translation The translation.
	 */
	public function setTranslation(ModuleTranslation $translation, $section = null) {
		$this->translation = $translation;
		$this->translationSection = $section;

		$this->addVar('dictionary', $translation);
		$this->addVar('_', $translation);
	}

	/**
	 * Get the page's translation.
	 * @return ModuleTranslation
	 */
	public function translation() {
		return $this->translation;
	}
}