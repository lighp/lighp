<?php
namespace lib\manager;

use lib\entities\User;

class UsersManager_json extends UsersManager {
	public function getByUsername($username) {
		$file = $this->dao->open('users/users');
		$data = $file->read()->filter(array('username' => $username));

		if (empty($data)) {
			throw new \RuntimeException('Cannot find a user with username "'.$username.'"');
		}

		return new User($data[0]);
	}

	public function listAll() {
		$file = $this->dao->open('users/users');
		$data = $file->read();

		$list = array();
		foreach($data as $userData) {
			try {
				$user = new User($userData);
			} catch(\InvalidArgumentException $e) {
				continue;
			}
		}

		return $list;
	}
}