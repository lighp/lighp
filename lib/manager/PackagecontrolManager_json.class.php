<?php
namespace lib\manager;

//Rename this to RemoteRepositoryManager_json
class PackagecontrolManager_json extends PackagecontrolManager {
	public function getPackagesList() {
		$remoteRepositories = $this->getRemoteRepositoriesList();

		$list = array();

		foreach($remoteRepositories as $repo) {
			try {
				$list += $repo->getPackagesList();
			} catch(\Exception $e) {
				continue;
			}
		}

		return $list;
	}

	public function getPackageMetadata($pkgName) {
		$remoteRepositories = $this->getRemoteRepositoriesList();

		$list = array();

		foreach($remoteRepositories as $repo) {
			try {
				if ($repo->packageExists($pkgName)) {
					$list[] = $repo->getPackageMetadata($pkgName);
				}
			} catch(\Exception $e) {
				continue;
			}
		}

		$maxVersion = 0;
		$selectedPkg = null;

		foreach($list as $pkg) {
			$version = $pkg['version'];
			if (version_compare($version, $maxVersion, '>')) {
				$maxVersion = $version;
				$selectedPkg = $pkg;
			}
		}

		return $selectedPkg;
	}

	public function getPackage($pkgName) {
		$remoteRepositories = $this->getRemoteRepositoriesList();

		$list = array();

		foreach($remoteRepositories as $repo) {
			try {
				if ($repo->packageExists($pkgName)) {
					$list[] = $repo->getPackage($pkgName);
				}
			} catch(\Exception $e) {
				continue;
			}
		}

		$maxVersion = 0;
		$selectedPkg = null;

		foreach($list as $pkg) {
			$version = $pkg->metadata()['version'];
			if (version_compare($version, $maxVersion, '>')) {
				$maxVersion = $version;
				$selectedPkg = $pkg;
			}
		}

		return $selectedPkg;
	}

	public function packageExists($pkgName) {
		$remoteRepositories = $this->getRemoteRepositoriesList();

		$list = array();

		foreach($remoteRepositories as $repo) {
			try {
				if ($repo->packageExists($pkgName)) {
					return true;
				}
			} catch(\Exception $e) {
				continue;
			}
		}

		return false;
	}

	public function search($query, \lib\Repository $repository = null) {
		$escapedQuery = preg_quote($query);

		$searchPlaces = array('name','title','subtitle','description','url','maintainer');

		if (!empty($repository)) {
			$packages = $repository->getPackagesList();
		} else {
			$packages = $this->getPackagesList();
		}

		$nbrPackages = count($packages);
		$hitsPower = strlen((string) $nbrPackages);
		$hitsFactor = pow(10, $hitsPower);

		$matchingPackages = array();

		foreach($packages as $i => $pkg) {
			$hits = 0;

			foreach($searchPlaces as $key) {
				preg_match_all('#'.$escapedQuery.'#i', $pkg[$key], $matches);

				if (is_array($matches[0]) && count($matches[0]) > 0) {
					$hits += count($matches[0]);
				}
			}

			if ($hits > 0) {
				$matchingPackages[$hits * $hitsFactor + ($nbrPackages - $i)] = $pkg;
			}
		}

		return array_values($matchingPackages);
	}

	protected function _mergeTrees($tree1, $tree2) {
		$mergedTree = $tree1;

		foreach ($tree2 as $depName => $dep) {
			if (!isset($mergedTree[$depName])) {
				$mergedTree[$depName] = $dep;
			} else {
				$mergedTree[$depName] = $this->_mergeDependencies($mergedTree[$depName], $dep);
			}
		}

		return $mergedTree;
	}

