<!DOCTYPE html>
<html>
	<body>
		<?php
		include('partials/menu.partial.php');
		include('bootstrap.php');

		if (
				  $request->getPostVar('first_name') != '' &&
				  $request->getPostVar('last_name') != '' &&
				  $request->getPostVar('email') != '' &&
				  $request->getPostVar('username') != '' &&
				  $request->getPostVar('password') != ''
		) {
			$newperson = new Person($db, null);
			$newperson->setFirstName($request->getPostVar('first_name'));
			$newperson->setLastName($request->getPostVar('last_name'));
			$newperson->setEmail($request->getPostVar('email'));

			// we only want to continue if the user can be safely added
			if ($newperson->validateNew()) {
				$newperson->save();
				$newuser = new User($db, $request->getPostVar('username'), $request->getPostVar('password'));
				$newuser->setPerson($newperson);
				if ($newuser->validateNew()) {
					$newuser->save();
					header('Location: index.php');
					exit;
				} else {
					echo "There was a problem adding the user. Message: " . $newuser->getError() . "<br />";
					// if user failed, we also should remove the Person to avoid making them an orphan.  what would the pro-lifers say??
					$newperson->delete();
					echo "Person deleted<br />";
				}
			} else {
				echo "There was a problem adding the person. Message: " . $newperson->getError() . "<br />";
			}
		}
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			First: <input type="text"  name="first_name" /><br />
			Last: <input type="text" name="last_name" /><br />
			Email: <input type="text" name="email" /><br />
			username: <input type="text" name="username" /><br />
			password: <input type="password" name="password" /><br />
			<input type="submit" name="submit" value="Add User" />
		</form>
	</body>
</html>
