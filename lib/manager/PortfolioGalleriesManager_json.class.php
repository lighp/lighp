<?php
namespace lib\manager;

class PortfolioGalleriesManager_json extends PortfolioGalleriesManager {
	public function get($projectName) {
		$file = $this->dao->open('portfolio/projects');
		$projects = $file->read()->filter(array('name' => $projectName));

		if (!isset($projects[0])) {
			throw new \RuntimeException('Unable to find the project "'.$projectName.'"');
		}

		$project = $projects[0];

		if (!isset($project['gallery'])) {
			return array();
		}

		$galleryData = $project['gallery'];
		$gallery = array();

		foreach($galleryData as $id => $itemData) {
			$item = \lib\entities\PortfolioGalleryItem::build($itemData, $id, $projectName);

			if ($item !== null) {
				$gallery[] = $item;
			}
		}

		return $gallery;
	}
}