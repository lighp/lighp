<?php

namespace ctrl\backend\packagecontrol;

use core\http\HTTPRequest;

class PackagecontrolController extends \core\BackController {
	protected function _addBreadcrumb($page = array()) {
		$breadcrumb = array(
			array(
				'url' => $this->app->router()->getUrl('main', 'showModule', array(
					'module' => $this->module()
				)),
				'title' => 'Gestionnaire de paquets'
			)
		);

		$this->page()->addVar('breadcrumb', array_merge($breadcrumb, array($page)));
	}

	public function executeSearchPackage(HTTPRequest $request) {
		$this->page()->addVar('title', 'Rechercher un paquet');
		$this->_addBreadcrumb();

		$packageManager = $this->managers->getManagerOf('Packagecontrol');

		$repositories = $packageManager->getRemoteRepositoriesList();
		$repoList = array();
		foreach($repositories as $repo) {
			$repoList[] = array(
				'name' => $repo->name(),
				'selected?' => ($request->getExists('repo') && $request->getData('repo') == $repo->name())
			);
		}
		$this->page()->addVar('repositories', $repoList);

		if ($request->getExists('q')) {
			$query = $request->getData('q');
			$repoName = $request->getData('repo');

			if (empty($query)) {
				$this->page()->addVar('emptyQuery?', true);
				return;
			}

			if (empty($repoName)) {
				$repo = null;
			} else {
				$repo = $packageManager->getRemoteRepository($repoName);
			}

			$packages = $packageManager->search($query, $repo);

			$this->page()->addVar('searched?', true);
			$this->page()->addVar('searchQuery', $query);
			$this->page()->addVar('packages?', (count($packages) > 0));
			$this->page()->addVar('packages', $packages);
		}
	}

