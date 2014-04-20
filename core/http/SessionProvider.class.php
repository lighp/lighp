<?php
namespace core\http;

use \RuntimeException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcacheSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeSessionHandler;
use core\fs\Pathfinder;
use core\Config;

/**
 * Session provider.
 * @author  emersion
 * @since  1.0alpha2
 */
class SessionProvider {
	const CONFIG_FILE = 'session.json';

	protected $handler;
	protected $storage;
	protected $session;

	protected static $provider;

	/**
	 * Get sessions configuration.
	 * @return Config
	 */
	protected function _getConfig() {
		$configPath = Pathfinder::getPathFor('config').'/'.self::CONFIG_FILE;

		return new Config($configPath);
	}

	public function handler() {
		if (!empty($this->storage)) {
			$handler = $this->handler;
		} else {
			$config = $this->_getConfig()->read();

			$handlerName = (isset($config['handler'])) ? $config['handler'] : '';
			$handlerConfig = (isset($config['config'])) ? $config['config'] : array();

			switch ($handlerName) {
				case 'memcache':
					if (!isset($handlerConfig['host']) || !isset($handlerConfig['port'])) {
						throw new RuntimeException('You must specify memcache host and port in handler config in "'.self::CONFIG_FILE.'"');
					}

					$memcache = new \Memcache;
					$memcache->addServer($handlerConfig['host'], (int) $handlerConfig['port']);

					$handler = new MemcacheSessionHandler($memcache);
				case 'native':
				default:
					$handler = new NativeSessionHandler;
			}
		}

		return $handler;
	}

	public function storage() {
		if (!empty($this->storage)) {
			$storage = $this->storage;
		} else {
			$handler = $this->handler();

			$storage = new NativeSessionStorage(array(), $handler);
			$this->storage = $storage;
		}

		return $storage;
	}

	public function session() {
		if (!empty($this->session)) {
			$session = $this->session;
		} else {
			$storage = $this->storage();

			$session = new Session($storage);
			$this->session = $session;
		}

		return $session;
	}

	public static function get() {
		if (!empty(self::$provider)) {
			$provider = self::$provider;
		} else {
			$provider = new self;
			self::$provider = $provider;
		}

		return $provider;
	}
}