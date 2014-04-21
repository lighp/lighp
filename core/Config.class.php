<?php
namespace core;

/**
 * A configuration file.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class Config {
	/**
	 * The configuration's path.
	 * @var string
	 */
	protected $path;

	/**
	 * The config data.
	 * @var array
	 */
	protected $data = null;

	/**
	 * Initialize the configuration file.
	 * @param string $path Configuration file path.
	 */
	public function __construct($path) {
		$this->path = $path;
	}

	/**
	 * Read the configuration's data.
	 * @return array The data.
	 */
	public function read() {
		if (empty($this->data)) {
			if (!file_exists($this->path)) {
				$this->data = array();
			} else {
				$json = file_get_contents($this->path);

				if ($json === false) {
					$this->data = array();
				} else {
					$this->data = json_decode($json, true);
				}
			}
		}

		return $this->data;
	}

	/**
	 * Write things to the configuration file
	 * @param array $data Configuration data.
	 */
	public function write(array $data, $flags = null) {
		if (!is_int($flags)) {
			$flags = JSON_UNESCAPED_SLASHES;

			if (defined('JSON_PRETTY_PRINT')) { //PHP >= 5.4
				$flags = $flags | JSON_PRETTY_PRINT;
			}
		}

		$json = json_encode($data, $flags);

		if ($json === false) {
			throw new \RuntimeException('Cannot encode configuration to JSON');
		}

		$result = file_put_contents($this->path, $json);

		if ($result === false) {
			throw new \RuntimeException('Cannot open configuration file "'.$this->path.'" (error while writing)');
		}
	}
}