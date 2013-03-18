<?php
namespace lib\entities;

class PortfolioGalleryImage extends PortfolioGalleryItem {
	const IMGS_DIR = 'img/portfolio/gallery';

	protected function _path() {
		$path = $this->source;

		if (!preg_match('#^(https?|ftps?)://#', $path)) {
			$path = self::IMGS_DIR . '/' . $this->projectName . '/' . $path;
		}

		return $path;
	}

	public function __construct($data = array()) {
		parent::__construct($data);

		$url = $this->_path();

		if (!preg_match('#^(https?|ftps?)://#', $url)) {
			$url = __DIR__ . '/../../public/' . $url;

			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mimeType = @finfo_file($finfo, $url);

			if ($mimeType !== false) {
				$mimeData = explode('/', $mimeType);

				if ($mimeData[0] != 'image') {
					throw new \RuntimeException('"'.$this->source.'" : specified file is not an image');
				}
			}
		}
	}

	public function link() {
		return $this->_path();
	}

	public function render() {
		$url = $this->_path();

		$output = '<img src="' . $url . '" alt="' . $this->title . '" title="' . $this->title . '" />';

		return $output;
	}
}