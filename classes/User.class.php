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
	const USERNAME_MIN_LENGTH = 3;
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
		$passwordhash = $this->_hashpass($password, $this->_salt);
		$sth = $this->_db->prepare('SELECT id FROM ' . $this->_table . ' WHERE username=:username AND password=:passwordhash');
		$sth->execute(array(':username' => $username, ':passwordhash' => $passwordhash));
		$users = $sth->fetchAll();
		if (count($users) != 1) {
			return false;
		}
		return $users[0]['id'];
	}

	public function validateNew() {
		if (!empty($this->id)) {
			return array(false, 'This user already exists');
		}

		if ($this->_usernameExists($this->_username)) {
			return array(false, 'Username is in use');
		}

		if (strlen($this->_username) < USERNAME_MIN_LENGTH) {
			return array(false, 'Username does not meet the minimum length of ' . USERNAME_MIN_LENGTH);
		}

		return $this->_person->validateNew();
	}

	public function _usernameExists($username) {
		$sth = $this->_db->prepare('SELECT * FROM ' . $this->_table . ' WHERE username=:username');
		$sth->execute(array(':username' => $username));
		$users = $sth->fetchAll();
		return count($users) > 0;
	}

	public function save() {
		
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

	protected function _hashpass($password, $salt) {
		return md5($password . md5($this->_salt));
	}

	protected function _generatesalt() {
		return substr(sha1(rand(332, 1784) * 85), 8, 19);
	}

}

