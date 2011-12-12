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
class User implements Serializable {
	const USERNAME_MIN_LENGTH = 3;
	protected $_db;
	protected $_table = 'users';
	protected $_id;
	protected $_person;
	protected $_loggedIn;
	protected $_salt;
	protected $_username;
	protected $_passwordhash;
	protected $_error;

	public function __construct(PDO $db, $username, $password) {
		$this->_db = $db;
		$this->_salt = $this->_getSalt($username);

		// salt will be false if there is no user by that name
		if ($this->_salt) {
			$this->_id = $this->_authenticate($username, $password);
		} else {
			$this->_salt = $this->_generateSalt();
		}

		$this->_username = $username;
		$this->_passwordhash = $this->_hashpass($password, $this->_salt);

		if ($this->_id) {
			$this->_loggedIn = true;
			$this->_person = new Person($this->_db, $this->_getPersonId());
		} else {
			$this->_loggedIn = false;
		}
	}

	public function serialize() {
		$data = array(
			 '_id' => $this->_id,
			 '_loggedIn' => $this->_loggedIn,
			 '_salt' => $this->_salt,
			 '_username' => $this->_username,
			 '_passwordhash' => $this->_passwordhash,
			 '_error' => $this->_error,
		);
		return serialize($data);
	}

	public function unserialize($serialized) {
		// a little safety precaution
		if (!isset($this->_db)) {
			return null;
		}
		
		$data = unserialize($serialized);
		$this->_id = $data['_id'];
		$this->_person = new Person($this->_db, $this->_getPersonId());
		$this->_loggedIn = $data['_loggedIn'];
		$this->_salt = $data['_salt'];
		$this->_username = $data['_username'];
		$this->_passwordhash = $data['_passwordhash'];
		$this->_error = $data['_error'];

		return $this;
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

	public function validateUpdate() {
		if (empty($this->_id)) {
			return array(false, 'This user has not been initialized');
		}

		if ($this->_usernameExists($this->_username, $this->_id)) {
			return array(false, 'Username is in use');
		}

		return true;
	}

	public function validateNew() {
		$this->_error = null;
		if (!empty($this->_id)) {
			$this->_error = 'This user already exists';
			return false;
		}

		if ($this->_usernameExists($this->_username)) {
			$this->_error = 'Username is in use';
			return false;
		}

		if (strlen($this->_username) < self::USERNAME_MIN_LENGTH) {
			$this->_error = 'Username does not meet the minimum length of ' . self::USERNAME_MIN_LENGTH;
			return false;
		}

		return true;
	}

	public function getError() {
		return $this->_error;
	}

	public function _usernameExists($username, $id = 0) {
		$sth = $this->_db->prepare('SELECT * FROM ' . $this->_table . ' WHERE username=:username AND id!=:id');
		$sth->execute(array(':username' => $username, ':id' => $id));
		$users = $sth->fetchAll();
		return count($users) > 0;
	}

	public function save() {
		$this->_person->save();
		$query = $this->_table . ' SET username=:username, password=:passwordhash, salt=:salt, person_id=:person_id';
		$params = array(
			 ':username' => $this->_username,
			 ':passwordhash' => $this->_passwordhash,
			 ':salt' => $this->_salt,
			 ':person_id' => $this->_person->getId(),
		);
		if (empty($this->_id)) {
			$query = 'INSERT INTO ' . $query;
		} else {
			$query = 'UPDATE ' . $query . ' WHERE id=:id';
			$params[':id'] = $this->_id;
		}

		$sth = $this->_db->prepare($query);
		$sth->execute($params);

		if (empty($this->_id)) {
			$this->_id = $this->_db->lastInsertId();
		}
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

	public function &getPerson() {
		return $this->_person;
	}

	public function setPerson(&$_person) {
		$this->_person = $_person;
	}

	protected function _hashpass($password, $salt) {
		return md5($password . md5($this->_salt));
	}

	public function getId() {
		return $this->_id;
	}

	protected function _generateSalt() {
		return substr(sha1(rand(332, 1784) * 85), 8, 19);
	}

	public function delete() {
		$query = 'DELETE FROM ' . $this->_table . ' WHERE id=:id';
		$params = array(':id' => $this->_id);
		$sth = $this->_db->prepare($query);
		$sth->execute($params);

		$this->_id = null;
		$this->_username = null;
		$this->_passwordhash = null;
		$this->_loggedIn = false;
	}

	public function getUsername() {
		return $this->_username;
	}

}

