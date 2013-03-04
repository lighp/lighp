<?php
namespace core\dao;

class PDOFactory {
	public static function getMysqlConnexion() {
		$db = new \PDO('mysql:host=localhost;dbname=lighp', 'root', '');
		$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		return $db;
	}
}