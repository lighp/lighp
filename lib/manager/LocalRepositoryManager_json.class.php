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

	protected function _deleteFiles(\lib\InstalledPackage $pkg) {
		$pkgFiles = $pkg->files();

		$root = __DIR__ . '/../../'; //Root folder

		foreach($pkgFiles as $i => $fileData) {
			$filePath = $root . '/' . $fileData['path'];

			if (file_exists($filePath)) {
				if (!unlink($filePath)) {
					throw new \RuntimeException('Cannot delete file "'.$filePath.'"');
				}

				do {
					$parentDirPath = dirname($filePath);
					$parentDir = dir($parentDirPath);
					$nbrFiles = 0;
					while (false !== ($entry = $parentDir->read())) {
						if ($entry == '.' || $entry == '..') { continue; }
						$nbrFiles++;
					}

					if ($nbrFiles == 0) {
						rmdir($parentDir);
					}
				} while($nbrFiles == 0);
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
			$this->_deleteFiles($pkg); //Deelete this package's files
			$this->_unregister($pkg, $metadatas, $files); //Unregister this package from the local DB
		}

		//Write new data
		$metadatasFile->write($metadatas);
		$filesFile->write($files);
	}
}