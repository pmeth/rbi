<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Peter Meth
 */
class User {

	protected $_db;
	protected $_table = 'users';
	protected $_id;
	protected $_person;
	protected $_loggedIn;
	protected $_salt;

	public function __construct(PDO $db, $username, $password) {
		$this->_db = $db;
		$this->_salt = $this->_getSalt($username);

		// salt will be false if there is no user by that name
		if ($this->_salt) {
			$this->_id = $this->_authenticate($username, $password);
		}

		if ($this->_id) {
			$this->_loggedIn = true;
			$this->_person = new Person($this->_db, $this->_getPersonId());
		} else {
			$this_loggedIn = false;
		}
	}

	public function getLoggedIn() {
		return $this->_loggedIn;
	}

	protected function _authenticate($username, $password) {
		$passwordhash = md5($password . md5($this->_salt));
		$sth = $this->_db->prepare('SELECT id FROM ' . $this->_table . ' WHERE username=:username AND password=:passwordhash');
		$sth->execute(array(':username' => $username, ':passwordhash' => $passwordhash));
		$users = $sth->fetchAll();
		if (count($users) != 1) {
			return false;
		}
		return $users[0]['id'];
	}

	protected function _getSalt($username) {
		$sth = $this->_db->prepare('SELECT salt FROM ' . $this->_table . ' WHERE username=:username');
		$sth->execute(array(':username' => $username));
		$users = $sth->fetchAll();
		if (count($users) != 1) {
			return false;
		}
		return $users[0]['salt'];
	}

	protected function _getPersonId() {
		$sth = $this->_db->prepare('SELECT person_id FROM ' . $this->_table . ' WHERE id=:id');
		$sth->execute(array(':id' => $this->_id));
		$users = $sth->fetchAll();
		if (count($users) != 1) {
			return false;
		}
		return $users[0]['person_id'];
		
	}

	public function getPerson() {
		return $this->_person;
	}
}

