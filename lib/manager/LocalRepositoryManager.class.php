<?php
namespace lib\manager;

abstract class LocalRepositoryManager extends \core\Manager implements \lib\Repository {
	abstract public function remove($packages);
}