<?php
namespace lib\manager;

abstract class PortfolioProjectsManager extends \core\Manager {
	abstract public function getList();
	abstract public function getByCategory($catName);
	abstract public function get($projectName);
	abstract public function add(\lib\entities\PortfolioProject $project);
	abstract public function edit(\lib\entities\PortfolioProject $project);
	abstract public function delete($projectName);
}