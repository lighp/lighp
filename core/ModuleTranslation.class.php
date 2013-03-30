<?php
namespace core;

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

		$this->section = $section;
	}

	protected function _filePath() {
		return __DIR__.'/../share/locale/fr_FR/'.$this->module.'.yaml';
	}

	public function read() {
		if (empty($this->translationData)) {
			$translationData = spyc\Spyc::YAMLLoad($this->_filePath());

			if ($translationData === false) {
				throw new \RuntimeException('Cannot open translation file "'.$this->_filePath().'"');
			}

			$this->translationData = $translationData;
		}

		return $this->_getSection();
	}

	public function get($path) {
		$indexes = explode('.', $path);
		$value = $this->read();

		foreach($indexes as $key => $index) {
			$remainingIndexes = array_slice($indexes, $key);
			$remainingPath = implode('.', $remainingIndexes);
			if (isset($value[$remainingPath])) {
				return $value[$remainingPath];
			}

			if (isset($value[$index])) {
				$value = $value[$index];
			} else {
				return $path;
			}
		}

		return $value;
	}

	public function _getSection($section = null) {
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
}