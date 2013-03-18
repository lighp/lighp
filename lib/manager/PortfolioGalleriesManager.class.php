<?php
namespace lib\manager;

abstract class PortfolioGalleriesManager extends \core\Manager {
	abstract public function getByProject($id);
	abstract public function get($id);
	abstract public function add(\lib\entities\PortfolioGalleryItem &$galleryItem);
	abstract public function edit(\lib\entities\PortfolioGalleryItem $galleryItem);
	abstract public function delete($galleryItemId);
}