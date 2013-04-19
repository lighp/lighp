<?php
namespace lib\entities;

abstract class PortfolioGalleryItem extends \core\Entity {
	protected $source, $title, $projectName, $kind;

	protected static $enabledItems = array(
		'\lib\entities\PortfolioGalleryYoutubeVideo',
		'\lib\entities\PortfolioGalleryThumbnail',
		'\lib\entities\PortfolioGalleryImage'
	);

	// SETTERS //

	public function setSource($source) {
		if (!is_string($source) || empty($source)) {
			throw new \InvalidArgumentException('Invalid gallery item source');
		}

		$this->source = $source;
	}

	public function setTitle($title) {
		if (!is_string($title) || empty($title)) {
			throw new \InvalidArgumentException('Invalid gallery item title');
		}

		$this->title = $title;
	}

	public function setProjectName($name) {
		if (!is_string($name) || empty($name)) {
			throw new \InvalidArgumentException('Invalid gallery item project name');
		}

		$this->projectName = $name;
	}

	public function setKind($kind) {
		if (!is_string($kind)) {
			throw new \InvalidArgumentException('Invalid gallery item kind');
		}

		$this->kind = $kind;
	}

	// GETTERS //

	public function source() {
		return $this->source;
	}

	public function title() {
		return $this->title;
	}

	public function projectName() {
		return $this->projectName;
	}

	public function link() {
		return $this->source;
	}

	abstract public function render();

	// STATIC //
	
	public static function build($data) {
		if (isset($data['kind']) && !empty($data['kind'])) {
			$className = '\lib\entities\PortfolioGallery' . ucfirst($data['kind']);

			if (!in_array($className, self::$enabledItems) || !in_array(__CLASS__, class_parents($className))) {
				return null;
			}

			return new $className($data);
		} else {
			foreach (self::$enabledItems as $itemClass) {
				if (!in_array(__CLASS__, class_parents($itemClass))) {
					continue;
				}

				try {
					$item = new $itemClass($data);
				} catch (\Exception $e) {
					continue;
				}

				return $item;
			}
		}
	}
}