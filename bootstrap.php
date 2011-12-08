<?php

error_reporting(E_ALL);

function classAutoload($class_name) {
	require_once("classes/$class_name.class.php");
}

spl_autoload_register('classAutoload');

$db = new PDO('mysql:dbname=rbi;host=127.0.0.1', 'root', '');

$request = new Request();