	public function executeInstallPackage(HTTPRequest $request) {
		$this->page()->addVar('title', 'Installer un paquet');
		$this->_addBreadcrumb();

		$packageManager = $this->managers->getManagerOf('Packagecontrol');
		$localRepo = $this->managers->getManagerOf('LocalRepository');

		$pkgName = $request->getData('name');

		$pkg = $packageManager->getPackage($pkgName);

		$localPkg = $localRepo->getPackage($pkgName);
		$alreadyInstalled = false;
		$isUpgrade = false;
		if (!empty($localPkg)) {
			if (version_compare($pkg->metadata()['version'], $localPkg->metadata()['version'], '>')) {
				$isUpgrade = true;
			} else {
				$alreadyInstalled = true;
			}
		}

		$files = array();
		foreach($pkg->files() as $path => $data) {
			$files[] = array('path'=> $path);
		}

		$this->page()->addVar('package', $pkg);
		$this->page()->addVar('filesList', $files);
		$this->page()->addVar('alreadyInstalled?', $alreadyInstalled);
		$this->page()->addVar('update?', $isUpgrade);
		$this->page()->addVar('unsafePkg?', $pkg->unsafe());
		
		if ($request->postExists('check') && !$alreadyInstalled) {
			try {
				$packageManager->install($pkg, $localRepo);
			} catch (\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('installed?', true);
		}
	}

	public function executeShowPackage(HTTPRequest $request) {
		$this->page()->addVar('title', 'Afficher un paquet');
		$this->_addBreadcrumb();

		$localRepo = $this->managers->getManagerOf('LocalRepository');
		$packageManager = $this->managers->getManagerOf('Packagecontrol');

		$pkgName = $request->getData('name');

		$localPkg = $localRepo->getPackage($pkgName);
		$remotePkg = $packageManager->getPackage($pkgName);

		$alreadyInstalled = false;
		$isUpgrade = false;

		$pkg = (!empty($remotePkg)) ? $remotePkg : $localPkg;

		if (empty($pkg)) {
			return;
		}

		$this->page()->addVar('title', $pkg->metadata()['title']);

		if (!empty($localPkg) && !empty($remotePkg)) {
			if (version_compare($remotePkg->metadata()['version'], $localPkg->metadata()['version'], '>')) {
				$isUpgrade = true;
			} else {
				$alreadyInstalled = true;
			}
		} else if(!empty($localPkg)) {
			$alreadyInstalled = true;
		}

		$files = array();
		foreach($pkg->files() as $key => $data) {
			if ($pkg instanceof \lib\InstalledPackage) {
				$files[] = array('path' => $data['path']);
			} else {
				$files[] = array('path' => $key);
			}
		}

		$this->page()->addVar('package', $pkg);
		$this->page()->addVar('filesNbr', count($files));
		$this->page()->addVar('filesList', $files);
		$this->page()->addVar('alreadyInstalled?', $alreadyInstalled);
		$this->page()->addVar('update?', $isUpgrade);
		$this->page()->addVar('repository', $localRepo);
		$this->page()->addVar('unsafePkg?', $pkg->unsafe());
	}

	public function executeRemovePackage(HTTPRequest $request) {
		$this->page()->addVar('title', 'Supprimer un paquet');
		$this->_addBreadcrumb();

		$localRepo = $this->managers->getManagerOf('LocalRepository');

		$pkgName = $request->getData('name');

		$pkg = $localRepo->getPackage($pkgName);
		$isInstalled = $localRepo->packageExists($pkgName);

		$this->page()->addVar('package', $pkg);
		$this->page()->addVar('isInstalled?', $isInstalled);

		if ($request->postExists('check') && $isInstalled) {
			try {
				$localRepo->remove($pkg);
			} catch (\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('removed?', true);
		}
	}

	public function executeUpdateCache(HTTPRequest $request) {
		$this->page()->addVar('title', 'Synchroniser le cache');
		$this->_addBreadcrumb();

		$packageManager = $this->managers->getManagerOf('Packagecontrol');

		try {
			$packageManager->updateCache();
		} catch(\Exception $e) {
			$this->page()->addVar('error', $e->getMessage());
			return;
		}

		$this->page()->addVar('updated?', true);
	}

	public function executeUpgradePackages(HTTPRequest $request) {
		$this->page()->addVar('title', 'Mettre &agrave; jour les paquets');
		$this->_addBreadcrumb();

		$packageManager = $this->managers->getManagerOf('Packagecontrol');
		$localRepo = $this->managers->getManagerOf('LocalRepository');

		$packageManager->updateCache();

		$upgrades = $packageManager->calculateUpgrades($localRepo);
		$upgradesNbr = count($upgrades);

		$this->page()->addVar('upgrades', $upgrades);
		$this->page()->addVar('upgradesNbr', $upgradesNbr);
		$this->page()->addVar('upgrades?', ($upgradesNbr > 0));

		if ($request->postExists('check')) {
			try {
				$packageManager->install($upgrades, $localRepo);
			} catch (\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('upgraded?', true);
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

			$this->page()->addVar('downloadSize', $downloadSize);
			$this->page()->addVar('extractedSize', $extractedSize);
			$this->page()->addVar('netSize', $netSize);
		}
	}

	public function executeAddRepository(HTTPRequest $request) {
		$this->page()->addVar('title', 'Ajouter un d&eacute;p&ocirc;t');
		$this->_addBreadcrumb();

		$packageManager = $this->managers->getManagerOf('Packagecontrol');

		if ($request->postExists('repo-url')) {
			$repoName = $request->postData('repo-name');
			$this->page()->addVar('repo-name', $repoName);
			$repoUrl = $request->postData('repo-url');
			$this->page()->addVar('repo-url', $repoUrl);

			try {
				$packageManager->addRemoteRepository($repoName, $repoUrl);
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('added?', true);
		}
	}

	public function executeRemoveRepository(HTTPRequest $request) {
		$this->page()->addVar('title', 'Supprimer un d&eacute;p&ocirc;t');
		$this->_addBreadcrumb();

		$packageManager = $this->managers->getManagerOf('Packagecontrol');

		$repoName = $request->getData('name');

		$repo = $packageManager->getRemoteRepository($repoName);
		$this->page()->addVar('repository', $repo);

		if ($request->postExists('check')) {
			try {
				$packageManager->removeRemoteRepository($repoName);
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('removed?', true);
		}
	}

	// LISTERS

	public function listInstalledPackages() {
		$packageManager = $this->managers->getManagerOf('Packagecontrol');

		$repo = $this->managers->getManagerOf('LocalRepository');
		$packages = $repo->getPackagesList();

		$list = array();

		foreach($packages as $pkg) {
			$item = array(
				'title' => $pkg->title().' ('.$pkg->name().' '.$pkg->version().')',
				'shortDescription' => $pkg->subtitle(),
				'vars' => array('name' => $pkg->name())
			);

			$list[] = $item;
		}

		return $list;
	}

	public function listRepositories() {
		$packageManager = $this->managers->getManagerOf('Packagecontrol');

		$repos = $packageManager->getRemoteRepositoriesList();
		$list = array();

		foreach($repos as $repo) {
			$item = array(
				'title' => $repo->name(),
				'shortDescription' => $repo->url(),
				'vars' => array('name' => $repo->name())
			);

			$list[] = $item;
		}

		return $list;
	}
}