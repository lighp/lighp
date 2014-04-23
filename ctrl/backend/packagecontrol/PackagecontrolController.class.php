<?php

namespace ctrl\backend\packagecontrol;

use core\http\HTTPRequest;
use core\fs\Pathfinder;
use core\Config;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Composer\Console\HtmlOutputFormatter;

use Composer\Composer;
use Composer\Factory;
use Composer\Console\Application;
use Composer\IO\NullIO;
use Composer\Package\Version\VersionParser;
use Composer\Json\JsonValidationException;
use Composer\Plugin\CommandEvent;
use Composer\Plugin\PluginEvents;
use Composer\Config as ComposerConfig;

use Composer\Repository\RepositoryInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Repository\CompositeRepository;
use Composer\Repository\PlatformRepository;
use Composer\Repository\ArrayRepository;

use Composer\DependencyResolver\Pool;
use Composer\DependencyResolver\DefaultPolicy;

class PackagecontrolController extends \core\BackController {
	protected $composer;
	protected $io;
	protected $versionParser;

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

	protected function _composerConfig() {
		$this->_initEnv();

		$config = new Config(Factory::getComposerFile());

		return $config;
	}

	protected function _getInput(array $args) {
		$input = new ArrayInput($args);
		$input->setInteractive(false);

		return $input;
	}

	protected function _getOutput() {
		$styles = Factory::createAdditionalStyles();
		$formatter = new HtmlOutputFormatter($styles);
		$output = new BufferedOutput(BufferedOutput::VERBOSITY_NORMAL, true, $formatter);

		return $output;
	}

	protected function _getIO() {
		if (null === $this->io) {
			$this->io = new NullIO;
		}

		return $this->io;
	}

	protected function _versionParser() {
		if (null === $this->versionParser) {
			$this->versionParser = new VersionParser;
		}

		return $this->versionParser;
	}

	protected function _initEnv() {
		chdir(Pathfinder::getRoot());

		$cachePath = Pathfinder::getPathFor('cache') . '/app/composer';
		putenv('COMPOSER_HOME='.$cachePath);

		//Define max. execution time
		if(!ini_get('safe_mode')) { //Detect safe_mode, but sometimes it doesn't work well -> we use the @ operator
			@set_time_limit(300); // 5min
		}
	}

	protected function _getApp() {
		$this->_initEnv();

		$application = new Application();
		$application->setAutoExit(false);

		return $application;
	}

	protected function getComposer(array $config = null, $disablePlugins = false) {
		$io = $this->_getIO();

		$this->_initEnv();

		if (null === $this->composer) {
			try {
				$this->composer = Factory::create($io, $config, $disablePlugins);
			} catch (\InvalidArgumentException $e) {
				$io->write($e->getMessage());
				return false;
			} catch (JsonValidationException $e) {
				$errors = ' - ' . implode(PHP_EOL . ' - ', $e->getErrors());
				$message = $e->getMessage() . ':' . PHP_EOL . $errors;
				throw new JsonValidationException($message);
			}

		}

		return $this->composer;
	}

	protected function _runCommand($args = array()) {
		if (!is_array($args)) {
			$args = array('command' => $args);
		}

		$input = $this->_getInput($args);
		$output = $this->_getOutput();

		$app = $this->_getApp();
		$result = $app->run($input, $output);

		$this->page()->addVar('output', $output->fetch());

		return $result;
	}

	protected function _getInstalledRepo(Composer $composer = null) {
		//Platform packages: virtual packages for things that are installed on the system but are not actually installable by Composer
		//$platformRepo = new PlatformRepository; 

		if ($composer === null) {
			$composer = $this->getComposer();
		}

		$localRepo = $composer->getRepositoryManager()->getLocalRepository();
		//$installedRepo = new CompositeRepository(array($localRepo, $platformRepo));

		return $localRepo;
	}

	protected function _getEnabledRepos(Composer $composer = null) {
		if ($composer === null) {
			$composer = $this->getComposer();
		}

		$enabledRepos = new CompositeRepository($composer->getRepositoryManager()->getRepositories());

		return $enabledRepos;
	}

	protected function _getRepos(Composer $composer = null) {
		$installedRepo = $this->_getInstalledRepo($composer);
		$enabledRepos = $this->_getEnabledRepos($composer)->getRepositories();

		$repos = new CompositeRepository(array_merge(array($installedRepo), $enabledRepos));

		return $repos;
	}

