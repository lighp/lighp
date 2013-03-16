<?php
namespace ctrl\backend\portfolio;

class PortfolioController extends \core\BackController {
	protected function _addBreadcrumb($page = array()) {
		$breadcrumb = array(
			array('url' => 'module-'.$this->module.'.html', 'title' => 'Portfolio')
		);

		$this->page->addVar('breadcrumb', array_merge($breadcrumb, array($page)));
	}

	protected function _handleImageUpload($uploadFile, $uploadImgDest) {
		if ($uploadFile['error'] != 0) {
			throw new \RuntimeException('Cannot upload file : server error [#'.$uploadFile['error'].']');
		}

		$uploadInfo = pathinfo($uploadFile['name']);
		$uploadExtension = strtolower($uploadInfo['extension']);
		if ($uploadExtension != 'png') {
			throw new \RuntimeException('Cannot upload file : wrong image extension (provided : "'.$uploadExtension.'", accepted : "png")');
		}

		$uploadSource = $uploadFile['tmp_name'];
		$uploadDest = __DIR__ . '/../../../public/img/' . $uploadImgDest;
		$uploadDestDir = dirname($uploadDest);

		if (!is_dir($uploadDestDir)) {
			mkdir($uploadDestDir, 0777, true);
			chmod($uploadDestDir, 0777);
		}

		//if (file_exists($uploadDest)) {
		//	return;
		//}

		$result = copy($uploadSource, $uploadDest);

		if ($result === false) {
			throw new \RuntimeException('Cannot upload file : error while copying file to "'.$uploadDest.'"');
		}

		chmod($uploadDest, 0777);
	}

	protected function _removeImage($imgPath) {
		$filePath = __DIR__ . '/../../../public/img/' . $imgPath;

		unlink($filePath);
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
				'shortDescription' => $request->postData('project-shortDescription'),
				'description' => $request->postData('project-description')
			);

			$this->page->addVar('project', $projectData);

			try {
				$project = new \lib\entities\PortfolioProject($projectData);
			} catch(\InvalidArgumentException $e) {
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			//Image upload
			if (isset($_FILES['project-largeimage']) && $_FILES['project-largeimage']['error'] !== UPLOAD_ERR_NO_FILE) {
				try {
					$largeImgUploadData = $_FILES['project-largeimage'];
					$this->_handleImageUpload($largeImgUploadData, 'portfolio/project/large/'.$project['name'].'.png');

					$project->setHasImage(true);

					if (isset($_FILES['project-mediumimage']) && $_FILES['project-mediumimage']['error'] !== UPLOAD_ERR_NO_FILE) {
						$mediumImgUploadData = $_FILES['project-mediumimage'];
					} else {
						$mediumImgUploadData = $_FILES['project-largeimage'];
					}

					$this->_handleImageUpload($mediumImgUploadData, 'portfolio/project/medium/'.$project['name'].'.png');
				} catch(\Exception $e) {
					$this->page->addVar('error', $e->getMessage());
					return;
				}
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
			$projectData = array(
				'name' => $request->postData('project-name'),
				'title' => $request->postData('project-title'),
				'subtitle' => $request->postData('project-subtitle'),
				'category' => $request->postData('project-category'),
				'url' => $request->postData('project-url'),
				'shortDescription' => $request->postData('project-shortDescription'),
				'description' => $request->postData('project-description')
			);

			$this->page->addVar('project', $projectData);

			try {
				$project->hydrate($projectData);
			} catch(\InvalidArgumentException $e) {
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			$this->page->addVar('project', $project);

			//Image upload
			if (isset($_FILES['project-largeimage']) && $_FILES['project-largeimage']['error'] !== UPLOAD_ERR_NO_FILE) {
				try {
					$largeImgUploadData = $_FILES['project-largeimage'];
					$this->_handleImageUpload($largeImgUploadData, 'portfolio/project/large/'.$project['name'].'.png');

					$project->setHasImage(true);

					if (isset($_FILES['project-mediumimage']) && $_FILES['project-mediumimage']['error'] !== UPLOAD_ERR_NO_FILE) {
						$mediumImgUploadData = $_FILES['project-mediumimage'];
					} else {
						$mediumImgUploadData = $_FILES['project-largeimage'];
					}

					$this->_handleImageUpload($mediumImgUploadData, 'portfolio/project/medium/'.$project['name'].'.png');
				} catch(\Exception $e) {
					$this->page->addVar('error', $e->getMessage());
					return;
				}
			}

			if ($request->postExists('project-largeimage-remove') && $request->postData('project-largeimage-remove') == 'on') {
				$project->setHasImage(false);
				$this->_removeImage('portfolio/project/medium/'.$project['name'].'.png');
				$this->_removeImage('portfolio/project/large/'.$project['name'].'.png');
			}

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

			//Image upload
			if (isset($_FILES['category-largeimage']) && $_FILES['category-largeimage']['error'] !== UPLOAD_ERR_NO_FILE) {
				try {
					$largeImgUploadData = $_FILES['category-largeimage'];
					$this->_handleImageUpload($largeImgUploadData, 'portfolio/category/large/'.$category['name'].'.png');

					$category->setHasImage(true);

					if (isset($_FILES['category-mediumimage']) && $_FILES['category-mediumimage']['error'] !== UPLOAD_ERR_NO_FILE) {
						$mediumImgUploadData = $_FILES['category-mediumimage'];
					} else {
						$mediumImgUploadData = $_FILES['category-largeimage'];
					}

					$this->_handleImageUpload($mediumImgUploadData, 'portfolio/category/medium/'.$category['name'].'.png');
				} catch(\Exception $e) {
					$this->page->addVar('error', $e->getMessage());
					return;
				}
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
		$this->page->addVar('title', 'Modifier une catégorie');
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

			//Image upload
			if (isset($_FILES['category-largeimage']) && $_FILES['category-largeimage']['error'] !== UPLOAD_ERR_NO_FILE) {
				try {
					$largeImgUploadData = $_FILES['category-largeimage'];
					$this->_handleImageUpload($largeImgUploadData, 'portfolio/category/large/'.$category['name'].'.png');

					$category->setHasImage(true);

					if (isset($_FILES['category-mediumimage']) && $_FILES['category-mediumimage']['error'] !== UPLOAD_ERR_NO_FILE) {
						$mediumImgUploadData = $_FILES['category-mediumimage'];
					} else {
						$mediumImgUploadData = $_FILES['category-largeimage'];
					}

					$this->_handleImageUpload($mediumImgUploadData, 'portfolio/category/medium/'.$category['name'].'.png');
				} catch(\Exception $e) {
					$this->page->addVar('error', $e->getMessage());
					return;
				}
			}
			if ($request->postExists('category-largeimage-remove') && $request->postData('category-largeimage-remove') == 'on') {
				$category->setHasImage(false);
				$this->_removeImage('portfolio/category/medium/'.$category['name'].'.png');
				$this->_removeImage('portfolio/category/large/'.$category['name'].'.png');
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
		$this->page->addVar('title', 'Supprimer une catégorie');
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