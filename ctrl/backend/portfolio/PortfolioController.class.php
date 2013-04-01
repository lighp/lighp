<?php
namespace ctrl\backend\portfolio;

class PortfolioController extends \core\BackController {
	protected function _addBreadcrumb($additionnalBreadcrumb = array(array())) {
		$breadcrumb = array(
			array(
				'url' => $this->app->router()->getUrl('main', 'showModule', array(
					'module' => $this->module()
				)),
				'title' => 'Portfolio'
			)
		);

		$this->page()->addVar('breadcrumb', array_merge($breadcrumb, $additionnalBreadcrumb));
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

	public function executeInsertProject(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Créer un projet');
		$this->_addBreadcrumb();

		$projectsManager = $this->managers->getManagerOf('PortfolioProjects');

		$categoriesManager = $this->managers->getManagerOf('PortfolioCategories');
		$categories = $categoriesManager->getList();
		$this->page()->addVar('categories', $categories);

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

			$this->page()->addVar('project', $projectData);

			try {
				$project = new \lib\entities\PortfolioProject($projectData);
			} catch(\InvalidArgumentException $e) {
				$this->page()->addVar('error', $e->getMessage());
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
					$this->page()->addVar('error', $e->getMessage());
					return;
				}
			}

			try {
				$projectsManager->add($project);
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('inserted?', true);
		}
	}

	public function executeUpdateProject(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Modifier un projet');
		$this->_addBreadcrumb();

		$projectName = $request->getData('name');

		$projectsManager = $this->managers->getManagerOf('PortfolioProjects');
		$project = $projectsManager->get($projectName);
		$this->page()->addVar('project', $project);

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
		$this->page()->addVar('categories', $list);

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

			$this->page()->addVar('project', $projectData);

			try {
				$project->hydrate($projectData);
			} catch(\InvalidArgumentException $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('project', $project);

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
					$this->page()->addVar('error', $e->getMessage());
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
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('updated?', true);
		}
	}

