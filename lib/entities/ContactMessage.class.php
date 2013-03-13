<?php
namespace lib\entities;

class ContactMessage extends \core\Entity {
	protected $senderName, $senderEmail, $subject, $content;

	// SETTERS //

	public function setSenderName($name) {
		if (!is_string($name) || empty($name) || !preg_match('#^[a-zA-Z0-9._\s\t-]+$#', $name)) {
			throw new \InvalidArgumentException('Invalid sender name');
		}

		$this->senderName = $name;
	}

	public function setSenderEmail($email) {
		if (!is_string($email) || empty($email) || !preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $email)) {
			throw new \InvalidArgumentException('Invalid sender e-mail');
		}

		$this->senderEmail = $email;
	}

	public function setSubject($subject) {
		if (!is_string($subject)) {
			throw new \InvalidArgumentException('Invalid message subject');
		}

		if (empty($subject)) {
			$subject = 'No subject.';
		}

		$this->subject = $subject;
	}

	public function setContent($content) {
		if (!is_string($content) || empty($content)) {
			throw new \InvalidArgumentException('Invalid message content');
		}

		$this->content = $content;
	}

	// GETTERS //

	public function senderName() {
		return $this->senderName;
	}

	public function senderEmail() {
		return $this->senderEmail;
	}

	public function subject() {
		return $this->subject;
	}

	public function content() {
		return $this->content;
	}
}