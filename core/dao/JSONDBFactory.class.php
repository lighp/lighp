<?php
namespace core\dao;

class JSONDBFactory {
	public static function getLocalConnexion() {
		$db = new \core\dao\json\Database(__DIR__.'/../../db/');

		return $db;
	}
}