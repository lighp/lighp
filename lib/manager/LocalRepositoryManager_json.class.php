<?php
namespace lib\manager;

class LocalRepositoryManager_json extends LocalRepositoryManager {
	protected function _buildPackage($metadata, $files) {
		$metadata = new \lib\entities\PackageMetadata($metadata);

		$pkg = new \lib\InstalledPackage($this, $metadata, $files);

		return $pkg;
	}

	public function name() {
		return null;
	}

	public function getPackagesList() {
		$file = $this->dao->open('packagecontrol/packages');
		$metadatas = $file->read();

		$list = array();

		foreach($metadatas as $metadata) {
			$list[] = new \lib\entities\PackageMetadata($metadata);
		}

		return $list;
	}

	public function getPackage($pkgName) {
		$metadataFile = $this->dao->open('packagecontrol/packages');
		$metadatas = $metadataFile->read();

		$filesFile = $this->dao->open('packagecontrol/files');

		foreach($metadatas as $metadata) {
			if ($metadata['name'] == $pkgName) {
				$files = $filesFile->read();

				$pkgFiles = $files->filter(array('pkg' => $pkgName));
				return $this->_buildPackage($metadata, $pkgFiles);
			}
		}
	}

	public function getPackageMetadata($pkgName) {
		$metadataFile = $this->dao->open('packagecontrol/packages');
		$metadatas = $metadataFile->read();

		foreach($metadatas as $metadata) {
			if ($metadata['name'] == $pkgName) {
				return new \lib\entities\PackageMetadata($metadata);
			}
		}
	}

	public function packageExists($pkgName) {
		$metadataFile = $this->dao->open('packagecontrol/packages');
		$metadatas = $metadataFile->read();

		foreach($metadatas as $metadata) {
			if ($metadata['name'] == $pkgName) {
				return true;
			}
		}

		return false;
	}

	public function getPackageFile($path) {
		$filesFile = $this->dao->open('packagecontrol/files');
		$files = $filesFile->read();

		$path = (substr($path, 0, 1) == '/') ? substr($path, 1) : $path;

		foreach($files as $file) {
			$filePath = $file['path'];
			$filePath = (substr($filePath, 0, 1) == '/') ? substr($filePath, 1) : $filePath;

			if ($filePath == $path) {
				return $file;
			}
		}
	}

	public function _runPreRemoveScript(\lib\InstalledPackage $pkg) {
		$removeScriptsDirPath = __DIR__.'/../../var/lib/packagemanager/remove-scripts';
		$removeScriptPath = $removeScriptsDirPath . '/' . $pkg->metadata()['name'] . '.php.txt';

		if (file_exists($removeScriptPath)) {
			$removeScript = file_get_contents($removeScriptPath);

			$removeScript = preg_replace('#^\<\?(php)?#i', '', $removeScript);
			$removeScript = preg_replace('#\?\>$#i', '', $removeScript);

			chdir(__DIR__.'/../..'); //Change working directory to the framework's root path

			$removeFn = create_function('', $removeScript);

			try {
				call_user_func_array($removeFn, array());
			} catch (\Exception $e) {}

			unlink($removeScriptPath);
		}
	}

	protected function _deleteFiles(\lib\InstalledPackage $pkg) {
		$pkgFiles = $pkg->files();

		$root = __DIR__ . '/../../'; //Root folder

		foreach($pkgFiles as $i => $fileData) {
			$filePath = $root . '/' . $fileData['path'];

			if (file_exists($filePath)) {
				//Pre-check
				if (isset($fileData['md5sum']) && !empty($fileData['md5sum'])) { //If a md5 checksum is specified
					$fileMd5 = md5_file($filePath); //Calculate the MD5 sum of the existing file

					if ($fileData['md5sum'] != $fileMd5) { //If checksums are different
						continue; //Do not delete the file
					}
				}

				if (!unlink($filePath)) { //Delete this file
					throw new \RuntimeException('Cannot delete file "'.$filePath.'"');
				}

				//Delete parent folders while they are empty
				do {
					$parentDirPath = dirname((isset($parentDirPath)) ? $parentDirPath : $filePath);
					$parentDir = opendir($parentDirPath);

					$isEmpty = true;

					if ($parentDir === false) {
						continue;
					}

					while (false !== ($entry = readdir($parentDir))) {
						if ($entry == '.' || $entry == '..') { continue; }

						$isEmpty = false;
						break;
					}
					closedir($parentDir);

					if ($isEmpty) {
						rmdir($parentDirPath);
					}
				} while($isEmpty);
			}
		}
	}

	public function _unregister(\lib\InstalledPackage $pkg, \core\dao\json\Collection &$metadatas, \core\dao\json\Collection &$files) {
		$pkgName = $pkg->metadata()['name'];

		foreach($metadatas as $i => $item) {
			if ($item['name'] == $pkgName) {
				unset($metadatas[$i]);
				break;
			}
		}

		foreach($files as $i => $item) {
			if ($item['pkg'] == $pkgName) {
				unset($files[$i]);
			}
		}
	}

	public function remove($packages) {
		$pkgList = array(); //Array in which valid packages will be stored

		//Arguments processing
		if (is_array($packages)) {
			foreach($packages as $key => $pkg) {
				if ($pkg instanceof \lib\InstalledPackage) {
					$pkgList[] = $pkg;
				} else {
					throw new \InvalidArgumentException('Invalid argument, packages must be an instance of "\lib\InstalledPackage" or an array of "\lib\InstalledPackage"');
				}
			}

			if (count($packages) == 0) {
				return;
			}
		} else if ($packages instanceof \lib\InstalledPackage) {
			$pkgList[] = $packages;
		} else {
			throw new \InvalidArgumentException('Invalid argument, packages must be an instance of "\lib\InstalledPackage" or an array of "\lib\InstalledPackage"');
		}

		//Local DB
		$metadatasFile = $this->dao->open('packagecontrol/packages');
		$metadatas = $metadatasFile->read();

		$filesFile = $this->dao->open('packagecontrol/files');
		$files = $filesFile->read();

		foreach($pkgList as $pkg) { //Uninstall packages
			$this->_runPreRemoveScript($pkg);
			$this->_deleteFiles($pkg); //Deelete this package's files
			$this->_unregister($pkg, $metadatas, $files); //Unregister this package from the local DB
		}

		//Write new data
		$metadatasFile->write($metadatas);
		$filesFile->write($files);
	}
}