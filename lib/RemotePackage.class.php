<?php
namespace lib;

class RemotePackage extends AvailablePackage {
	protected $files;

	protected function _root() {
		$name = $this->metadata()['name'];
		return $this->repository->url() . '/packages/' . substr($name, 0, 1) . '/' . $name . '/';
	}

	public function files() {
		if ($this->files === null) {
			$filesPath = $this->_root() . '/files.json';

			$json = file_get_contents($filesPath);
			if ($json === false) { throw new \RuntimeException('Cannot open files list : "'.$filesPath.'"'); }

			$list = json_decode($json, true);
			if ($list === false || json_last_error() != JSON_ERROR_NONE) { throw new \RuntimeException('Cannot load files list (malformed JSON) : "'.$filesPath.'"'); }

			$this->files = array();

			foreach($list as $path => $item) {
				if (substr($path, 0, 1) == '/') { $path = substr($path, 1); } //Remove / at the begining of the path
				$this->files[$path] = array(
					'md5sum' => (isset($item['md5sum'])) ? $item['md5sum'] : null,
					'noextract' => (isset($item['noextract']) && $item['noextract']) ? true : false
				);
			}
		}

		return $this->files;
	}

	public function source() {
		return $this->_root() . '/source.zip';
	}
}