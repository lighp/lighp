<?php
namespace core;

use \InvalidArgumentException;

/**
 * A user.
 * @author emersion
 * @since 1.0alpha1
 */
class User extends ApplicationComponent {
	public function isAdmin() {
		$session = $this->app()->httpRequest()->session();

		return ($session->has('user_admin') && $session->get('user_admin') === true);

	}

	public function isLogged() {
		$session = $this->app()->httpRequest()->session();

		return ($session->has('user_logged') && $session->get('user_logged') === true);
	}

	public function lang() {
		$session = $this->app()->httpRequest()->session();

		$lang = 'en';

		if ($session->has('user_lang')) {
			$lang = $session->get('user_lang');
		} else {
			$browserLang = $this->app()->httpRequest()->lang();

			if (!empty($browserLang)) {
				$lang = $browserLang;
			}
		}

		return $lang;
	}

	public function permissions() {
		$session = $this->app()->httpRequest()->session();

		$perm = array();
		if ($session->has('user_permissions')) {
			$perm = $session->get('user_permissions');
		}

		return $perm;
	}

	public function can($permission) {
		return in_array($permission, $this->permissions());
	}

	public function setAdmin($isAdmin) {
		$session = $this->app()->httpRequest()->session();

		if (!is_bool($isAdmin)) {
			throw new InvalidArgumentException('Invalid user admin value');
		}

		$session->set('user_admin', $isAdmin);
	}

	public function setLogged($isLogged) {
		$session = $this->app()->httpRequest()->session();

		if (!is_bool($isLogged)) {
			throw new InvalidArgumentException('Invalid user logged value');
		}

		$session->set('user_admin', $isLogged);
	}

	public function setPermissions(array $permissions) {
		$session->set('user_permissions', $permissions);
	}

	public function setLang($lang) {
		//TODO
		
		/*if (!preg_match('#^[a-z]{2}([-_][A-Z]{2})?$#', $lang)) {
			throw new InvalidArgumentException('Invalid language "'.$lang.'"');
		}*/
	}
}