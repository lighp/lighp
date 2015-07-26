<?php

namespace lib\entities;

use core\Entity;

class User extends Entity {
	protected $username, $password;

	// SETTERS //

	public function setUsername($username) {
		if (!is_string($username) || empty($username)) {
			throw new InvalidArgumentException('Invalid user username');
		}
		$this->username = $username;
	}

	public function setPassword($password) {
		if (!is_string($password) || empty($password)) {
			throw new InvalidArgumentException('Invalid user password');
		}
		$this->password = $password;
	}

	// GETTERS //

	public function username() {
		return $this->username;
	}

	public function password() {
		return $this->password;
	}
}