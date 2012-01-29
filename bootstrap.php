<?php
namespace Pmeth\RBI;
error_reporting(E_ALL);

use Respect\Validation\Validator as v;
use Pmeth\Common;

//function classAutoload($class_name) {
//	require_once("classes/$class_name.class.php");
//}
//
//spl_autoload_register('classAutoload');

set_include_path('library' . PATH_SEPARATOR . get_include_path());
require('SplClassLoader.php');

$classLoader = new \SplClassLoader();
$classLoader->setIncludePathLookup(true);
$classLoader->setMode(\SplClassLoader::MODE_SILENT);
$classLoader->register();

$request = new Common\Request();

// Start - You can probably comment out the next few lines of code if you don't intend on having logins
$db = new \PDO('mysql:dbname=rbi;host=127.0.0.1', 'root', '');

$user = new Common\User($db, null, null);
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