<?php
namespace lib\manager;

class PortfolioCategoriesManager_json extends PortfolioCategoriesManager {
	protected function _buildCategory($category) {
		return new \lib\entities\PortfolioCategory($category);
	}

	protected function _buildCategoriesArray($categories) {
		return $categories->convertToArray('\lib\entities\PortfolioCategory');
	}

	public function getList() {
		$file = $this->dao->open('portfolio/categories');
		$categories = $file->read();

		return $this->_buildCategoriesArray($categories);
	}

	public function get($catName) {
		$file = $this->dao->open('portfolio/categories');
		$categories = $file->read()->filter(array('name' => $catName));

		return $this->_buildCategory($categories[0]);
	}

	public function add(\lib\entities\PortfolioCategory $category) {
		$file = $this->dao->open('portfolio/categories');
		$categories = $file->read();

		$item = $this->dao->createItem($category->toArray());

		$categories[] = $item;

		$file->write($categories);
	}

	public function edit(\lib\entities\PortfolioCategory $category) {
		$file = $this->dao->open('portfolio/categories');
		$categories = $file->read();

		$editedItem = $this->dao->createItem($category->toArray());

		foreach($categories as $id => $item) {
			if ($item['name'] == $category['name']) {
				$categories[$id] = $editedItem;
				break;
			}
		}

		$file->write($categories);
	}

	public function delete($categoryName) {
		$file = $this->dao->open('portfolio/categories');
		$categories = $file->read();

		foreach($categories as $id => $item) {
			if ($item['name'] == $categoryName) {
				unset($categories[$id]);
				break;
			}
		}

		$file->write($categories);
	}
}