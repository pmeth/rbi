<?php
namespace Pmeth\Common;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Request
 *
 * @author Peter Meth
 */



class Request {

	protected $getVars = array();
	protected $postVars = array();
	protected $sessionVars = array();
	protected $cookieVars = array();

	public function __construct() {
		session_start();
		$this->getVars = !empty($_GET) ? $_GET : array();
		$this->postVars = !empty($_POST) ? $_POST : array();
		$this->sessionVars = !empty($_SESSION) ? $_SESSION : array();
		$this->cookieVars = !empty($_COOKIE) ? $_COOKIE : array();
		
		// undo the effects of magic_quotes
		if (get_magic_quotes_gpc()) {
			$this->postVars = stripslashes($this->postVars);
			$this->getVars = stripslashes($this->getVars);
			$this->cookieVars = stripslashes($this->cookieVars);
		}
	}

	public function getGetVars() {
		return $this->getVars;
	}

	public function getPostVars() {
		
		return $this->postVars;
	}

	public function getSessionVars() {
		return $this->sessionVars;
	}

	public function getPostVar($index) {
		if (isset($this->postVars[$index])) {
			return $this->postVars[$index];
		} else {
			return false;
		}
	}

	public function getGetVar($index) {
		if (isset($this->getVars[$index])) {
			return $this->getVars[$index];
		} else {
			return false;
		}
	}

	public function getSessionVar($index) {

		if (isset($this->sessionVars[$index])) {
			return $this->sessionVars[$index];
		} else {
			return false;
		}
	}

	public function setSessionVar($index, $value) {
		$_SESSION[$index] = $value;
		$this->sessionVars[$index] = $value;
	}

	public function unsetSessionVar($index) {
		if(isset($_SESSION[$index])) {
			unset($_SESSION[$index]);
			unset($this->sessionVars[$index]);
		}
	}

}

?>
