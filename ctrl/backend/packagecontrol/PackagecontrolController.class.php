<?php
namespace ctrl\backend\packagecontrol;

class PackagecontrolController extends \core\BackController {
	public function executeListPackages(\core\HTTPRequest $request) {
		if (!$request->getExists('repo')) {
			$repo = $this->managers->getManagerOf('LocalRepository');
		} else {
			$repoName = $request->getData('repo');

			$packageManager = $this->managers->getManagerOf('Packagecontrol');
			$repo = $packageManager->getRemoteRepository($repoName);
		}

		$packages = $repo->getPackagesList();

		foreach ($packages as $i => $metadata) {
			$metadata = $metadata->toArray();

			if ($repo instanceof \lib\manager\LocalRepositoryManager) {
				$metadata['installed'] = true;
			}

			$packages[$i] = $metadata;
		}

		$this->page->addVar('packages', $packages);
	}

	public function executeSearchPackage(\core\HTTPRequest $request) {
		$packageManager = $this->managers->getManagerOf('Packagecontrol');

		$repositories = $packageManager->getRemoteRepositoriesList();
		$repoList = array();
		foreach($repositories as $repo) {
			$repoList[] = array(
				'name' => $repo->name(),
				'selected?' => ($request->getExists('repo') && $request->getData('repo') == $repo->name())
			);
		}
		$this->page->addVar('repositories', $repoList);

		if ($request->getExists('q')) {
			$query = $request->getData('q');
			$repoName = $request->getData('repo');

			if (empty($query)) {
				$this->page->addVar('emptyQuery?', true);
				return;
			}

			if (empty($repoName)) {
				$repo = null;
			} else {
				$repo = $packageManager->getRemoteRepository($repoName);
			}

			$packages = $packageManager->search($query, $repo);

			$this->page->addVar('searched?', true);
			$this->page->addVar('searchQuery', $query);
			$this->page->addVar('packages?', (count($packages) > 0));
			$this->page->addVar('packages', $packages);
		}
	}

	public function executeInstallPackage(\core\HTTPRequest $request) {
		$packageManager = $this->managers->getManagerOf('Packagecontrol');
		$localRepo = $this->managers->getManagerOf('LocalRepository');

		$pkgName = $request->getData('name');

		$pkg = $packageManager->getPackage($pkgName);

		$localPkg = $localRepo->getPackage($pkgName);
		$alreadyInstalled = false;
		$isUpdate = false;
		if (!empty($localPkg)) {
			if (version_compare($pkg->metadata()['version'], $localPkg->metadata()['version'], '>')) {
				$isUpdate = true;
			} else {
				$alreadyInstalled = true;
			}
		}

		$files = array();
		foreach($pkg->files() as $path => $data) {
			$files[] = array('path'=> $path);
		}

		$this->page->addVar('package', $pkg);
		$this->page->addVar('filesList', $files);
		$this->page->addVar('alreadyInstalled?', $alreadyInstalled);
		$this->page->addVar('update?', $isUpdate);
		$this->page->addVar('unsafePkg?', (count($pkg->unsafeFiles()) > 0));
		
		if ($request->postExists('check') && !$alreadyInstalled) {
			try {
				if ($isUpdate) {
					$localRepo->remove($localPkg);
				}

				$packageManager->install($pkg, $localRepo);
			} catch (\Exception $e) {
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			$this->page->addVar('installed?', true);
		}
	}

	public function executeRemovePackage(\core\HTTPRequest $request) {
		$localRepo = $this->managers->getManagerOf('LocalRepository');

		$pkgName = $request->getData('name');

		$pkg = $localRepo->getPackage($pkgName);
		$isInstalled = $localRepo->packageExists($pkgName);

		$this->page->addVar('package', $pkg);
		$this->page->addVar('isInstalled?', $isInstalled);

		if ($request->postExists('check') && $isInstalled) {
			try {
				$localRepo->remove($pkg);
			} catch (\Exception $e) {
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			$this->page->addVar('removed?', true);
		}
	}

	public function executeUpdateCache(\core\HTTPRequest $request) {

	}

	public function executeUpgradePackages(\core\HTTPRequest $request) {
		$packageManager = $this->managers->getManagerOf('Packagecontrol');
		$localRepo = $this->managers->getManagerOf('LocalRepository');

		$upgrades = $packageManager->calculateUpgrades($localRepo);
		$upgradesNbr = count($upgrades);

		$this->page->addVar('upgrades', $upgrades);
		$this->page->addVar('upgradesNbr', $upgradesNbr);
		$this->page->addVar('upgrades?', ($upgradesNbr > 0));

		if ($request->postExists('check')) {
			try {
				$localPkgs = array();
				foreach($upgrades as $pkg) {
					$localPkgs[] = $localRepo->getPackage($pkg->metadata()['name']);
				}
				$localRepo->remove($localPkgs);

				$packageManager->install($upgrades, $localRepo);
			} catch (\Exception $e) {
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			$this->page->addVar('upgraded?', true);
		} else {
			$downloadSize = 0;
			$extractedSize = 0;
			$netSize = 0;

			foreach($upgrades as $pkg) {
				$downloadSize += $pkg->metadata()['size'];
				$extractedSize += $pkg->metadata()['extractedSize'];

				$localPkg = $localRepo->getPackageMetadata($pkg->metadata()['name']);
				$netSize += $pkg->metadata()['extractedSize'] - $localPkg['extractedSize'];
			}

			$this->page->addVar('downloadSize', $downloadSize);
			$this->page->addVar('extractedSize', $extractedSize);
			$this->page->addVar('netSize', $netSize);
		}
	}

	public function executeListRepositories(\core\HTTPRequest $request) {
		$packageManager = $this->managers->getManagerOf('Packagecontrol');

		$repos = $packageManager->getRemoteRepositoriesList();

		$this->page->addVar('repositories', $repos);
	}

	public function executeAddRepository(\core\HTTPRequest $request) {
		$packageManager = $this->managers->getManagerOf('Packagecontrol');

		if ($request->postExists('repo-url')) {
			$repoName = $request->postData('repo-name');
			$this->page->addVar('repo-name', $repoName);
			$repoUrl = $request->postData('repo-url');
			$this->page->addVar('repo-url', $repoUrl);

			try {
				$packageManager->addRemoteRepository($repoName, $repoUrl);
			} catch(\Exception $e) {
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			$this->page->addVar('added?', true);
		}
	}

	public function executeRemoveRepository(\core\HTTPRequest $request) {
		$packageManager = $this->managers->getManagerOf('Packagecontrol');

		$repoName = $request->getData('name');

		$repo = $packageManager->getRemoteRepository($repoName);
		$this->page->addVar('repository', $repo);

		if ($request->postExists('check')) {
			try {
				$packageManager->removeRemoteRepository($repoName);
			} catch(\Exception $e) {
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			$this->page->addVar('removed?', true);
		}
	}
}