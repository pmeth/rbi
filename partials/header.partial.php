<header>
<?php
if ($user->getLoggedIn()) {
	?>
	Welcome <?php echo $user->getPerson()->getFirstName(); ?> [<a href='logout.php'>Logout</a>]<br />
	<?php
} else {
	?>
	[<a href='login.php'>Login</a>] | [<a href='register.php'>Register</a>]<br />
	<?php
}
?>
</header>