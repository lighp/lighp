<?php
namespace lib\manager;

class PortfolioGalleriesManager_json extends PortfolioGalleriesManager {
	public function getByProject($projectName) {
		$file = $this->dao->open('portfolio/galleries');
		$galleryItems = $file->read()->filter(array('projectName' => $projectName));

		$gallery = array();

		foreach($galleryItems as $itemData) {
			$item = \lib\entities\PortfolioGalleryItem::build($itemData);

			if ($item !== null) {
				$gallery[] = $item;
			}
		}

		return $gallery;
	}

	public function get($galleryItemId) {
		$file = $this->dao->open('portfolio/galleries');
		$galleryItems = $file->read()->filter(array('id' => $galleryItemId));

		if (count($galleryItems) == 0) {
			return;
		} else {
			return \lib\entities\PortfolioGalleryItem::build($galleryItems[0]);
		}
	}

	public function add(\lib\entities\PortfolioGalleryItem &$galleryItem) {
		$file = $this->dao->open('portfolio/galleries');
		$items = $file->read();

		$galleryItemId = (count($items) > 0) ? $items->last()['id'] + 1 : 0;
		$galleryItem->setId($galleryItemId);

		$item = $this->dao->createItem($galleryItem->toArray());
		$items[] = $item;

		$file->write($items);
	}

	public function edit(\lib\entities\PortfolioGalleryItem $galleryItem) {
		$file = $this->dao->open('portfolio/galleries');
		$items = $file->read();

		$editedItem = $this->dao->createItem($galleryItem->toArray());

		foreach($items as $key => $item) {
			if ($item['id'] == $galleryItem['id']) {
				$items[$key] = $editedItem;
				break;
			}
		}

		$file->write($items);
	}

	public function delete($galleryItemId) {
		$galleryItemId = (int) $galleryItemId;

		$file = $this->dao->open('portfolio/galleries');
		$items = $file->read();

		foreach($items as $key => $item) {
			if ($item['id'] == $galleryItemId) {
				unset($items[$key]);
				break;
			}
		}

		$file->write($items);
	}
}