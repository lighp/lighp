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

		$i = 0;
		foreach($categories as $key => $cat) {
			$categories[$key] = $cat->toArray();
			$categories[$key]['changeRow?'] = ($i % 3 == 0 && $i > 0);
			$i++;
		}

		$featuretteProjects = $leading['featurette'];

		$featuretteProjectsNames = array();
		$i = 0;
		foreach($featuretteProjects as $key => $project) {
			$featuretteProjectsNames[] = $project['name'];

			$featuretteProjects[$key] = $project->toArray();
			$featuretteProjects[$key]['pullRight?'] = ($i % 2 == 1);

			$i++;
		}

		$i = 0;
		foreach($projects as $key => $project) {
			if (in_array($project['name'], $featuretteProjectsNames)) {
				unset($projects[$key]);
			} else {
				$projects[$key] = $project->toArray();
				$projects[$key]['changeRow?'] = ($i % 3 == 0 && $i > 0);
				$i++;
			}
		}

		$this->page->addVar('categories', $categories);
		$this->page->addVar('featuretteProjects', $featuretteProjects);
		$this->page->addVar('otherProjects', array_values($projects));
	}

	public function executeShowCategory(\core\HTTPRequest $request) {
		$this->page->addVar('title', 'Voir une catégorie');

		$projectsManager = $this->managers->getManagerOf('PortfolioProjects');
		$categoriesManager = $this->managers->getManagerOf('PortfolioCategories');

		$catName = $request->getData('name');

		$category = $categoriesManager->get($catName);
		$projects = $projectsManager->getByCategory($catName);

		$i = 0;
		foreach($projects as $key => $project) {
			$projects[$key] = $project->toArray();
			$projects[$key]['changeRow?'] = ($i % 3 == 0 && $i > 0);
			$i++;
		}

		$this->page->addVar('title', $category['title']);
		$this->page->addVar('category', $category);
		$this->page->addVar('projects', $projects);
	}

	public function executeShowProject(\core\HTTPRequest $request) {
		$this->page->addVar('title', 'Voir un projet');

		$projectsManager = $this->managers->getManagerOf('PortfolioProjects');
		$galleriesManager = $this->managers->getManagerOf('PortfolioGalleries');

		$projectName = $request->getData('name');

		$project = $projectsManager->get($projectName);
		$gallery = $galleriesManager->get($projectName);

		$i = 0;
		foreach($gallery as $key => $item) {
			$gallery[$key] = $item->toArray();
			$gallery[$key]['render'] = $item->render();
			$gallery[$key]['changeRow?'] = ($i % 3 == 0 && $i > 0);
			$i++;
		}

		$this->page->addVar('title', $project['title']);
		$this->page->addVar('project', $project);
		$this->page->addVar('gallery?', (count($gallery) > 0));
		$this->page->addVar('gallery', $gallery);
	}

	public function executeAbout(\core\HTTPRequest $request) {
		$this->page->addVar('title', 'À propos');

		$portfolioManager = $this->managers->getManagerOf('Portfolio');

		$aboutTexts = $portfolioManager->getAboutTexts();
		$aboutLinks = $portfolioManager->getAboutLinks();

		$this->page->addVar('aboutTexts', $aboutTexts);
		$this->page->addVar('aboutLinks', $aboutLinks);
	}
}