	protected function _mergeDependencies($dep1, $dep2) {
		if ($dep1['name'] != $dep2['name']) {
			return null;
		}

		$mergedDep = array_merge($dep1, $dep2);

		//If specific versions are specified
		if (!empty($dep1['maxVersion']) && !empty($dep2['maxVersion'])) { //If both have a max. version
			//The max. version is the lower one
			if (version_compare($dep1['maxVersion'], $dep2['maxVersion'], '=')) { //If both versions are equivalent
				$mergedDep['maxVersion'] = $dep2['maxVersion']; //Keep this dependency's version

				//The max. operator is the more restrictive
				$mergedDep['maxVersionStrict'] = ($dep1['maxVersionStrict'] || $dep2['maxVersionStrict']);
			} else if (version_compare($dep1['maxVersion'], $dep2['maxVersion'], '>')) { //If the actual max. version is greater than this one
				//Keep this dependency's version and operator
				$mergedDep['maxVersion'] = $dep2['maxVersion'];
				$mergedDep['maxVersionStrict'] = $dep2['maxVersionStrict'];
			} else { //If the actual max. version is lower than this one
				$mergedDep['maxVersion'] = $dep1['maxVersion'];
				$mergedDep['maxVersionStrict'] = $dep1['maxVersionStrict'];
			}
		}
		if (!empty($dep1['minVersion']) && !empty($dep2['minVersion'])) { //If both have a min. version
			//The min. version is the greater one
			if (version_compare($dep1['minVersion'], $dep2['minVersion'], '=')) { //If both versions are equivalent
				$mergedDep['minVersion'] = $dep2['minVersion']; //Keep this dependency's version

				//The min. operator is the more restrictive
				$mergedDep['minVersionStrict'] = ($dep1['minVersionStrict'] || $dep2['minVersionStrict']);
			} else if (version_compare($dep1['minVersion'], $dep2['minVersion'], '>')) { //If the actual min. version is greater than this one
				$mergedDep['minVersion'] = $dep1['minVersion'];
				$mergedDep['minVersionStrict'] = $dep1['minVersionStrict'];
			} else { //If the actual min. version is lower than this one
				//Keep this dependency's version and operator
				$mergedDep['minVersion'] = $dep2['minVersion'];
				$mergedDep['minVersionStrict'] = $dep2['minVersionStrict'];
			}
		}

		//merge parents and levels
		$mergedDep['parents'] = array_merge($dep1['parents'], $dep2['parents']);
		$mergedDep['level'] = ($dep1['level'] > $dep2['level']) ? $dep1['level'] : $dep2['level'];

		return $mergedDep;
	}

	public function _compareDependenciesLevel($dep1, $dep2) {
		return $dep1['level'] - $dep2['level'];
	}

	protected function _createTreeNode($pkgName) {
		return array(
			'name' => $pkgName,
			'maxVersion' => null,
			'maxVersionStrict' => false,
			'minVersion' => null,
			'minVersionStrict' => false,
			'parents' => array(),
			'level' => 0
		);
	}

	protected function _calculateTree(\lib\Package $pkg, LocalRepositoryManager $localRepository) {
		$pkgName = $pkg->metadata()['name']; //Package's name

		$tree = array(); //Package's tree

		$tree[$pkgName] = $this->_createTreeNode($pkgName); //Firstly, create this package's node

		//Here are allowed operators to target specific versions
		$operators = array(
			'<' => -2,
			'<=' => -1,
			'=' => 0,
			'>=' => 1,
			'>' => 2
		);

		$remoteRepositories = $this->getRemoteRepositoriesList(); //Remote repositories

		//Then add dependencies
		$pkgDeps = $pkg->depends();
		foreach ($pkgDeps as $dep) {
			$depToAdd = $this->_createTreeNode($dep['name']);

			$depToAdd['parents'][] = $pkgName;
			$depToAdd['level'] = 1;

			//If specific versions are specified
			if (isset($pkgDep['versionOperator']) && isset($pkgDep['version'])) {
				//Versionning support
				$pkgDepOperator = $operators[$pkgDep['operator']];

				if ($pkgDepOperator <= 0) { //If the operator is <, <= or =
					$depToAdd['maxVersion'] = $pkgDep['version'];
					$depToAdd['maxVersionStrict'] = ($pkgDep['operator'] <= -2);
				}
				if ($pkgDepOperator >= 0) { //If the operator is >, >= or =
					$depToAdd['minVersion'] = $pkgDep['version'];
					$depToAdd['minVersionStrict'] = ($pkgDep['operator'] >= 2);
				}
			}

			//Find dependency in repositories
			$depPkg = null;

			//Check if the requiered package is already installed
			$localPkg = $this->_resolveDependency($dep, $localRepository);
			if ($localPkg === null) { //No => we must download it from remote repositories
				$found = false;
				foreach($remoteRepositories as $repo) {
					$remotePkg = $this->_resolveDependency($dep, $repo);

					if ($remotePkg !== null) { //Package found
						$depPkg = $remotePkg;
						$found = true;
						break;
					}
				}

				if (!$found) { //Package not found, trigger an error
					throw new \RuntimeException('Cannot resolve the dependency tree : package "'.$pkgName.'" requires "'.$dep['name'].$dep['versionOperator'].$dep['version'].'"');
				}
			} else {
				$depPkg = $localPkg;
			}

			//Then, calculate this dependency's tree
			$depTree = $this->_calculateTree($depPkg, $localRepository);
			foreach($depTree as $depsDepName => $depsDep) { //Add 1 to every dependencies' levels
				$depTree[$depsDepName]['level']++;
			}
			$depTree[$depToAdd['name']] = $depToAdd;

			$tree = $this->_mergeTrees($tree, $depTree); //Merge this dependency's tree and the final tree
		}

		uasort($tree, array($this, '_compareDependenciesLevel'));

		return $tree;
	}

