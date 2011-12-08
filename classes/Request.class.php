<?php

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

	public function __construct() {
		$this->getVars = !empty($_GET) ? $_GET : array();
		$this->postVars = !empty($_POST) ? $_POST : array();
	}

	public function getGetVars() {
		return $this->getVars;
	}

	public function getPostVars() {
		return $this->postVars;
	}

	public function getPostVar($index){
		if(isset($this->postVars[$index])) {
			return $this->postVars[$index];
		} else {
			return false;
		}
	}
	public function getGetVar($index){
		if(isset($this->getVars[$index])) {
			return $this->getVars[$index];
		} else {
			return false;
		}
	}
}

?>
