<?php
namespace lib\entities;

class PortfolioGalleryYoutubeVideo extends PortfolioGalleryItem {
	protected function _videoId() {
		$src = $this->source;

		if (preg_match('#^https?://(www\.)?youtube\.com/watch\?v=([a-zA-Z0-9-]+)$#', $src, $matches)) {
			$videoId = end($matches);
		} else if (preg_match('#^https?://youtu.be/([a-zA-Z0-9-]+)$#', $src, $matches)) {
			$videoId = end($matches);
		} else {
			$videoId = null;
		}

		return $videoId;
	}

	public function __construct($data = array()) {
		parent::__construct($data);

		$src = $this->source;

		if (!preg_match('#^https?://(www\.)?youtube\.com/watch\?v=[a-zA-Z0-9-]+$#', $src) && !preg_match('#^https?://youtu.be/[a-zA-Z0-9-]+$#', $src)) {
			throw new \RuntimeException('"'.$this->source.'" : invalid Youtube URL');
		}
	}

	public function render() {
		$videoId = $this->_videoId();

		$output = '<iframe width="360" height="255" src="http://www.youtube.com/embed/' . $videoId . '" frameborder="0" allowfullscreen></iframe>';

		return $output;
	}
}