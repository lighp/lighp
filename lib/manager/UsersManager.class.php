<?php

namespace lib\manager;

use lib\entities\User;

abstract class UsersManager extends \core\Manager {
	abstract public function getByUsername($username);
	abstract public function listAll();

	abstract public function insert(User &$user);
	abstract public function update(User $user);
	abstract public function deleteByUsername($username);
}