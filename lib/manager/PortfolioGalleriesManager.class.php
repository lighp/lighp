<?php
namespace lib\manager;

abstract class PortfolioGalleriesManager extends \core\Manager {
	abstract public function get($projectName);
}