	protected function _resolveDependency($dep, \lib\Repository $repository) {
		$pkg = $repository->getPackage($dep['name']);

		if (isset($dep['minVersion']) && !version_compare($pkg->metadata()['version'], $dep['minVersion'], $dep['minVersionOperator'])) {
			return;
		}
		if (isset($dep['maxVersion']) && !version_compare($pkg->metadata()['version'], $dep['maxVersion'], $dep['maxVersionOperator'])) {
			return;
		}

		return $pkg;
	}

	protected function _download(\lib\AvailablePackage $package, $zipPath) {
		$source = fopen($package->source(), 'r'); //Source file

		if ($source === false) {
			throw new \RuntimeException('Cannot open package\'s contents from repository "'.$package->source().'"');
		}

		$dest = fopen($zipPath, 'w'); //Destination file

		if ($dest === false) {
			throw new \RuntimeException('Cannot create temporary file "'.$zipPath.'"');
		}

		$copiedBits = stream_copy_to_stream($source, $dest); //Now we can download the source's file...

		fclose($source);
		fclose($dest);

		if ($copiedBits == 0) { //Check if bytes have been copied
			throw new \RuntimeException('Cannot copy package\'s contents from repository "'.$package->source().'" to temporary file "'.$zipPath.'"');
		}
	}

	protected function _extract(\lib\AvailablePackage $package, $zipPath) {
		$pkgFiles = $package->files(); //Get the package's files

		$zip = new \ZipArchive();
		$result = $zip->open($zipPath, \ZipArchive::CREATE); //Open the package's source
		
		if ($result !== true) {
			throw new \RuntimeException('Cannot open package\'s contents from temporary file "'.$zipPath.'" : ZipArchive error #'.$result);
		}

		$root = __DIR__ . '/../../'; //Root folder

		$filesToCopy = array();

		//Check if already goes the right way, and store files to copy in an array
		for ($i = 0; $i < $zip->numFiles; $i++) { //For each file
			//Get info about it
			$itemStat = $zip->statIndex($i); //From the archive

			$itemName = preg_replace('#^src/#', '', $itemStat['name']);

			if (empty($itemName) || substr($itemName, -1) == '/') { //Is this a directory ?
				continue;
			}

			$itemPkgData = $pkgFiles[$itemName]; //From the package

			//Pre-check

			if ($itemPkgData['noextract']) { continue; } //Skip this item

			$itemDestPath = $root . '/' . $itemName; //Here is the final file's destination

			if (file_exists($itemDestPath)) { //File already exists
				throw new \RuntimeException('File collision detected : "'.$itemDestPath.'" already exists');
			}

			//Add this file in the list
			$filesToCopy[] = array(
				'sourcePath' => $itemStat['name'],
				'name' => $itemName,
				'destPath' => $itemDestPath,
				'md5sum' => $itemPkgData['md5sum']
			);
		}

		//Now, extract files
		foreach($filesToCopy as $item) {
			//Re-create parent dirs if they are not
			$parentDir = dirname($item['destPath']);
			if (!is_dir($parentDir)) {
				if (!mkdir($parentDir, 0777, true)) {
					throw new \RuntimeException('Cannot create directory "'.$parentDir.'"');
				}
				chmod($parentDir, 0777);
			}

			$itemSource = $zip->getStream($item['sourcePath']); //Get the file's stream
			$itemDest = fopen($item['destPath'], 'w'); //Destination file

			$copiedBits = stream_copy_to_stream($itemSource, $itemDest); //Extract current file...
			fclose($itemDest);

			if ($copiedBits == 0) { //Nothing copied -> error ?
				throw new \RuntimeException('Cannot extract file "'.$item['sourcePath'].'" from "'.$zipPath.'" to "'.$item['destPath'].'"');
			}

			//Post-check

			if (!empty($item['md5sum'])) { //If a md5 checksum is specified
				$destMd5 = md5_file($item['destPath']); //Calculate the MD5 sum of the extracted file

				if ($item['md5sum'] != $destMd5) { //If checksums are different
					unlink($item['destPath']); //Delete copied file
					throw new \RuntimeException('Bad file checksum : "'.$item['destPath'].'". This file is corrupted.');
				}
			}

			chmod($item['destPath'], 0777); //Allow read-write-execute for all users - better for maintaining the framework
		}

		$zip->close(); //Close the package's source
	}

