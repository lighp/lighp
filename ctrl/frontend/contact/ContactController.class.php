<?php
namespace ctrl\frontend\contact;

class ContactController extends \core\BackController {
	public function executeIndex(\core\HTTPRequest $request) {
		$captcha = \lib\Captcha::build();

		$this->page->addVar('captcha', $captcha);

		if ($request->postExists('message-sender-name')) {
			$messageData = array(
				'senderName' => trim($request->postData('message-sender-name')),
				'senderEmail' => $request->postData('message-sender-email'),
				'subject' => trim($request->postData('message-subject')),
				'content' => trim($request->postData('message-content'))
			);

			$this->page->addVar('message', $messageData);

			try {
				$message = new \lib\entities\ContactMessage($messageData);
			} catch(\InvalidArgumentException $e) {
				$this->page->addVar('error', $e->getMessage());
				return;
			}

			$captchaId = (int) $request->postData('captcha-id');
			$captchaValue = (int) $request->postData('captcha-value');

			$captcha = \lib\Captcha::get($captchaId);
			if (empty($captcha)) {
				$this->page->addVar('error', 'Your session has expired. Please try again');
				return;
			}

			if (!$captcha->check($captchaValue)) {
				$this->page->addVar('error', 'Invalid captcha');
				return;
			}

			$contactConfig = $this->config->read();

			$messageDest = $contactConfig['email'];
			$messageSubject = $contactConfig['subjectPrepend'].' '.$message['subject'];
			$messageContent = 'Nom : '.$message['senderName'].' <'.$message['senderEmail'].'>'."\n";
			$messageContent .= 'Sujet : '.$message['subject']."\n";
			$messageContent .= 'Message :'."\n".$message['content'];

			$messageHeaders = 'From: '.$message['senderEmail']."\r\n".
			'Reply-To: '.$message['senderEmail']."\r\n" .
			'X-Mailer: PHP/' . phpversion();

			if(mail($messageDest, $messageSubject, $messageContent, $messageHeaders) !== false) {
				$this->page->addVar('messageSent?', true);
			} else {
				$this->page->addVar('error', 'Cannot send message : server error');
			}
		}

		$this->page->addVar('title', 'Contact');
	}
}