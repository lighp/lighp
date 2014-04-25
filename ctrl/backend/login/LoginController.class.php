<?php

namespace ctrl\backend\login;

use core\apps\Application;
use core\http\HTTPRequest;

class LoginController extends \core\BackController {
	protected function _addBreadcrumb($page = array()) {
		$breadcrumb = array(
			array(
				'url' => $this->app->router()->getUrl('main', 'showModule', array(
					'module' => $this->module()
				)),
				'title' => 'Compte administrateur'
			)
		);

		$this->page()->addVar('breadcrumb', array_merge($breadcrumb, array($page)));
	}

	protected function _rehashPassword($password) {
		$cryptoManager = $this->managers->getManagerOf('crypto');

		$configData = $this->config->read();
		$hash = $configData['password'];

		if ($cryptoManager->needsRehash($hash)) {
			$configData['password'] = $cryptoManager->hashPassword($password);

			try {
				$this->config->write($configData);
			} catch (\Exception $e) {}
		}
	}

	public function executeIndex(HTTPRequest $request) {
		$this->page()->addVar('title', 'Connexion');

		$cryptoManager = $this->managers->getManagerOf('crypto');

		if ($request->postExists('login-username')) {
			$username = $request->postData('login-username');
			$password = $request->postData('login-password');

			$configData = $this->config->read();
			$passwordHash = $configData['password'];

			if ($username == $configData['username'] && $cryptoManager->verifyPassword($password, $passwordHash)) {
				$this->_rehashPassword($password);
				$this->app->user()->setAdmin(true);
				$this->app->httpResponse()->redirect('');
			} else {
				sleep(3); //Delay to prevent bruteforce attacks
				$this->page()->addVar('error', 'Incorrect username or password');
			}
		}
	}

	public function executeLogout(HTTPRequest $request) {
		$this->page()->addVar('title', 'Déconnexion');

		$this->app->user()->setAdmin(false);
		$this->app->httpResponse()->redirect('');
	}

	public function executeUpdate(HTTPRequest $request) {
		$this->page()->addVar('title', 'Modifier les identifiants de connexion');
		$this->_addBreadcrumb();

		$cryptoManager = $this->managers->getManagerOf('crypto');

		$configData = $this->config->read();
		$this->page()->addVar('username', $configData['username']);

		if ($request->postExists('login-password')) {
			$password = $request->postData('login-password');

			$passwordHash = $configData['password'];

			if ($cryptoManager->verifyPassword($password, $passwordHash)) {
				$configData['username'] = $request->postData('login-update-username');

				$newPassword = $request->postData('login-update-password');
				if (!empty($newPassword)) { //If the password is not empty, the user wants to change it
					$configData['password'] = $cryptoManager->hashPassword($newPassword);
				}

				try {
					$this->config->write($configData);
				} catch (\Exception $e) {
					$this->page()->addVar('error', $e->getMessage());
					return;
				}

				$this->page()->addVar('updated?', true);
			} else {
				$this->page()->addVar('error', 'Incorrect password');
			}
		}
	}
}