	public function executeDeleteProject(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Supprimer un projet');
		$this->_addBreadcrumb();

		$projectName = $request->getData('name');

		$projectsManager = $this->managers->getManagerOf('PortfolioProjects');
		$project = $projectsManager->get($projectName);
		$this->page()->addVar('project', $project);

		if ($request->postExists('check')) {
			try {
				$projectsManager->delete($projectName);
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('deleted?', true);
		}
	}

	public function executeInsertCategory(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Créer une catégorie');
		$this->_addBreadcrumb();

		$categoriesManager = $this->managers->getManagerOf('PortfolioCategories');

		if ($request->postExists('category-name')) {
			$categoryData = array(
				'name' => $request->postData('category-name'),
				'title' => $request->postData('category-title'),
				'subtitle' => $request->postData('category-subtitle'),
				'shortDescription' => $request->postData('category-shortDescription')
			);
			$this->page()->addVar('category', $categoryData);

			try {
				$category = new \lib\entities\PortfolioCategory($categoryData);
			} catch(\InvalidArgumentException $e) {
				$this->page()->addVar('error', $e->getMessage());
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
					$this->page()->addVar('error', $e->getMessage());
					return;
				}
			}

			try {
				$categoriesManager->add($category);
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('inserted?', true);
		}
	}

	public function executeUpdateCategory(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Modifier une catégorie');
		$this->_addBreadcrumb();

		$categoryName = $request->getData('name');

		$categoriesManager = $this->managers->getManagerOf('PortfolioCategories');
		$category = $categoriesManager->get($categoryName);
		$this->page()->addVar('category', $category);

		if ($request->postExists('category-name')) {
			$categoryData = array(
				'name' => $request->postData('category-name'),
				'title' => $request->postData('category-title'),
				'subtitle' => $request->postData('category-subtitle'),
				'shortDescription' => $request->postData('category-shortDescription')
			);
			$this->page()->addVar('category', $categoryData);

			try {
				$category->hydrate($categoryData);
			} catch(\InvalidArgumentException $e) {
				$this->page()->addVar('error', $e->getMessage());
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
					$this->page()->addVar('error', $e->getMessage());
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
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('updated?', true);
		}
	}

	public function executeDeleteCategory(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Supprimer une catégorie');
		$this->_addBreadcrumb();

		$categoryName = $request->getData('name');

		$categoriesManager = $this->managers->getManagerOf('PortfolioCategories');
		$category = $categoriesManager->get($categoryName);
		$this->page()->addVar('category', $category);

		if ($request->postExists('check')) {
			try {
				$categoriesManager->delete($categoryName);
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('deleted?', true);
		}
	}

	public function executeListGalleryItems(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Afficher une galerie');
		$this->_addBreadcrumb();

		$projectName = $request->getData('projectName');

		$galleriesManager = $this->managers->getManagerOf('PortfolioGalleries');
		$gallery = $galleriesManager->getByProject($projectName);
		$this->page()->addVar('gallery', $gallery);
		$this->page()->addVar('projectName', $projectName);
	}

	public function executeInsertGalleryItem(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Ajouter un item à la galerie');

		$projectName = $request->getData('projectName');

		$this->_addBreadcrumb(array(
			array(
				'url' => $this->app->router()->getUrl($this->module(), 'listGalleryItems', array(
					'projectName' => $projectName
				)),
				'title' => 'Galerie'
			),
			array()
		));

		$galleriesManager = $this->managers->getManagerOf('PortfolioGalleries');

		if ($request->postExists('galleryItem-title')) {
			$galleryItemData = array(
				'title' => $request->postData('galleryItem-title'),
				'kind' => $request->postData('galleryItem-kind'),
				'source' => $request->postData('galleryItem-source'),
				'projectName' => $projectName
			);

			$this->page()->addVar('galleryItem', $galleryItemData);

			try {
				$galleryItem = \lib\entities\PortfolioGalleryItem::build($galleryItemData);
			} catch(\InvalidArgumentException $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			if (empty($galleryItem)) {
				$this->page()->addVar('error', 'Invalid gallery item source');
				return;
			}

			try {
				$galleriesManager->add($galleryItem);
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('inserted?', true);
		}
	}

	public function executeUpdateGalleryItem(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Modifier un item de la galerie');

		$projectName = $request->getData('projectName');
		$galleryItemId = $request->getData('id');

		$this->_addBreadcrumb(array(
			array(
				'url' => $this->app->router()->getUrl($this->module(), 'listGalleryItems', array(
					'projectName' => $projectName
				)),
				'title' => 'Galerie'
			),
			array()
		));

		$galleriesManager = $this->managers->getManagerOf('PortfolioGalleries');
		$galleryItem = $galleriesManager->get($galleryItemId);

		$this->page()->addVar('galleryItem', $galleryItem);

		if ($request->postExists('galleryItem-title')) {
			$galleryItemData = array(
				'title' => $request->postData('galleryItem-title'),
				'kind' => $request->postData('galleryItem-kind'),
				'source' => $request->postData('galleryItem-source'),
				'projectName' => $projectName
			);

			$this->page()->addVar('galleryItem', $galleryItemData);

			try {
				$galleryItem->hydrate($galleryItemData);
			} catch(\InvalidArgumentException $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			try {
				$galleriesManager->edit($galleryItem);
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('updated?', true);
		}
	}

	public function executeDeleteGalleryItem(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Supprimer un item de la galerie');

		$projectName = $request->getData('projectName');
		$galleryItemId = (int) $request->getData('id');

		$this->_addBreadcrumb(array(
			array(
				'url' => $this->app->router()->getUrl($this->module(), 'listGalleryItems', array(
					'projectName' => $projectName
				)),
				'title' => 'Galerie'
			),
			array()
		));

		$galleriesManager = $this->managers->getManagerOf('PortfolioGalleries');
		
		try {
			$galleriesManager->delete($galleryItemId);
		} catch(\Exception $e) {
			$this->page()->addVar('error', $e->getMessage());
			return;
		}

		$this->page()->addVar('deleted?', true);
	}

	public function executeInsertLeadingItem(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Mettre en avant un item');
		$this->_addBreadcrumb();

		$portfolioManager = $this->managers->getManagerOf('Portfolio');

		$leadingItemKind = $request->getData('kind');
		$leadingItemName = $request->getData('name');

		if ($request->postExists('leadingItem-place')) {
			$leadingItemData = array(
				'name' => $leadingItemName,
				'kind' => $leadingItemKind,
				'place' => $request->postData('leadingItem-place')
			);

			$this->page()->addVar('leadingItem', $leadingItemData);

			try {
				$leadingItem = new \lib\entities\PortfolioLeadingItem($leadingItemData);
			} catch(\InvalidArgumentException $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			try {
				$portfolioManager->addLeadingItem($leadingItem);
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('inserted?', true);
		}
	}

	public function executeDeleteLeadingItem(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Supprimer un item mis en avant');
		$this->_addBreadcrumb();

		$leadingItemId = (int) $request->getData('id');

		$portfolioManager = $this->managers->getManagerOf('Portfolio');
		
		try {
			$portfolioManager->deleteLeadingItem($leadingItemId);
		} catch(\Exception $e) {
			$this->page()->addVar('error', $e->getMessage());
			return;
		}

		$this->page()->addVar('deleted?', true);
	}

	public function executeUpdateAboutPage(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Modifier mes informations personnelles');
		$this->_addBreadcrumb();

		$portfolioManager = $this->managers->getManagerOf('Portfolio');
		$aboutTexts = $portfolioManager->getAboutTexts();

		$this->page()->addVar('aboutTexts', $aboutTexts);

		if ($request->postExists('about-shortDescription')) {
			try {
				$portfolioManager->updateAboutTexts(array(
					'shortDescription' => $request->postData('about-shortDescription'),
					'content' => $request->postData('about-content')
				));
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('updated?', true);
		}
	}

	public function executeInsertAboutLink(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Ajouter une nouvelle coordonnée');
		$this->_addBreadcrumb();

		$portfolioManager = $this->managers->getManagerOf('Portfolio');

		if ($request->postExists('aboutLink-title')) {
			$aboutLinkData = array(
				'title' => $request->postData('aboutLink-title'),
				'url' => $request->postData('aboutLink-url')
			);

			$this->page()->addVar('aboutLink', $aboutLinkData);

			try {
				$aboutLink = new \lib\entities\PortfolioAboutLink($aboutLinkData);
			} catch(\InvalidArgumentException $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			try {
				$portfolioManager->insertAboutLink($aboutLink);
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('inserted?', true);
		}
	}

	public function executeUpdateAboutLink(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Modifier une de mes coordonnées');
		$this->_addBreadcrumb();

		$aboutLinkId = (int) $request->getData('id');

		$portfolioManager = $this->managers->getManagerOf('Portfolio');
		$aboutLink = $portfolioManager->getAboutLink($aboutLinkId);

		$this->page()->addVar('aboutLink', $aboutLink);

		if ($request->postExists('aboutLink-title')) {
			$aboutLinkData = array(
				'title' => $request->postData('aboutLink-title'),
				'url' => $request->postData('aboutLink-url')
			);

			$this->page()->addVar('aboutLink', $aboutLinkData);

			try {
				$aboutLink->hydrate($aboutLinkData);
			} catch(\InvalidArgumentException $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			try {
				$portfolioManager->updateAboutLink($aboutLink);
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('updated?', true);
		}
	}

	public function executeDeleteAboutLink(\core\HTTPRequest $request) {
		$this->page()->addVar('title', 'Supprimer une de mes coordonnées');
		$this->_addBreadcrumb();

		$aboutLinkId = (int) $request->getData('id');

		$portfolioManager = $this->managers->getManagerOf('Portfolio');
		
		try {
			$portfolioManager->deleteAboutLink($aboutLinkId);
		} catch(\Exception $e) {
			$this->page()->addVar('error', $e->getMessage());
			return;
		}

		$this->page()->addVar('deleted?', true);
	}


	// LISTERS

	public function listProjects() {
		$projectsManager = $this->managers->getManagerOf('PortfolioProjects');

		$projects = $projectsManager->getList();
		$list = array();

		foreach($projects as $project) {
			$item = array(
				'title' => $project['title'],
				'shortDescription' => $project['shortDescription'],
				'vars' => array('name' => $project['name'])
			);

			$list[] = $item;
		}

		return $list;
	}

	public function listCategories() {
		$categoriesManager = $this->managers->getManagerOf('PortfolioCategories');

		$categories = $categoriesManager->getList();
		$list = array();

		foreach($categories as $category) {
			$item = array(
				'title' => $category['title'],
				'shortDescription' => $category['shortDescription'],
				'vars' => array('name' => $category['name'])
			);

			$list[] = $item;
		}

		return $list;
	}

	public function listCategoriesAndProjects() {
		$categories = $this->listCategories();
		$projects = $this->listProjects();

		$list = array();

		foreach($categories as $key => $category) {
			$item = $category;
			$item['vars']['kind'] = 'category';
			$item['title'] = 'Catégorie : ' . $category['title'];

			$list[] = $item;
		}

		foreach($projects as $key => $project) {
			$item = $project;
			$item['vars']['kind'] = 'project';
			$item['title'] = 'Projet : ' . $project['title'];

			$list[] = $item;
		}

		return $list;
	}

	public function listLeadingItems() {
		$portfolioManager = $this->managers->getManagerOf('Portfolio');

		$leadingItems = $portfolioManager->getLeadingItemsData();
		$list = array();

		foreach($leadingItems as $leadingItem) {
			$item = array(
				'title' => (($leadingItem['item']['kind'] == 'category') ? 'Catégorie' : 'Projet') . ' : ' . $leadingItem['data']['title'],
				'shortDescription' => 'Mis en avant sur : ' . $leadingItem['item']['place'],
				'vars' => array('id' => $leadingItem['item']['id'])
			);

			$list[] = $item;
		}

		return $list;
	}

	public function listAboutLinks() {
		$portfolioManager = $this->managers->getManagerOf('Portfolio');

		$aboutLinks = $portfolioManager->getAboutLinks();
		$list = array();

		foreach($aboutLinks as $aboutLink) {
			$item = array(
				'title' => $aboutLink['title'],
				'vars' => array('id' => $aboutLink['id'])
			);

			$list[] = $item;
		}

		return $list;
	}
}