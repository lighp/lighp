<?php
namespace core;

/**
 * A module's translation.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class ModuleTranslation extends ModuleComponent {
	/**
	 * The translation data.
	 * @var array
	 */
	protected $translationData;

	/**
	 * Initialize the configuration file.
	 * @param Application $app    The application.
	 * @param string      $module The module.
	 */
	public function __construct(Application $app, $module) {
		parent::__construct($app, $module);
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

		return $this->translationData;
	}

	public function get($path, $section = null) {
		$indexes = explode('.', $path);
		$value = $this->read();

		if (!empty($section) && isset($value[$section])) {
			$value = $value[$section];
		}

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
}