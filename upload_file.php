<?php
namespace Pmeth\RBI;
include('bootstrap.php');
try {
	if ($_POST['send']) {
		$fileUploader = new FileUploader();
		if ($fileUploader->upload()) {
			echo 'Target file uploaded successfully!  Click <a href="index.php">here</a> to return to the main menu.';
		}
		
	}
} catch (Exception $e) {
	echo $e->getMessage();
	exit();
}