	protected function _getPackage(RepositoryInterface $installedRepo, RepositoryInterface $repos, $name, $version = null) {
		$name = strtolower($name);

		$versionParser = $this->_versionParser();
		
		$constraint = null;
		if ($version) {
			$constraint = $versionParser->parseConstraints($version);
		}

		$policy = new DefaultPolicy();
		$pool = new Pool('dev');
		$pool->addRepository($repos);

		$matchedPackage = null;
		$versions = array();
		$matches = $pool->whatProvides($name, $constraint);
		foreach ($matches as $index => $package) {
			// skip providers/replacers
			if ($package->getName() !== $name) {
				unset($matches[$index]);
				continue;
			}

			// select an exact match if it is in the installed repo and no specific version was required
			if (null === $version && $installedRepo->hasPackage($package)) {
				$matchedPackage = $package;
			}

			$versions[$package->getPrettyVersion()] = $package->getVersion();
			$matches[$index] = $package->getId();
		}

		// select prefered package according to policy rules
		if (!$matchedPackage && $matches && $prefered = $policy->selectPreferedPackages($pool, array(), $matches)) {
			$matchedPackage = $pool->literalToPackage($prefered[0]);
		}

		return array($matchedPackage, $versions);
	}


	public function executeAbout(HTTPRequest $request) {
		$this->page()->addVar('title', '&Agrave; propos');
		$this->_addBreadcrumb();

		$this->_runCommand('about');
	}

	public function executeSearchPackage(HTTPRequest $request) {
		$this->page()->addVar('title', 'Rechercher un paquet');
		$this->_addBreadcrumb();

		$composer = $this->getComposer();
		$config = $composer->getConfig();

		$reposConfig = $config->getRepositories();
		$reposList = array();
		foreach($reposConfig as $name => $repo) {
			$title = $name;
			if (is_int($title)) {
				$title = $repo['url'];
			}

			$selected = false;
			if ($request->getExists('repo')) {
				if ($request->getData('repo') == $name) {
					$selected = true;
				}
			} elseif ($name == 'lighp') {
				$selected = true;
			}

			$reposList[] = array(
				'name' => $name,
				'title' => $title,
				'selected?' => $selected
			);
		}
		$this->page()->addVar('repositories', $reposList);

		if ($request->getExists('q')) {
			$query = $request->getData('q');
			$repoName = $request->getData('repo');

			if (empty($query)) {
				$this->page()->addVar('emptyQuery?', true);
				return;
			}

			if (empty($repoName)) {
				$repos = $this->_getRepos();
			} else {
				$reposConfig = $config->getRepositories();
				$tmpConfig = array('repositories' => array());
				foreach ($reposConfig as $name => $repo) {
					if ($name == $repoName) {
						continue;
					}

					$tmpConfig = array_merge($tmpConfig, array(
						'repositories' => array(
							$name => false
						)
					));
				}

				$config->merge($tmpConfig);

				$configData = $config->all();
				$configData['repositories'] = array_merge($configData['repositories'], $tmpConfig);

				$this->composer = null;
				$composer = $this->getComposer($configData);
				$repos = $this->_getEnabledRepos($composer);
			}

			/*if ($composer = $this->getComposer()) {
				$commandEvent = new CommandEvent(PluginEvents::COMMAND, 'search', $input, $output);
				$composer->getEventDispatcher()->dispatch($commandEvent->getName(), $commandEvent);
			}*/

			$onlyName = false;
			$flags = $onlyName ? RepositoryInterface::SEARCH_NAME : RepositoryInterface::SEARCH_FULLTEXT;
			$packages = $repos->search($query, $flags);

			$this->page()->addVar('searched?', true);
			$this->page()->addVar('searchQuery', $query);
			$this->page()->addVar('packages?', (count($packages) > 0));
			$this->page()->addVar('packages', array_values($packages));
		}
	}

	public function executeInstallPackage(HTTPRequest $request) {
		$this->page()->addVar('title', 'Installer un paquet');
		$this->_addBreadcrumb();

		$pkgName = $request->getData('name');

		if ($request->postExists('check')) {
			$result = $this->_runCommand(array(
				'command' => 'require',
				'packages' => array($pkgName.' dev-master')
			));

			if ($result !== 0) {
				$this->page()->addVar('error', 'Error (process returned '.$result.')');
			} else {
				$this->page()->addVar('installed?', true);
			}
		}
	}

	public function executeShowPackage(HTTPRequest $request) {
		$this->page()->addVar('title', 'Afficher un paquet');
		$this->_addBreadcrumb();

		$installedRepo = $this->_getInstalledRepo();
		$repos = $this->_getRepos();
		$versionParser = $this->_versionParser();

		$pkgName = $request->getData('name');
		list($pkg, $versions) = $this->_getPackage($installedRepo, $repos, $pkgName);

		if (empty($pkg)) {
			return;
		}

		$this->page()->addVar('title', $pkg->getPrettyName());

		$alreadyInstalled = false;
		$isUpgrade = false;

		$localPkgs = $installedRepo->findPackages($pkg->getName());
		if (!empty($localPkgs)) {
			$isLatest = false;
			foreach ($localPkgs as $localPkg) {
				if ($versionParser->normalize($localPkg->getVersion()) == $versionParser->normalize($pkg->getVersion())) {
					$isLatest = true;
					break;
				}
			}

			if (!$isLatest) {
				$isUpgrade = true;
			} else {
				$alreadyInstalled = true;
			}
		}

		$this->page()->addVar('package', $pkg);
		$this->page()->addVar('alreadyInstalled?', $alreadyInstalled);
		$this->page()->addVar('update?', $isUpgrade);
		$this->page()->addVar('requires', array_values($pkg->getRequires()));
		//$this->page()->addVar('repository', $localRepo);
	}

