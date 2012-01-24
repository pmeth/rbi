<?php

error_reporting(E_ALL);

function classAutoload($class_name) {
	require_once("classes/$class_name.class.php");
}

spl_autoload_register('classAutoload');

$db = new PDO('mysql:dbname=rbi;host=127.0.0.1', 'root', '');

$request = new Request();

$user = new User($db, null, null);
$serialized_user = $request->getSessionVar('user');
if ($serialized_user) {
	// note: if i do unserialize($serialized_user) instead, it will be an incomplete class.
	$user->unserialize($serialized_user);
}

try {
	$myrom = new RBI3AndyBRom("../rbi2008.nes");
} catch (Exception $e) {
	die('An error occurred.  Here are the details: ' . $e->getMessage());
}