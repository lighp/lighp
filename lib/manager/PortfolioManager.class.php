<?php
namespace lib\manager;

abstract class PortfolioManager extends \core\Manager {
	abstract public function getLeadingItems();
	abstract public function getAboutTexts();
	abstract public function getAboutLinks();
}