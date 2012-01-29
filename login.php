<?php
namespace Pmeth\RBI;
use Pmeth\Common;

include('bootstrap.php');

// this should clear any already set user
$request->unsetSessionVar('user');

$user = new Common\User($db, $request->getPostVar('username'), $request->getPostVar('password'));


if ($user->getLoggedIn()) {
	$request->setSessionVar('user', $user->serialize());
	header('Location: index.php');
} else {
	?>
	<!DOCTYPE html>
	<html>
		<body>
			<menu><a href="index.php">Return home</a></menu>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				username: <input type="text" name="username" /><br />
				password: <input type="password" name="password" /><br />
				<input type="submit" />
			</form>
		</body>
	</html>
	<?php
}
?>
