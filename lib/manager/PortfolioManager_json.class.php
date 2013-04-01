<?php
namespace lib\manager;

class PortfolioManager_json extends PortfolioManager {
	public function getLeadingItems() {
		$leadingFile = $this->dao->open('portfolio/leading');

		$leading = $leadingFile->read();

		$list = array();

		foreach($leading as $itemData) {
			$list[] = new \lib\entities\PortfolioLeadingItem($itemData);
		}

		return $list;
	}

	public function getLeadingItemsData() {
		$projectsFile = $this->dao->open('portfolio/projects');
		$categoriesFile = $this->dao->open('portfolio/categories');

		$leadingItems = $this->getLeadingItems();
		$projects = $projectsFile->read();
		$categories = $categoriesFile->read();

		$list = array();

		foreach($leadingItems as $leadingItem) {
			$itemData = null;

			switch ($leadingItem['kind']) {
				case 'project':
					$filteredProjects = $projects->filter(array('name' => $leadingItem['name']));
					if (!isset($filteredProjects[0])) { continue; }
					$itemData = new \lib\entities\PortfolioProject($filteredProjects[0]);
					break;
				case 'category':
					$filteredCategories = $categories->filter(array('name' => $leadingItem['name']));
					if (!isset($filteredCategories[0])) { continue; }
					$itemData = new \lib\entities\PortfolioCategory($filteredCategories[0]);
					break;
			}

			if ($itemData !== null) {
				$list[] = array(
					'item' => $leadingItem,
					'data' => $itemData
				);
			}
		}

		return $list;
	}

	public function addLeadingItem(\lib\entities\PortfolioLeadingItem &$leadingItem) {
		$file = $this->dao->open('portfolio/leading');
		$items = $file->read();

		$leadingItemId = (count($items) > 0) ? $items->last()['id'] + 1 : 0;
		$leadingItem->setId($leadingItemId);

		$item = $this->dao->createItem($leadingItem->toArray());
		$items[] = $item;

		$file->write($items);
	}

	public function deleteLeadingItem($leadingItemId) {
		$leadingItemId = (int) $leadingItemId;

		$file = $this->dao->open('portfolio/leading');
		$items = $file->read();

		foreach($items as $key => $item) {
			if ($item['id'] == $leadingItemId) {
				unset($items[$key]);
				break;
			}
		}

		$file->write($items);
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

	public function updateAboutTexts($aboutTexts) {
		$actualAboutTexts = $this->getAboutTexts();
		$aboutTexts = array_merge($actualAboutTexts, $aboutTexts);

		$aboutTextFile = $this->dao->open('portfolio/about_text');

		$aboutTextRow = $this->dao->createCollection();
		foreach($aboutTexts as $name => $value) {
			$aboutTextRow[] = $this->dao->createItem(array(
				'name' => $name,
				'value' => $value
			));
		}

		$aboutTextFile->write($aboutTextRow);
	}

	public function getAboutLinks() {
		$aboutLinksFile = $this->dao->open('portfolio/about_link');

		$aboutLinks = $aboutLinksFile->read();

		return $aboutLinks->convertToArray('\lib\entities\PortfolioAboutLink');
	}

	public function getAboutLink($aboutLinkId) {
		$file = $this->dao->open('portfolio/about_link');
		$aboutLinks = $file->read()->filter(array('id' => (int) $aboutLinkId));

		if (count($aboutLinks) == 0) {
			return;
		} else {
			return new \lib\entities\PortfolioAboutLink($aboutLinks[0]);
		}
	}

	public function insertAboutLink(\lib\entities\PortfolioAboutLink &$aboutLink) {
		$file = $this->dao->open('portfolio/about_link');
		$items = $file->read();

		$aboutLinkId = (count($items) > 0) ? $items->last()['id'] + 1 : 0;
		$aboutLink->setId($aboutLinkId);

		$item = $this->dao->createItem($aboutLink->toArray());
		$items[] = $item;

		$file->write($items);
	}

	public function updateAboutLink(\lib\entities\PortfolioAboutLink $aboutLink) {
		$file = $this->dao->open('portfolio/about_link');
		$items = $file->read();

		$editedItem = $this->dao->createItem($aboutLink->toArray());

		foreach($items as $key => $item) {
			if ($item['id'] == $aboutLink['id']) {
				$items[$key] = $editedItem;
				break;
			}
		}

		$file->write($items);
	}

	public function deleteAboutLink($aboutLinkId) {
		$aboutLinkId = (int) $aboutLinkId;

		$file = $this->dao->open('portfolio/about_link');
		$items = $file->read();

		foreach($items as $key => $item) {
			if ($item['id'] == $aboutLinkId) {
				unset($items[$key]);
				break;
			}
		}

		$file->write($items);
	}
}