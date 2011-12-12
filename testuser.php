<?php
include('bootstrap.php');

$user = new User($db, $request->getPostVar('username'), $request->getPostVar('password'));


if ($user->getLoggedIn()) {
	$person = $user->getPerson();
	echo "Hello " . $person->getFirstName() . "";
} else {
	$person = null;



?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	username: <input type="text" name="username" /><br />
	password: <input type="password" name="password" /><br />
	<input type="submit" />
</form>
<?php
}
?>
