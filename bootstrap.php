<?php

error_reporting(E_ALL);

function classAutoload($class_name) {
	require_once("classes/$class_name.class.php");
}

spl_autoload_register('classAutoload');


$request = new Request();

// Start - You can probably comment out the next few lines of code if you don't intend on having logins
$db = new PDO('mysql:dbname=rbi;host=127.0.0.1', 'root', '');

$user = new User($db, null, null);
$serialized_user = $request->getSessionVar('user');
if ($serialized_user) {
	// note: if i do unserialize($serialized_user) instead, it will be an incomplete class.
	$user->unserialize($serialized_user);
}
// End - You can probably comment out the next few lines of code if you don't intend on having logins

try {
	$myrom = new RBI3AndyBRom("../rbi2008.nes");
} catch (Exception $e) {
	die('An error occurred.  Here are the details: ' . $e->getMessage());
}