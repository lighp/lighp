<?php

namespace core\submodules;

use \InvalidArgumentException;
use \RuntimeException;
use \Spyc;
use core\apps\Application;
use core\fs\Pathfinder;
use core\i18n\TranslationDictionary;

/**
 * A module's translation.
 * @author emersion
 * @since 1.0alpha1
 */
class ModuleTranslation extends ModuleComponent {
	/**
	 * The translation's section.
	 * @var string
	 */
	protected $section;

	/**
	 * The translation dictionary.
	 * @var TranslationDictionary
	 */
	protected $dict;

	public function __construct(Application $app, $module, $section = null) {
		parent::__construct($app, $module);

		$this->setSection($section);
	}

	/**
	 * Open another translation.
	 * @param  string $module    The module's name.
	 * @param  string $section   The section's name.
	 * @return ModuleTranslation The translation.
	 */
	public function open($module, $section = null) {
		return new self($this->app(), $module, $section);
	}

	/**
	 * Get this translation's dictionary.
	 * @return TranslationDictionary The dictionary.
	 */
	public function dict() {
		if (empty($this->dict)) {
			$this->dict = TranslationDictionary::fromYamlFile($this->_dictPathFromIndex($this->module()));
		}

		return $this->dict;
	}

	/**
	 * Read this translation.
	 * @return array The translation's data.
	 */
	public function read() {
		$dict = $this->dict();

		if (!empty($this->section) && isset($dict[$this->section])) {
			return $dict[$this->section];
		}

		return $dict;
	}

	/**
	 * Get a translation value.
	 * @param  string $path The translation's path.
	 * @return string       The translation's value.
	 */
	public function get($path = null) {
		$dict = $this->dict(); //Make sure the dictionary is loaded

		if (empty($path)) {
			return $this->dict;
		}

		if (isset($dict[$path])) {
			return $dict[$path];
		}

		if (!empty($this->section)) {
			$sectionPath = $this->section . '.' . $path;

			if (isset($dict[$sectionPath])) {
				return $dict[$sectionPath];
			}
		}

		return $path;
	}

	/**
	 * Get this translation's section.
	 * @return string The section.
	 */
	public function section() {
		return $this->section;
	}

	/**
	 * Set this translation's section.
	 * @param string $section The section.
	 */
	public function setSection($section) {
		$this->section = $section;
	}

	public function __isset($path) {
		return ($this->get($path) != $path);
	}

	public function __get($path) {
		return $this->get($path);
	}


	/**
	 * Get a dictionary's file path.
	 * @param  string $lang  The language.
	 * @param  string $index The dictionary's index.
	 * @return string        The file path.
	 */
	protected function _dictPath($lang, $index) {
		return Pathfinder::getPathFor('locale').'/'.$lang.'/'.$index.'.yaml';
	}

	/**
	 * Get a dictionary's path from its index. The language is determined automatically.
	 * @param  string $index The dictionary's index.
	 * @return string        The file path.
	 */
	protected function _dictPathFromIndex($index) {
		$lang = $this->app()->user()->lang();

		$filepath = $this->_dictPath($lang, $index);
		if (!file_exists($filepath) && strpos($lang, '-') !== false) {
			$lang = explode('-', $lang)[0];
			$filepath = $this->_dictPath($lang, $index);
		}
		if (!file_exists($filepath)) {
			$filepath = $this->_dictPath('en', $index);
		}
		if (!file_exists($filepath)) {
			$langsDir = Pathfinder::getPathFor('locale');
			$handle = opendir($langsDir);

			$found = false;

			while (($filename = readdir($handle)) !== false) {
				if ($filename == '.' || $filename == '..' || !is_dir($langsDir.'/'.$filename)) {
					continue;
				}

				$lang = $filename;
				$filepath = $this->_dictPath($lang, $index);
				if (file_exists($filepath)) {
					$found = true;
					break;
				}
			}
			closedir($handle);

			if (!$found) {
				throw new RuntimeException('Cannot find the translation file with index "'.$index.'"');
			}
		}

		return $filepath;
	}
}
