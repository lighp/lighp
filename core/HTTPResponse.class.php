<?php
namespace core;

/**
 * The HTTP response.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class HTTPResponse {
	/**
	 * The response's content.
	 * @var ResponseContent
	 */
	protected $content;

	/**
	 * Add a HTTP header.
	 * @param string $header The header to add.
	 */
	public function addHeader($header) {
		header($header);
	}

	/**
	 * Remove an HTTP header.
	 * @param string $header The header to remove.
	 */
	public function removeHeader($header) {
		header_remove($header);
	}

	/**
	 * Redirect the user to another URI.
	 * @param string $location The destination's URI.
	 */
	public function redirect($location) {
		$this->addHeader('Location: '.$location);
		exit;
	}

	/**
	 * Send the response.
	 */
	public function send() {
		exit($this->content->generate());
	}

	/**
	 * Set the response's content.
	 * @param ResponseContent $page The response's content.
	 */
	public function setContent(ResponseContent $content) {
		$this->content = $content;
	}

	// Changes compared to the setcookie() function : the last argument is true by default
	/**
	 * Set a cookie.
	 * @param string $name The cookie's name.
	 * @param mixed $value The cookie's content.
	 * @param int $expire The expiration's duration.
	 * @param string $path The path for which the cookie is defined.
	 * @param string $domain The domain for which the cookie is defined.
	 * @param bool $secure Defines if the cookie will be secure.
	 * @param bool $httpOnly Defines if the cookie will be defined for access with other methods besides HTTP requests (e.g. Javascript).
	 */
	public function setCookie($name, $value = '', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true) {
		setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
	}

	/**
	 * Send a 404 response (not found).
	 * @param Application $app The application.
	 */
	public function redirect404($app) {
		$page = new Page($app);
		$page->setTemplate(__DIR__.'/../tpl/error/404.html');

		$this->setContent($page);

		$this->addHeader('HTTP/1.0 404 Not Found');

		$this->send();
	}
}