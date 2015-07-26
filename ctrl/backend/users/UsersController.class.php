<?php

namespace ctrl\backend\users;

use core\http\HTTPRequest;
use lib\entities\User;

class UsersController extends \core\BackController {
	protected function _addBreadcrumb($page = array()) {
		$breadcrumb = array(
			array(
				'url' => $this->app->router()->getUrl('main', 'showModule', array(
					'module' => $this->module()
				)),
				'title' => 'Utilisateurs'
			)
		);

		$this->page()->addVar('breadcrumb', array_merge($breadcrumb, array($page)));
	}

	protected function _rehashPassword(User $user, $password) {
		$manager = $this->managers->getManagerOf('users');
		$cryptoManager = $this->managers->getManagerOf('crypto');

		if ($cryptoManager->needsRehash($user['password'])) {
			$user['password'] = $cryptoManager->hashPassword($password);

			try {
				$manager->update($user);
			} catch (\Exception $e) {
				// Silently ignore error
			}
		}
	}

	public function executeIndex(HTTPRequest $request) {
		$this->page()->addVar('title', 'Connexion');

		$manager = $this->managers->getManagerOf('users');
		$cryptoManager = $this->managers->getManagerOf('crypto');

		if ($request->postExists('login-username')) {
			$username = $request->postData('login-username');
			$password = $request->postData('login-password');

			try {
				$user = $manager->getByUsername($username);
			} catch (\Exception $e) {
				sleep(3); // Delay to prevent bruteforce attacks
				$this->page()->addVar('error', 'Incorrect username or password');
			}

			if ($cryptoManager->verifyPassword($password, $user['password'])) {
				$this->_rehashPassword($user, $password);
				$this->app->user()->setAdmin(true);
				$this->app->httpResponse()->redirect('');
			} else {
				sleep(3); // Delay to prevent bruteforce attacks
				$this->page()->addVar('error', 'Incorrect username or password');
			}
		}
	}

	public function executeLogout(HTTPRequest $request) {
		$this->page()->addVar('title', 'Déconnexion');

		$this->app->user()->setAdmin(false);
		$this->app->httpResponse()->redirect('');
	}

	public function executeUpdateMe(HTTPRequest $request) {
		// TODO
	}

	public function executeInsert(HTTPRequest $request) {
		$this->page()->addVar('title', 'Ajouter un utilisateur');
		$this->_addBreadcrumb();

		$manager = $this->managers->getManagerOf('users');
		$cryptoManager = $this->managers->getManagerOf('crypto');

		if ($request->postExists('username')) {
			$userData = array(
				'username' => $request->postData('username'),
				'password' => $request->postData('password')
			);

			$this->page()->addVar('user', $userData);

			$passwordConfirm = $request->postData('password-confirm');
			if ($userData['password'] !== $passwordConfirm) {
				$this->page()->addVar('error', 'Les deux mots de passe ne correspondent pas');
				return;
			}

			// Hash password
			$userData['password'] = $cryptoManager->hashPassword($userData['password']);

			try {
				$user = new User($userData);
			} catch(\InvalidArgumentException $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			try {
				$manager->insert($user);
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('inserted?', true);
		}
	}

	public function executeDelete(HTTPRequest $request) {
		$this->page()->addVar('title', 'Supprimer un utilisateur');
		$this->_addBreadcrumb();

		$manager = $this->managers->getManagerOf('users');

		$username = $request->getData('username');

		if ($request->postExists('check')) {
			try {
				$manager->deleteByUsername($username);
			} catch(\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('deleted?', true);
		} else {
			$user = $manager->getByUsername($username);
			$this->page()->addVar('user', $user);
		}
	}

	public function listUsers() {
		$manager = $this->managers->getManagerOf('users');
		$users = $manager->listAll();

		$list = array();
		foreach($users as $user) {
			$list[] = array(
				'title' => $user['username'],
				//'shortDescription' => '',
				'vars' => array('username' => $user['username'])
			);
		}

		return $list;
	}
}