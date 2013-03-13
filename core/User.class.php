<?php
namespace core;

if (!isset($_SESSION)) {
	session_start();
}

class User extends ApplicationComponent {
	public function isAdmin() {
		return (isset($_SESSION['user_admin']) && $_SESSION['user_admin'] === true);
	}

	public function setAdmin($isAdmin) {
		if (!is_bool($isAdmin)) {
			throw new \InvalidArgumentException('Invalid user admin value');
		}

		$_SESSION['user_admin'] = $isAdmin;
	}
}