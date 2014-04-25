<?php

namespace core\i18n;

use \RuntimeException;
use \ArrayAccess;
use \Spyc;
use core\fs\Pathfinder;

/**
 * A translation dictionary.
 * @author emersion
 * @since 1.0alpha2
 */
class TranslationDictionary implements ArrayAccess {
	/**
	 * The dictionary data.
	 * @var array
	 */
	protected $dict = array();

	/**
	 * Create a new dictionary.
	 * @param array $dict The dictionary data.
	 */
	public function __construct($dict) {
		$this->dict = $dict;
	}

	/**
	 * Read this dictionary.
	 * @return array This dictionary data.
	 */
	public function read() {
		return $this->dict;
	}

	/**
	 * Get a dictionary's entry.
	 * @param  string $path The entry's path. Dot-notation is supported.
	 * @return string       The entry's value.
	 */
	public function get($path = null) {
		$dict = $this->read();

		$result = $this->_followPath($path, $dict);
		if ($result === false) {
			return $path;
		}

		if (is_array($result)) {
			$result = self::fromArray($result);
		}

		return $result;
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

	public function offsetExists($path) {
		$dict = $this->read();

		$result = $this->_followPath($path, $dict);

		return ($result !== false);
	}

	public function offsetGet($path) {
		return $this->get($path);
	}

	public function offsetSet($path, $value) {
		throw new RuntimeException('Cannot set a translation dictionary\'s entry');
	}

	public function offsetUnset($path) {
		throw new RuntimeException('Cannot unset a translation dictionary\'s entry');
	}


	/**
	 * Load a dictionary from an array (or an array-like object).
	 * @param  array $data           The dictionary data.
	 * @return TranslationDictionary The dictionary.
	 */
	public static function fromArray($data) {
		return new self($data);
	}

	/**
	 * Load a dictionary from a YAML string.
	 * @param  array $yaml           The YAML string.
	 * @return TranslationDictionary The dictionary.
	 */
	public static function fromYaml($yaml) {
		$data = Spyc::YAMLLoadString($yaml);

		if ($data === false) {
			throw new \RuntimeException('Cannot read YAML translation string (parse error)');
		}

		return new self($data);
	}

	/**
	 * Load a dictionary from a YAML file.
	 * @param  array $filepath       The YAML file path.
	 * @return TranslationDictionary The dictionary.
	 */
	public static function fromYamlFile($filepath) {
		$data = Spyc::YAMLLoad($filepath);

		if ($data === false) {
			throw new \RuntimeException('Cannot read YAML translation file "'.$filepath.'"');
		}

		return new self($data);
	}

	/**
	 * Load a dictionary from an index.
	 * @param  array $index          The index.
	 * @return TranslationDictionary The dictionary.
	 */
	public static function fromIndex($index) {
		$filepath = Pathfinder::getPathFor('locale').'/'.$index.'.yaml';

		return self::fromYamlFile($filepath);
	}
}