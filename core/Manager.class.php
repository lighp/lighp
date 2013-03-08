<?php
namespace core;

/**
 * A data manager.
 * @author Simon Ser
 * @since 1.0alpha1
 */
abstract class Manager {
	/**
	 * The DAO which will be used to access data from this manager.
	 * @var object
	 */
	protected $dao;

	/**
	 * Initialize the manager.
	 * @param object $dao The DAO which will be used to access data from this manager.
	 */
	public function __construct($dao) {
		$this->dao = $dao;
	}
}