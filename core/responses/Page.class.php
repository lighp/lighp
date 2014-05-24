<?php

namespace core\responses;

use core\fs\CacheDirectory;
use core\fs\Pathfinder;
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
	 * Get one of this page's variables.
	 * @param string|int $name  The variable's name.
	 */
	public function getVar($name) {
		if (!isset($this->vars[$name])) {
			return null;
		}

		return $this->vars[$name];
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
		
		$files = $conf->read();

		return $files;
	}

	/**
	 * Generate the page.
	 * @return string The generated page.
	 */
	public function generate() {
		$templatePath = $this->_templatePath();

		$content = $this->renderTemplate($this->_templatePath(), $this->vars);

		$layoutPath = Pathfinder::getPathFor('tpl').'/'.$this->app->name().'/layout.html';

		$layoutControllerClass = 'ctrl\\'.$this->app->name().'\\LayoutController';
		if (class_exists($layoutControllerClass)) {
			$layoutController = new $layoutControllerClass($this->app, $this);
			$layoutController->execute($this->app->httpRequest());
		}

		$layoutVars = array_merge($this->vars, array('content' => $content));
		return $this->renderTemplate($layoutPath, $layoutVars);
	}

	/**
	 * Render a template.
	 * @param  string $templatePath The template's path.
	 * @param  array  $vars         Variables to provide to the template.
	 * @return string               The output.
	 */
	public function renderTemplate($templatePath, array $vars = array()) {
		if (!file_exists($templatePath)) {
			throw new \RuntimeException('"'.$templatePath.'" : template doesn\'t exist');
		}

		$contentTpl = file_get_contents($templatePath);
		if ($contentTpl === false) {
			throw new \RuntimeException('Cannot read template "'.$templatePath.'"');
		}

		$globalVars = $this->globalVars();

		$contentLoader = new Mustache_Loader_FilesystemLoader(dirname($templatePath), array('extension' => '.html'));

		$engine = $this->_templatesEngine();

		$contentVars = array_merge($vars, $globalVars);
		$engine->setPartialsLoader($contentLoader);
		return $engine->render($contentTpl, $contentVars);
	}

	/**
	 * Get this page's template path.
	 * @return string The template path.
	 */
	protected function _templatePath() {
		return Pathfinder::getPathFor('tpl').'/'.$this->app->name().'/'.$this->module.'/'.$this->action.'.html';
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
			$hasPublicFilesDir = is_dir($relativePublicFilesDir.'/'.$filesBaseDir);

			//Core files
			$coreFilesPath = $filesBaseDir.'/core';
			if (isset($coreLinkedFiles[$type])) {
				foreach($coreLinkedFiles[$type] as $scriptData) {
					if (is_string($scriptData)) {
						$scriptData = array('filename' => $scriptData);
					}

					if (!empty($scriptData['app'])) {
						if (!is_array($scriptData['app'])) {
							$scriptData['app'] = array($scriptData['app']);
						}

						if (!in_array($appName, $scriptData['app'])) {
							continue;
						}
					}

					if (substr($scriptData['filename'], 0, 2) == '//') { //URL
						$filePath = $scriptData['filename'];
					} elseif (substr($scriptData['filename'], 0, 1) == '/') { //Absolute path
						$filePath = substr($scriptData['filename'], 1);
					} elseif ($hasPublicFilesDir) { //Relative path
						$filePath = $coreFilesPath.'/'.$scriptData['filename'];
					}

					$linkedFiles[] = $filePath;
				}
			}

			if ($hasPublicFilesDir) {
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
			}

			$linkedFilesTags = '';
			foreach($linkedFiles as $filePath) {
				if (substr($filePath, 0, 2) != '//') { //URL
					$filePath = '{{WEBSITE_ROOT}}/'.$filePath;
				}

				switch($type) {
					case 'js':
						$tag = '<script type="text/javascript" src="'.$filePath.'"></script>';
						break;
					case 'css':
						$tag = '<link href="'.$filePath.'" rel="stylesheet" media="screen" />';
						break;
					default:
						$tag = '';
				}

				$linkedFilesTags .= $tag;
			}

			if ($type == 'js') {
				$linkedFilesTags .= '<script type="text/javascript">Lighp.setWebsiteConf('.json_encode($globalVars).');Lighp.setVars('.json_encode($pageVars).');</script>';
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

			return strtotime($text);
		});
		$mustache->addHelper('date', function($time, $helper = null) {
			if (!empty($helper)) {
				$time = $helper->render($time);
			}

			$time = (int) $time;

			return date('Y-m-d H:i:s', $time);
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

		//join
		$mustache->addHelper('join', function($value) {
			if (!is_array($value)) {
				return $value;
			}

			return implode(', ', $value);
		});

		//debug
		$mustache->addHelper('var_dump', function($value) {
			if (!is_array($value)) {
				return $value;
			}

			ob_start();
			var_dump($value);
			$out = ob_get_contents();
			ob_end_clean();

			return $out;
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