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
}