<!DOCTYPE html>
<html>
	<body>
		<menu><a href="index.php">Return home</a></menu>
		<?php
		include('bootstrap.php');

		$user = new User($db, $request->getPostVar('username'), $request->getPostVar('password'));

		if ($user->getLoggedIn()) {
			$person = $user->getPerson();

			echo "Hello " . $person->getFirstName() . "<br />";
			if ($user->validateUpdate() === true) {
				echo "Valid for update;";
			} else {
				echo "Not valid for update;";
			}
			echo "<br />";
			if ($user->validateNew() === true) {
				echo "Valid for new;";
			} else {
				echo "Not valid for new;";
			}
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
	</body>
</html>
