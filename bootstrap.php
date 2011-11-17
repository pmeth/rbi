<?php
error_reporting(E_ALL);

function classAutoload($class_name) {
	require_once("classes/$class_name.class.php");
}

spl_autoload_register('classAutoload');

/*
// Your custom class dir
define('CLASS_DIR', 'classes/');

// Add your class dir to include path
set_include_path(get_include_path() . PATH_SEPARATOR . CLASS_DIR);

spl_autoload_extensions(".class.php"); // comma-separated list
spl_autoload_register();
*/

// this was the old way before autoloading.
//include_once('classes/Rom.class.php');
//include_once('classes/RBI3Rom.class.php');
//include_once('classes/RBI3AndyBRom.class.php');
//include_once('classes/Player.class.php');
//include_once('classes/Hitter.class.php');
//include_once('classes/PlayerCollection.class.php');
?>
