<?php
namespace ctrl\backend\portfolio;

class PortfolioController extends \core\BackController {
	protected function _addBreadcrumb($page = array()) {
		$breadcrumb = array(
			array('url' => 'module-'.$this->module.'.html', 'title' => 'Portfolio')
		);

		$this->page->addVar('breadcrumb', array_merge($breadcrumb, array($page)));
	}

	public function executeListProjects(\core\HTTPRequest $request) {
		$this->page->addVar('title', 'Gérer un projet');
		$this->_addBreadcrumb();

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
		$this->page->addVar('title', 'Créer un projet');
		$this->_addBreadcrumb();

		$projectsManager = $this->managers->getManagerOf('PortfolioProjects');

		$categoriesManager = $this->managers->getManagerOf('PortfolioCategories');
		$categories = $categoriesManager->getList();
		$this->page->addVar('categories', $categories);

		if ($request->postExists('project-name')) {
			$projectData = array(
				'name' => $request->postData('project-name'),
				'title' => $request->postData('project-title'),
				'subtitle' => $request->postData('project-subtitle'),
				'category' => $request->postData('project-category'),
				'url' => $request->postData('project-url'),
				'shortDescription' => $request->postData('project-shortDescription')
			);

			$this->page->addVar('project', $projectData);

			try {
				$project = new \lib\entities\PortfolioProject($projectData);
			} catch(\InvalidArgumentException $e) {
				$this->page->addVar('error', $e->getMessage());
				return;
			}

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
		$this->page->addVar('title', 'Modifier un projet');
		$this->_addBreadcrumb();

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
		$this->page->addVar('title', 'Supprimer un projet');
		$this->_addBreadcrumb();

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

	public function executeListCategories(\core\HTTPRequest $request) {
		$this->page->addVar('title', 'Gérer une catégorie');
		$this->_addBreadcrumb();

		$categoriesManager = $this->managers->getManagerOf('PortfolioCategories');

		$categories = $categoriesManager->getList();

		$this->page->addVar('categories', $categories);
	}

	public function executeInsertCategory(\core\HTTPRequest $request) {
		$this->page->addVar('title', 'Créer une catégorie');
		$this->_addBreadcrumb();

		$categoriesManager = $this->managers->getManagerOf('PortfolioCategories');

		if ($request->postExists('category-name')) {
			$categoryData = array(
				'name' => $request->postData('category-name'),
				'title' => $request->postData('category-title'),
				'subtitle' => $request->postData('category-subtitle'),
				'shortDescription' => $request->postData('category-shortDescription')
			);

			$this->page->addVar('category', $categoryData);

			try {
				$category = new \lib\entities\PortfolioCategory($categoryData);
			} catch(\InvalidArgumentException $e) {
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			try {
				$categoriesManager->add($category);
			} catch(\Exception $e) {
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			$this->page->addVar('inserted?', true);
		}
	}

	public function executeUpdateCategory(\core\HTTPRequest $request) {
		$this->page->addVar('title', 'Modifier un catégorie');
		$this->_addBreadcrumb();

		$categoryName = $request->getData('name');

		$categoriesManager = $this->managers->getManagerOf('PortfolioCategories');
		$category = $categoriesManager->get($categoryName);
		$this->page->addVar('category', $category);

		if ($request->postExists('category-name')) {
			$categoryData = array(
				'name' => $request->postData('category-name'),
				'title' => $request->postData('category-title'),
				'subtitle' => $request->postData('category-subtitle'),
				'shortDescription' => $request->postData('category-shortDescription')
			);
			$this->page->addVar('category', $categoryData);

			try {
				$category->hydrate($categoryData);
			} catch(\InvalidArgumentException $e) {
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			try {
				if ($categoryName != $category['name']) { //If we've edited the category's name
					$categoriesManager->delete($categoryName);
					$categoriesManager->add($category);
				} else {
					$categoriesManager->edit($category);
				}
			} catch(\Exception $e) {
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			$this->page->addVar('updated?', true);
		}
	}

	public function executeDeleteCategory(\core\HTTPRequest $request) {
		$this->page->addVar('title', 'Supprimer un catégorie');
		$this->_addBreadcrumb();

		$categoryName = $request->getData('name');

		$categoriesManager = $this->managers->getManagerOf('PortfolioCategories');
		$category = $categoriesManager->get($categoryName);
		$this->page->addVar('category', $category);

		if ($request->postExists('check')) {
			try {
				$categoriesManager->delete($categoryName);
			} catch(\Exception $e) {
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			$this->page->addVar('deleted?', true);
		}
	}
}