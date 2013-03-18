<?php
namespace lib\entities;

class PortfolioGalleryThumbnail extends PortfolioGalleryImage {
	protected function _thumbnailsDir() {
		return self::IMGS_DIR . '/' . $this->projectName . '/thumbnails';
	}

	protected function _thumbnailPath() {
		return $this->_thumbnailsDir() . '/' . $this->source;
	}

	public function __construct($data = array()) {
		parent::__construct($data);

		$url = $this->_path();
		$thumbnailsDirPath = __DIR__ . '/../../public/' . $this->_thumbnailsDir();
		$thumbnailPath = __DIR__ . '/../../public/' . $this->_thumbnailPath();
		$sourcePath = __DIR__ . '/../../public/' . $this->_path();

		if (!is_dir($thumbnailsDirPath)) {
			throw new \RuntimeException('"'.$this->source.'" : thumnails\' directory "'.$thumbnailsDirPath.'" doesn\'t exist');
		}

		if (preg_match('#^(https?|ftps?)://#', $url)) {
			throw new \RuntimeException('"'.$this->source.'" : only local images are allowed to be thumnailed');
		}

		if (!file_exists($thumbnailPath)) {
			if (!\lib\portfolio\Thumbnail::checkSupport()) {
				throw new \RuntimeException('"'.$this->source.'" : cannot generate thumbnails, some libraries are missing (e.g. GD)');
			}

			$extension = pathinfo($sourcePath, PATHINFO_EXTENSION);

			if (!in_array($extension, \lib\portfolio\Thumbnail::getSupportedExtensions())) {
				throw new \RuntimeException('"'.$this->source.'" : file format "'.$extension.'" not supported');
			}
		}
	}

	public function render() {
		$sourcePath = __DIR__ . '/../../public/' . $this->_path();
		$thumbnailPath = __DIR__ . '/../../public/' . $this->_thumbnailPath();

		if (!file_exists($thumbnailPath)) {
			$thumbnail = new \lib\portfolio\Thumbnail($sourcePath);
			$thumbnail->setWidth(360);

			$thumbnail->generate($thumbnailPath);
		}

		$output = '<img src="' . $this->_thumbnailPath() . '" alt="' . $this->title . '" title="' . $this->title . '" />';

		return $output;
	}
}