	public function executeRemovePackage(HTTPRequest $request) {
		$this->page()->addVar('title', 'Supprimer un paquet');
		$this->_addBreadcrumb();

		$pkgName = $request->getData('name');

		$configFile = $this->_composerConfig();
		$config = $configFile->read();

		if (!isset($config['require']) || !isset($config['require'][$pkgName])) {
			return;
		}

		$this->page()->addVar('isInstalled?', true);
		$this->page()->addVar('package', $pkgName);

		if ($request->postExists('check')) {
			//Update composer.json
			unset($config['require'][$pkgName]);
			$configFile->write($config);

			//Update
			$result = $this->_runCommand(array(
				'command' => 'update',
				'packages' => array($pkgName)
			));

			if ($result !== 0) {
				$this->page()->addVar('error', 'Error (process returned '.$result.')');
			} else {
				$this->page()->addVar('removed?', true);
			}
		}
	}

	public function executeUpgradePackages(HTTPRequest $request) {
		$this->page()->addVar('title', 'Mettre &agrave; jour les paquets');
		$this->_addBreadcrumb();

		$this->page()->addVar('upgrades?', true);

		if ($request->postExists('check')) {
			$result = $this->_runCommand(array(
				'command' => 'update'
			));

			if ($result !== 0) {
				$this->page()->addVar('error', 'Error (process returned '.$result.')');
			} else {
				$this->page()->addVar('upgraded?', true);
			}
		} else {
			$result = $this->_runCommand(array(
				'command' => 'update',
				'--dry-run' => true
			));

			if ($result !== 0) {
				$this->page()->addVar('error', 'Error (process returned '.$result.')');
			} else {
				$output = $this->page()->getVar('output');

				//Ugly workaround to detect if we need to update or not
				if (strpos($output, 'Nothing to install or update') !== false) {
					$this->page()->addVar('upgrades?', false);
				}
			}
		}
	}

	public function executeAddRepository(HTTPRequest $request) {
		$this->page()->addVar('title', 'Ajouter un d&eacute;p&ocirc;t');
		$this->_addBreadcrumb();

		$packageManager = $this->managers->getManagerOf('Packagecontrol');

		if ($request->postExists('repo-url')) {
			$repoName = $request->postData('repo-name');
			$repoType = $request->postData('repo-type');
			$repoUrl = $request->postData('repo-url');
			$this->page()->addVar('repo-name', $repoName);
			$this->page()->addVar('repo-type', $repoType);
			$this->page()->addVar('repo-url', $repoUrl);

			$configFile = $this->_composerConfig();
			$config = $configFile->read();

			if (!isset($config['repositories'])) {
				$config['repositories'] = array();
			}

			$config['repositories'][$repoName] = array(
				'type' => $repoType,
				'url' => $repoUrl
			);

			$configFile->write($config);

			$this->page()->addVar('added?', true);
		}
	}

	public function executeRemoveRepository(HTTPRequest $request) {
		$this->page()->addVar('title', 'Supprimer un d&eacute;p&ocirc;t');
		$this->_addBreadcrumb();

		$repoName = $request->getData('name');

		$configFile = $this->_composerConfig();
		$config = $configFile->read();

		if (!isset($config['repositories']) || !isset($config['repositories'][$repoName])) {
			return;
		}

		$this->page()->addVar('repository', $repoName);

		if ($request->postExists('check')) {
			unset($config['repositories'][$repoName]);
			$configFile->write($config);

			$this->page()->addVar('removed?', true);
		}
	}

	// LISTERS

	public function listInstalledPackages() {
		$installedRepo = $this->_getInstalledRepo();
		$packages = $installedRepo->getPackages();

		$list = array();

		foreach($packages as $pkg) {
			$item = array(
				'title' => $pkg->getName().' ('.$pkg->getPrettyVersion().')',
				'shortDescription' => $pkg->getDescription(),
				'vars' => array('name' => $pkg->getName())
			);

			$list[] = $item;
		}

		return $list;
	}

	public function listRepositories() {
		$composer = $this->getComposer();
		$config = $composer->getConfig();

		$reposConfig = $config->getRepositories();
		$list = array();

		foreach($reposConfig as $name => $repo) {
			if ($name == 'packagist') { //Skip packagist
				continue;
			}
			if (!isset($repo['url'])) { //Unsupported for the moment
				continue;
			}

			$title = $name;
			if (is_int($title)) {
				$title = $repo['url'];
			}

			$item = array(
				'title' => $name,
				'shortDescription' => $repo['url'],
				'vars' => array('name' => $name)
			);

			$list[] = $item;
		}

		return $list;
	}
}