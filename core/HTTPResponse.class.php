<?php
namespace core;

/**
 * HTTPResponse represente la reponse HTTP.
 * @author $imon
 * @version 1.0
 */
class HTTPResponse {
	/**
	 * Le contenu de la reponse.
	 * @var Page
	 */
	protected $page;

	/**
	 * Ajouter une en-tete HTTP.
	 * @param string $header L'en-tete.
	 */
	public function addHeader($header) {
		header($header);
	}

	/**
	 * Enlever une en-tete HTTP.
	 * @param string $header L'en-tete.
	 */
	public function removeHeader($header) {
		header_remove($header);
	}

	/**
	 * Rediriger l'utilisateur vers une autre URL.
	 * @param string $location L'URL de destination.
	 */
	public function redirect($location) {
		$this->addHeader('Location: '.$location);
		exit;
	}

	/**
	 * Envoyer la reponse.
	 */
	public function send() {
		exit($this->page->generate());
	}

	/**
	 * Definir le contenu de la reponse.
	 * @param Page $page La page correspondante.
	 */
	public function setPage(Page $page) {
		$this->page = $page;
	}

	// Changement par rapport à la fonction setcookie() : le dernier argument est par défaut à true
	/**
	 * Definir un cookie.
	 * @param string $name Le nom du cookie.
	 * @param mixed $value La valeur du cookie.
	 * @param int $expire La duree d'expiration.
	 * @param string $path Le chemin pour lequel le cookie est actif.
	 * @param string $domain Le domaine pour lequel le cookie est actif.
	 * @param bool $secure Definit si le cookie est securise.
	 * @param bool $httpOnly Definit si le cookie ne fonctionne qu'avec le protocole HTTP
	 */
	public function setCookie($name, $value = '', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true) {
		setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
	}

	/**
	 * Renvoyer une erreur 404 (page introuvable).
	 */
	public function redirect404($app) {
		$page = new Page($app);
		$page->setTemplate(__DIR__.'/../tpl/error/404.html');

		$this->setPage($page);

		$this->addHeader('HTTP/1.0 404 Not Found');

		$this->send();
	}
}