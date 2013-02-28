<?php
namespace lib\manager;

abstract class PortfolioCategoriesManager extends \core\Manager {
	abstract public function getList();
	abstract public function get($catName);
}