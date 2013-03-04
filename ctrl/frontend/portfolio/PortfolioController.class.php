<?php
namespace ctrl\frontend\portfolio;

class PortfolioController extends \core\BackController {
	public function executeIndex(\core\HTTPRequest $request) {
		$projectsManager = $this->managers->getManagerOf('PortfolioProjects');
		$categoriesManager = $this->managers->getManagerOf('PortfolioCategories');
		$portfolioManager = $this->managers->getManagerOf('Portfolio');

		$projects = $projectsManager->getList();
		$categories = $categoriesManager->getList();
		$leading = $portfolioManager->getLeading();

		$featuretteProjects = $leading['featurette'];

		$featuretteProjectsNames = array();
		foreach($featuretteProjects as $project) {
			$featuretteProjectsNames[] = $project['name'];
		}

		foreach($projects as $key => $project) {
			if (in_array($project['name'], $featuretteProjectsNames)) {
				unset($projects[$key]);
			}
		}

		$this->page->addVar('categories', $categories);
		$this->page->addVar('featuretteProjects', $featuretteProjects);
		$this->page->addVar('otherProjects', array_values($projects));
	}

	public function executeShowCategory(\core\HTTPRequest $request) {
		$projectsManager = $this->managers->getManagerOf('PortfolioProjects');
		$categoriesManager = $this->managers->getManagerOf('PortfolioCategories');

		$catName = $request->getData('name');

		$category = $categoriesManager->get($catName);
		$projects = $projectsManager->getByCategory($catName);

		$this->page->addVar('title', $category['title']);
		$this->page->addVar('category', $category);
		$this->page->addVar('projects', $projects);
	}

	public function executeShowProject(\core\HTTPRequest $request) {
		$projectsManager = $this->managers->getManagerOf('PortfolioProjects');

		$projectName = $request->getData('name');

		$project = $projectsManager->get($projectName);

		$this->page->addVar('title', $project['title']);
		$this->page->addVar('project', $project);
		$this->page->addVar('gallery?', (count($project['gallery']) > 0));
	}
}