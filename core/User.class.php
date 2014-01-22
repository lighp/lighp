<?php
namespace core;

if (!isset($_SESSION)) {
	session_start();
}

class User extends ApplicationComponent {
	public function isAdmin() {
		return (isset($_SESSION['user_admin']) && $_SESSION['user_admin'] === true);
	}

	public function isLogged() {
		return (isset($_SESSION['user_logged']) && $_SESSION['user_logged'] === true);
	}

	public function permissions() {
		return array();
	}

	public function can($permission) {
		return false;
	}

	public function setAdmin($isAdmin) {
		if (!is_bool($isAdmin)) {
			throw new \InvalidArgumentException('Invalid user admin value');
		}

		$_SESSION['user_admin'] = $isAdmin;
	}

	public function setLogged($isLogged) {
		if (!is_bool($isLogged)) {
			throw new \InvalidArgumentException('Invalid user logged value');
		}

		$_SESSION['user_logged'] = $isLogged;
	}

	public function setPermissions(array $permissions) {
		$_SESSION['user_permissions'] = $permissions;
	}
}