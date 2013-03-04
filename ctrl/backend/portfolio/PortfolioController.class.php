<?php
namespace ctrl\backend\portfolio;

class PortfolioController extends \core\BackController {
	public function executeListProjects(\core\HTTPRequest $request) {
		$projectsManager = $this->managers->getManagerOf('PortfolioProjects');
		$categoriesManager = $this->managers->getManagerOf('PortfolioCategories');

		$projects = $projectsManager->getList();
		$categories = $categoriesManager->getList();

		$list = array();

		foreach($projects as $project) {
			$item = $project->toArray();

			foreach($categories as $category) {
				if ($item['category'] == $category['name']) {
					$item['category'] = $category;
					break;
				}
			}

			$list[] = $item;
		}

		$this->page->addVar('projects', $list);
	}

	public function executeInsertProject(\core\HTTPRequest $request) {
		$projectsManager = $this->managers->getManagerOf('PortfolioProjects');

		$categoriesManager = $this->managers->getManagerOf('PortfolioCategories');
		$categories = $categoriesManager->getList();
		$this->page->addVar('categories', $categories);

		if ($request->postExists('project-name')) {
			try {
				$project = new \lib\entities\PortfolioProject(array(
					'name' => $request->postData('project-name'),
					'title' => $request->postData('project-title'),
					'subtitle' => $request->postData('project-subtitle'),
					'category' => $request->postData('project-category'),
					'url' => $request->postData('project-url'),
					'shortDescription' => $request->postData('project-shortDescription')
				));
			} catch(\InvalidArgumentException $e) {
				$this->page->addVar('project', $project);
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			$this->page->addVar('project', $project);

			try {
				$projectsManager->add($project);
			} catch(\Exception $e) {
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			$this->page->addVar('inserted?', true);
		}
	}

	public function executeUpdateProject(\core\HTTPRequest $request) {
		$projectName = $request->getData('name');

		$projectsManager = $this->managers->getManagerOf('PortfolioProjects');
		$project = $projectsManager->get($projectName);
		$this->page->addVar('project', $project);

		$categoriesManager = $this->managers->getManagerOf('PortfolioCategories');
		$categories = $categoriesManager->getList();
		$list = array();
		foreach($categories as $cat) {
			$item = $cat->toArray();

			if ($project['category'] == $cat['name']) {
				$item['selected?'] = true;
			}

			$list[] = $item;
		}
		$this->page->addVar('categories', $list);

		if ($request->postExists('project-name')) {
			try {
				$project->hydrate(array(
					'name' => $request->postData('project-name'),
					'title' => $request->postData('project-title'),
					'subtitle' => $request->postData('project-subtitle'),
					'category' => $request->postData('project-category'),
					'url' => $request->postData('project-url'),
					'shortDescription' => $request->postData('project-shortDescription')
				));
			} catch(\InvalidArgumentException $e) {
				$this->page->addVar('project', $project);
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			$this->page->addVar('project', $project);

			try {
				if ($projectName != $project['name']) { //If we've edited the project's name
					$projectsManager->delete($projectName);
					$projectsManager->add($project);
				} else {
					$projectsManager->edit($project);
				}
			} catch(\Exception $e) {
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			$this->page->addVar('updated?', true);
		}
	}

	public function executeDeleteProject(\core\HTTPRequest $request) {
		$projectName = $request->getData('name');

		$projectsManager = $this->managers->getManagerOf('PortfolioProjects');
		$project = $projectsManager->get($projectName);
		$this->page->addVar('project', $project);

		if ($request->postExists('check')) {
			try {
				$projectsManager->delete($projectName);
			} catch(\Exception $e) {
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			$this->page->addVar('deleted?', true);
		}
	}
}