	protected function _register(\lib\AvailablePackage $package, \core\dao\json\Collection &$metadatas, \core\dao\json\Collection &$files) {
		$metadataItem = $this->dao->createItem($package->metadata()->toArray());
		$metadatas[] = $metadataItem;

		$pkgFiles = $package->files();
		foreach($pkgFiles as $filePath => $fileData) {
			$fileItem = $this->dao->createItem(array(
				'path' => $filePath,
				'pkg' => $package->metadata()['name']
			));

			if (isset($fileData['md5sum'])) {
				$fileItem['md5sum'] = $fileData['md5sum'];
			}

			$files[] = $fileItem;
		}
	}

	public function install($packages, LocalRepositoryManager $localRepository) {
		$pkgList = array(); //Array in which valid packages will be stored

		//Arguments processing
		if (is_array($packages)) {
			foreach($packages as $key => $pkg) {
				if ($pkg instanceof \lib\AvailablePackage) {
					$pkgList[] = $pkg;
				} else {
					throw new \InvalidArgumentException('Invalid argument, packages must be an instance of "\lib\AvailablePackage" or an array of "\lib\AvailablePackage"');
				}
			}

			if (count($packages) == 0) {
				return;
			}
		} else if ($packages instanceof \lib\AvailablePackage) {
			$pkgList[] = $packages;
		} else {
			throw new \InvalidArgumentException('Invalid argument, packages must be an instance of "\lib\AvailablePackage" or an array of "\lib\AvailablePackage"');
		}

		//First, resolve the dependency tree
		$tree = array();

		foreach ($pkgList as $pkg) { //For each package to install
			$pkgTree = $this->_calculateTree($pkg, $localRepository);

			$tree = $this->_mergeTrees($tree, $pkgTree);
		}

		$remoteRepositories = $this->getRemoteRepositoriesList(); //Remote repositories
		$packagesToInstall = array(); //Packages to install

		//Check if we can install all packages
		foreach($tree as $node) {
			if ($node['level'] == 0) { //If node's level is 0 => it's a package asked by the user
				$found = false;
				foreach($pkgList as $pkg) {
					if ($pkg->metadata()['name'] == $node['name']) {
						$packagesToInstall[] = $pkg;
						$found = true;
						break;
					}
				}
				if ($found) {
					continue;
				}
			}

			//First check if the requiered package is already installed
			$localPkg = $this->_resolveDependency($node, $localRepository);

			if ($localPkg === null) { //No => we must download it from remote repositories
				$found = false;
				foreach($remoteRepositories as $repo) {
					$remotePkg = $this->_resolveDependency($node, $repo);

					if ($remotePkg !== null) { //Package found
						$packagesToInstall[] = $remotePkg;
						$found = true;
						break;
					}
				}

				if (!$found) { //Package not found, trigger an error
					$pkgsRequiringThisDep = implode('", "', $node['parents']);
					$minVersion = (isset($node['minVersion'])) ? (($node['minVersionStrict']) ? '>' : '>=').$node['minVersion'] : '';
					$maxVersion = (isset($node['maxVersion'])) ? (($node['maxVersionStrict']) ? '<' : '<=').$node['maxVersion'] : '';

					throw new \RuntimeException('Cannot resolve the dependency tree : packages "'.$pkgsRequiringThisDep.'" require "'.$node['name'].$maxVersion.$minVersion.'"');
				}
			}
		}

		//Second, download the packages' sources
		$tmpDir = new \core\TemporaryDirectory(); //Create a temporary folder

		foreach($packagesToInstall as $pkg) { //Download each package
			$zipPath = $tmpDir->root().'/'.$pkg->metadata()['name'].'.zip';

			$this->_download($pkg, $zipPath);
		}

		//Then, check and extract zipped files
		foreach($packagesToInstall as $pkg) {
			$zipPath = $tmpDir->root().'/'.$pkg->metadata()['name'].'.zip';

			$this->_extract($pkg, $zipPath);
		}

		//Finally, register new packages in the local DB
		$metadatasFile = $this->dao->open('packagecontrol/packages');
		$metadatas = $metadatasFile->read();

		$filesFile = $this->dao->open('packagecontrol/files');
		$files = $filesFile->read();

		foreach($packagesToInstall as $pkg) {
			$this->_register($pkg, $metadatas, $files);
		}

		$metadatasFile->write($metadatas);
		$filesFile->write($files);
	}

