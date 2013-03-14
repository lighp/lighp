<?php
namespace lib\manager;

class PortfolioManager_json extends PortfolioManager {
	public function getLeading() {
		$leadingFile = $this->dao->open('portfolio/leading');
		$projectsFile = $this->dao->open('portfolio/projects');
		$categoriesFile = $this->dao->open('portfolio/categories');

		$leading = $leadingFile->read();
		$projects = $projectsFile->read();
		$categories = $categoriesFile->read();

		$list = array(
			'featurette' => array(),
			'carousel' => array()
		);

		foreach($leading as $itemData) {
			$item = null;

			switch ($itemData['kind']) {
				case 'project':
					$filteredProjects = $projects->filter(array('name' => $itemData['name']));
					if (!isset($filteredProjects[0])) { continue; }
					$project = $filteredProjects[0];
					$item = new \lib\entities\PortfolioProject($project);
					break;
				case 'category':
					$filteredCategories = $categories->filter(array('name' => $itemData['name']));
					if (!isset($filteredCategories[0])) { continue; }
					$category = $filteredCategories[0];
					$item = new \lib\entities\PortfolioCategory($category);
					break;
				default:
					continue;
			}

			if ($item !== null) {
				$list[$itemData['place']][] = $item;
			}
		}

		return $list;
	}

	public function getAboutTexts() {
		$aboutTextFile = $this->dao->open('portfolio/about_text');

		$aboutTextRow = $aboutTextFile->read();

		$aboutText = array();

		foreach($aboutTextRow as $text) {
			$aboutText[$text['name']] = $text['value'];
		}

		return $aboutText;
	}

	public function getAboutLinks() {
		$aboutLinksFile = $this->dao->open('portfolio/about_link');

		$aboutLinks = $aboutLinksFile->read();

		return $aboutLinks->convertToArray();
	}
}