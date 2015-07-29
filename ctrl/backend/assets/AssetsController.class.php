<?php

namespace ctrl\backend\assets;

use core\http\HTTPRequest;
use core\Config;
use core\fs\Pathfinder;

class AssetsController extends \core\BackController {
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

	protected function _getConfig($type) {
		if ($type != 'stylesheets' && $type != 'scripts') {
			throw new \InvalidArgumentException('Invalid asset type: '.$type);
		}

		$path = Pathfinder::getPathFor('config').'/core/'.$type.'.json';
		return new Config($path);
	}

	protected function executeInsertAsset($req, $type) {
		$this->_addBreadcrumb();

		if ($req->postExists('filename')) {
			$filename = $req->postData('filename');

			$this->page()->addVar('filename', $filename);

			$config = $this->_getConfig($type);
			$assets = $config->read();
			$assets[] = array(
				'filename' => $filename
			);

			try {
				$config->write($assets);
			} catch (\Exception $e) {
				$this->page()->addVar('error', $e->getMessage());
				return;
			}

			$this->page()->addVar('inserted?', true);
		}
	}

	public function executeInsertStylesheet(HTTPRequest $req) {
		$this->page()->addVar('title', 'Ajouter une feuille de style');
		$this->executeInsertAsset($req, 'stylesheets');
	}

	public function executeInsertScript(HTTPRequest $req) {
		$this->page()->addVar('title', 'Ajouter un fichier Javascript');
		$this->executeInsertAsset($req, 'scripts');
	}

	protected function listAssets($type) {
		$assets = $this->_getConfig($type)->read();

		$list = array();
		foreach($assets as $i => $asset) {
			$list[] = array(
				'title' => $asset['filename'],
				//'shortDescription' => '',
				'vars' => array('index' => $i)
			);
		}

		return $list;
	}

	public function listStylesheets() {
		return $this->listAssets('stylesheets');
	}

	public function listScripts() {
		return $this->listAssets('scripts');
	}
}