	public function calculateUpgrades(LocalRepositoryManager $localRepository) {
		$installedPkgs = $localRepository->getPackagesList();

		$upgrades = array();

		foreach ($installedPkgs as $installedPkg) {
			$remotePkg = $this->getPackageMetadata($installedPkg['name']);

			if ($remotePkg !== null && version_compare($remotePkg['version'], $installedPkg['version'], '>')) {
				$upgrades[] = $this->getPackage($installedPkg['name']);
			}
		}

		return $upgrades;
	}

	public function getRemoteRepositoriesList() {
		$file = $this->dao->open('packagecontrol/repositories');
		$repos = $file->read();

		$list = array();

		foreach($repos as $repoMetadata) {
			try {
				$list[] = new \lib\RemoteRepository($repoMetadata['name'], $repoMetadata['url']);
			} catch (\Exception $e) {
				continue;
			}
		}

		return $list;
	}

	public function getRemoteRepository($repoName) {
		$file = $this->dao->open('packagecontrol/repositories');
		$repos = $file->read();

		$list = array();

		foreach($repos as $repoMetadata) {
			if ($repoMetadata['name'] == $repoName) {
				return new \lib\RemoteRepository($repoMetadata['name'], $repoMetadata['url']);
			}
		}
	}

	public function remoteRepositoryExists($repoName) {
		$file = $this->dao->open('packagecontrol/repositories');
		$repos = $file->read();

		$list = array();

		foreach($repos as $repoMetadata) {
			if ($repoMetadata['name'] == $repoName) {
				return true;
			}
		}

		return false;
	}

	public function addRemoteRepository($repoName, $repoUrl) {
		if (empty($repoName) || $this->remoteRepositoryExists($repoName)) {
			throw new \InvalidArgumentException('Invalid repository name');
		}
		if (empty($repoUrl)) {
			throw new \InvalidArgumentException('Invalid repository url');
		}

		$file = $this->dao->open('packagecontrol/repositories');
		$repos = $file->read();

		$newRepo = $this->dao->createItem(array(
			'name' => $repoName,
			'url' => $repoUrl
		));

		$repos[] = $newRepo;

		$file->write($repos);
	}

	public function removeRemoteRepository($repoName) {
		$file = $this->dao->open('packagecontrol/repositories');
		$repos = $file->read();

		foreach($repos as $i => $repoMetadata) {
			if ($repoMetadata['name'] == $repoName) {
				unset($repos[$i]);
				break;
			}
		}

		$file->write($repos);
	}
}