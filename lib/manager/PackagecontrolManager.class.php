<?php
namespace lib\manager;

abstract class PackagecontrolManager extends \core\Manager implements \lib\Repository {
	abstract public function search($query, \lib\Repository $repository = null);

	abstract public function install($packages, LocalRepositoryManager $localRepository);
	abstract public function calculateUpgrades(LocalRepositoryManager $localRepository);
	
	abstract public function getRemoteRepositoriesList();
	abstract public function getRemoteRepository($repoName);
	abstract public function remoteRepositoryExists($repoName);
	abstract public function addRemoteRepository($repoName, $repoUrl);
	abstract public function removeRemoteRepository($repoName);
}