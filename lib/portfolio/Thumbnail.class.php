<?php
namespace lib\portfolio;

class Thumbnail {
	protected $originalImg;

	protected $dimentions = array(null, null);

	protected static $supportedExtensions = array('jpg', 'jpeg', 'png');

	public function __construct($originalImg) {
		$this->originalImg = $originalImg;
	}

	public function getWidth() {
		return $this->dimentions[0];
	}

	public function getHeight() {
		return $this->dimentions[1];
	}

	public function getDimentions() {
		return $this->dimentions;
	}

	public function setWidth($width) {
		$this->dimentions[0] = $width;
	}

	public function setHeight($height) {
		$this->dimentions[1] = $height;
	}

	public function setDimentions($width, $height = null) {
		$this->setWidth($width);
		$this->setHeight($height);
	}

	public function generate($dest) {
		$origExtension = strtolower(pathinfo($this->originalImg, PATHINFO_EXTENSION));
		$newExtension = strtolower(pathinfo($dest, PATHINFO_EXTENSION));

		list($origWidth, $origHeight) = getimagesize($this->originalImg);
		list($newWidth, $newHeight) = $this->getDimentions();

		if ($newWidth === null && $newHeight === null) {
			return false;
		}

		if ($newWidth === null || $newHeight === null) {
			$ratio = $origWidth / $origHeight;

			if ($newWidth === null) {
				$newWidth = (int) round($newHeight * $ratio);
			}
			if ($newHeight === null) {
				$newHeight = (int) round($newWidth / $ratio);
			}
		}

		switch ($origExtension) {
			case 'jpg':
			case 'jpeg':
				$original = imagecreatefromjpeg($this->originalImg);
				break;
			case 'png':
				$original = imagecreatefrompng($this->originalImg);
				break;
			default:
				return false;
		}

		$thumbnail = imagecreatetruecolor($newWidth, $newHeight);

		imagecopyresampled($thumbnail, $original, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

		$result = false;
		switch ($newExtension) {
			case 'jpg':
			case 'jpeg':
				$result = imagejpeg($thumbnail, $dest);
				break;
			case 'png':
				$result = imagepng($thumbnail, $dest);
				break;
		}

		if ($result) {
			chmod($dest, 0777);
			return true;
		} else {
			return false;
		}
	}

	public static function getSupportedExtensions() {
		return self::$supportedExtensions;
	}

	public static function checkSupport() {
		return extension_loaded('gd');
	}
}