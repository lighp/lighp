<?php

namespace core\submodules;

use \Spyc;
use core\apps\Application;
use core\fs\Pathfinder;

/**
 * A module's translation.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class ModuleTranslation extends ModuleComponent {
	/**
	 * The translation's section.
	 * @var string
	 */
	protected $section;

	/**
	 * The translation data.
	 * @var array
	 */
	protected $translationData;

	public function __construct(Application $app, $module, $section = null) {
		parent::__construct($app, $module);

		$this->setSection($section);
	}

	public function section() {
		return $this->section;
	}

	public function setSection($section) {
		if (!is_string($section) || empty($section)) {
			throw new \InvalidArgumentException('Invalid section name');
		}

		$this->section = $section;
	}

	protected function _filePath() {
		return Pathfinder::getPathFor('locale').'/fr_FR/'.$this->module.'.yaml';
	}

	public function read() {
		if (empty($this->translationData)) {
			$translationData = Spyc::YAMLLoad($this->_filePath());

			if ($translationData === false) {
				throw new \RuntimeException('Cannot open translation file "'.$this->_filePath().'"');
			}

			$this->translationData = $translationData;
		}

		return $this->_getSection();
	}

	public function get($path = null) {
		$sectionData = $this->read();

		$result = $this->_followPath($path, $this->translationData);
		if ($result !== false) {
			return $result;
		}

		$result = $this->_followPath($path, $sectionData);
		if ($result !== false) {
			return $result;
		}

		return $path;
	}

	protected function _followPath($path, array $data) {
		$indexes = explode('.', $path);
		
		if (empty($path)) {
			return $data;
		}

		foreach($indexes as $key => $index) {
			$remainingIndexes = array_slice($indexes, $key);
			$remainingPath = implode('.', $remainingIndexes);
			if (isset($data[$remainingPath])) {
				return $data[$remainingPath];
			}

			if (isset($data[$index])) {
				$data = $data[$index];
			} else {
				return false;
			}
		}

		return $data;
	}

	protected function _getSection($section = null) {
		if (empty($section)) {
			$section = $this->section;
		}

		$translationData = $this->translationData;

		if (!empty($section) && isset($translationData[$section])) {
			return $translationData[$section];
		} else {
			return $translationData;
		}
	}

	public function __isset($path) {
		return ($this->get($path) != $path);
	}

	public function __get($path) {
		return $this->get($path);
	}
}