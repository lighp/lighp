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
				$list[] = $user;
			} catch(\InvalidArgumentException $e) {
				continue;
			}
		}

		return $list;
	}

	public function insert(User $user) {
		$file = $this->dao->open('users/users');
		$items = $file->read();

		$existing = $items->filter(array('username' => $user['username']));
		if (count($existing) > 0) {
			throw new \RuntimeException('Another user is already registered with the username "'.$user['username'].'"');
		}

		$item = $this->dao->createItem($user->toArray());
		$items[] = $item;
		$file->write($items);
	}

	public function update(User $user) {
		$file = $this->dao->open('users/users');
		$items = $file->read();
		
		foreach ($items as $i => $item) {
			if ($item['username'] == $user['username']) {
				$items[$i] = $this->dao->createItem($user->toArray());
				$file->write($items);
				return;
			}
		}

		throw new \RuntimeException('Cannot find a user with username "'.$user['username'].'"');
	}

	public function deleteByUsername($username) {
		$file = $this->dao->open('users/users');
		$items = $file->read();
		foreach ($items as $i => $item) {
			if ($item['username'] == $username) {
				unset($items[$i]);
				$file->write($items);
				return;
			}
		}

		throw new \RuntimeException('Cannot find a user with username "'.$username.'"');
	}
}