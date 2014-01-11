<?php
namespace lib;

if (!isset($_SESSION)) {
	session_start();
}

class Captcha {
	protected $id, $question, $result, $dict;

	public function __construct() {
		$this->_generate();
		$this->_remember();
	}

	protected function _generate() {
		$n1 = mt_rand(0, 10);
		$n2 = mt_rand(0, 10);

		$humanNbr = array('zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'height', 'nine', 'ten');

		if (!empty($this->dict)) {
			foreach($humanNbr as $i => $englishNbr) {
				$humanNbr[$i] = $this->dict->get('numbers.'.$englishNbr);
			}
			$this->question = $this->dict->get('howMuchIs') . ' ' . $humanNbr[$n1] .' '.$this->dict->get('plus').' ' . $humanNbr[$n2] . ' ?';
		} else {
			$this->question = 'Combien font ' . $humanNbr[$n1] .' plus ' . $humanNbr[$n2] . ' ?';
		}
		
		$this->result = $n1 + $n2;
	}

	public function question() {
		return $this->question;
	}

	public function id() {
		return $this->id;
	}

	public function check($result) {
		return ($this->result === $result);
	}

	public function setDictionary(\core\ModuleTranslation $dict) {
		$this->dict = $dict;

		$this->_generate();
		$this->_remember();
	}

	protected function _remember() {
		if (!isset($_SESSION['captcha'])) {
			$_SESSION['captcha'] = array();
		}

		$this->id = count($_SESSION['captcha']);
		$_SESSION['captcha'][$this->id] = $this;

		return $this->id;
	}

	public static function build() {
		return new self();
	}

	public static function get($id) {
		if (!isset($_SESSION['captcha']) || !isset($_SESSION['captcha'][(int) $id])) {
			return;
		}

		return $_SESSION['captcha'][(int) $id];
	}
}