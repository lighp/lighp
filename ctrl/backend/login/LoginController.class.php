<?php
namespace ctrl\backend\login;

class LoginController extends \core\BackController {
	protected function _addBreadcrumb($page = array()) {
		$breadcrumb = array(
			array('url' => 'module-'.$this->module.'.html', 'title' => 'Compte administrateur')
		);

		$this->page->addVar('breadcrumb', array_merge($breadcrumb, array($page)));
	}

	protected function _hashPassword($password) {
		return hash('sha512', $password);
	}

	public function executeIndex(\core\HTTPRequest $request) {
		$this->page->addVar('title', 'Connexion');

		if ($request->postExists('login-username')) {
			$username = $request->postData('login-username');
			$password = $request->postData('login-password');

			$configData = $this->config->read();

			if ($username == $configData['username'] && $this->_hashPassword($password) == $configData['password']) {
				$this->app->user()->setAdmin(true);
				$this->app->httpResponse()->redirect('');
			} else {
				$this->page->addVar('error', 'Incorrect username or password');
			}
		}
	}

	public function executeLogout(\core\HTTPRequest $request) {
		$this->page->addVar('title', 'Déconnexion');

		$this->app->user()->setAdmin(false);
		$this->app->httpResponse()->redirect('.');
	}

	public function executeUpdate(\core\HTTPRequest $request) {
		$this->page->addVar('title', 'Modifier les identifiants de connexion');
		$this->_addBreadcrumb();

		$configData = $this->config->read();

		$this->page->addVar('username', $configData['username']);

		if ($request->postExists('login-password')) {
			$password = $request->postData('login-password');

			if ($this->_hashPassword($password) == $configData['password']) {
				$configData['username'] = $request->postData('login-update-username');

				$newPassword = $request->postData('login-update-password');
				if (!empty($newPassword)) {
					$configData['password'] = $this->_hashPassword($newPassword);
				}

				try {
					$this->config->write($configData);
				} catch (\Exception $e) {
					$this->page->addVar('error', $e->getMessage());
					return;
				}

				$this->page->addVar('updated?', true);
			} else {
				$this->page->addVar('error', 'Incorrect password');
			}
		}
	}
}