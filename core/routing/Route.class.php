<?php

namespace core\routing;

/**
 * A route to a page.
 * @author Simon Ser
 * @since 1.0alpha1
 */
class Route {
	/**
	 * The route's action.
	 * @var string
	 */
	protected $action;

	/**
	 * The route's module.
	 * @var string
	 */
	protected $module;

	/**
	 * The route's URL.
	 * @var string
	 */
	protected $url;

	/**
	 * The route's variables' names.
	 * @var array
	 */
	protected $varsNames;

	/**
	 * The route's variables' values.
	 * @var array
	 */
	protected $vars = array();

	/**
	 * True if the route is a redirection.
	 * @var boolean
	 */
	protected $redirect = false;

	/**
	 * Initialize the route.
	 * @param string $url       The route's URL.
	 * @param string $module    The route's module.
	 * @param string $action    The route's action.
	 * @param array  $varsNames The route's variables' names.
	 * @param bool   $redirect  True if this route is temporary, false otherwise.
	 */
	public function __construct($url, $module, $action, array $varsNames, $redirect = false) {
		$this->setUrl($url);
		$this->setModule($module);
		$this->setAction($action);
		$this->setVarsNames($varsNames);
		$this->setRedirect($redirect);
	}

	/**
	 * Determine if this route has variables.
	 * @return boolean True if this route has variables, false otherwise.
	 */
	public function hasVars() {
		return !empty($this->varsNames);
	}

	/**
	 * Determine if this route matches with a given URL.
	 * @param  string $url The URL.
	 * @return bool        True if this route matches, false otherwise.
	 */
	public function match($url) {
		if (preg_match('`^'.$this->url.'(\?.+)?$`', $this->_beautifyPath($url), $matches)) {
			array_shift($matches);
			return $matches;
		} else {
			return false;
		}
	}

	/**
	 * Set this route's action.
	 * @param string $action The action's name.
	 */
	public function setAction($action) {
		if (is_string($action)) {
			$this->action = $action;
		}
	}

	/**
	 * Set this route's module.
	 * @param string $module The module's name.
	 */
	public function setModule($module) {
		if (is_string($module)) {
			$this->module = $module;
		}
	}

	/**
	 * Set this route's URL.
	 * @param string $url The URL.
	 */
	public function setUrl($url) {
		if (is_string($url)) {
			$this->url = $this->_beautifyPath($url);
		}
	}

	/**
	 * Set this route's variables' names.
	 * @param string $varsNames The variables' names.
	 */
	public function setVarsNames(array $varsNames) {
		$this->varsNames = $varsNames;
	}

	/**
	 * Mark this route as a redirection or not.
	 * @param bool $redirect True if this route is a redirection, false otherwise.
	 */
	public function setRedirect($redirect) {
		if (is_bool($redirect)) {
			$this->redirect = $redirect;
		}
	}

	/**
	 * Set this route's variables' values.
	 * @param string $vars The variables' values.
	 */
	public function setVars(array $vars) {
		$this->vars = $vars;
	}

	/**
	 * Get this route's action.
	 * @return string The action's name.
	 */
	public function action() {
		return $this->action;
	}

	/**
	 * Get this route's module.
	 * @return string The module's name.
	 */
	public function module() {
		return $this->module;
	}

	/**
	 * Get this route's variables' names.
	 * @return string The variables' names.
	 */
	public function vars() {
		return $this->vars;
	}

	/**
	 * Get this route's variables' values.
	 * @return string The variables' values.
	 */
	public function varsNames() {
		return $this->varsNames;
	}

	/**
	 * Check if this route is a redirection.
	 * @return bool True if this route is a redirection, false otherwise.
	 */
	public function redirect() {
		return $this->redirect;
	}

	/**
	 * Build this route's URL.
	 * @return string The route's URL.
	 */
	public function buildUrl() {
		$url = $this->url;

		if ($this->hasVars()) {
			$vars = $this->vars();

			foreach($this->varsNames as $varKey => $varName) {
				$varValue = '$1';

				//Use $specialCharRegex to replace preg_quote ?
				//Be careful with - and . in URLs
				if (isset($vars[$varName])) {
					$varValue = preg_quote($vars[$varName]);
				} elseif (isset($vars[$varKey])) {
					$varValue = preg_quote($vars[$varKey]);
				}

				$url = preg_replace('#\\((.+)\\)#U', $varValue, $url, 1);
			}
		}

		$specialCharRegex = '\\.\\+\\?\\[\\^\\]\\$\\(\\)\\{\\}\\=\\!\\<\\>\\|\\:\\*'; //Backslashes are processed apart
		$url = preg_replace('#(\\(.+\\)|\\{.+\\}|\\[.+\\])#U', '', $url); // (), {}, []
		$url = preg_replace('#^['.$specialCharRegex.']#', '', $url); //Special char at the begining
		$url = preg_replace('#([^\\\\])['.$specialCharRegex.']+#', '$1', $url); //Unescaped special char in the URL
		//$url = preg_replace('#'.$specialCharRegex.'$#', '', $url); //Special char at the end
		$url = preg_replace('#\\\\(['.$specialCharRegex.'\\\\])#', '$1', $url); //Escaped char or \
		$url = preg_replace('#([^\\\\])\\\\#', '$1', $url); //Unescaped \ in the URL

		return $this->_beautifyPath($url);
	}

	protected function _beautifyPath($url) {
		$url = preg_replace('#/+#', '/', $url);
		$url = preg_replace('#^/#', '', $url);

		return $url;
	}
}