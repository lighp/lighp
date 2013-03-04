<?php
namespace lib\manager;

class PortfolioProjectsManager_json extends PortfolioProjectsManager {
	protected function _buildProject($project) {
		return new \lib\entities\PortfolioProject($project);
	}

	protected function _buildProjectsArray($projects) {
		return $projects->convertToArray('\lib\entities\PortfolioProject');
	}

	public function getList() {
		$file = $this->dao->open('portfolio/projects');
		$projects = $file->read();

		return $this->_buildProjectsArray($projects);
	}

	public function getByCategory($catName) {
		$file = $this->dao->open('portfolio/projects');
		$projects = $file->read()->filter(array('category' => $catName));

		return $this->_buildProjectsArray($projects);
	}

	public function get($projectName) {
		$file = $this->dao->open('portfolio/projects');
		$projects = $file->read()->filter(array('name' => $projectName));

		return $this->_buildProject($projects[0]);
	}

	public function add(\lib\entities\PortfolioProject $project) {
		$file = $this->dao->open('portfolio/projects');
		$projects = $file->read();

		$item = $this->dao->createItem($project->toArray());

		$projects[] = $item;

		$file->write($projects);
	}

	public function edit(\lib\entities\PortfolioProject $project) {
		$file = $this->dao->open('portfolio/projects');
		$projects = $file->read();

		$editedItem = $this->dao->createItem($project->toArray());

		foreach($projects as $id => $item) {
			if ($item['name'] == $project['name']) {
				$projects[$id] = $editedItem;
				break;
			}
		}

		$file->write($projects);
	}

	public function delete($projectName) {
		$file = $this->dao->open('portfolio/projects');
		$projects = $file->read();

		foreach($projects as $id => $item) {
			if ($item['name'] == $projectName) {
				unset($projects[$id]);
				break;
			}
		}

		$file->write($projects);
	}
}