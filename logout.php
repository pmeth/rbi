<?php
namespace Pmeth\RBI;
include('bootstrap.php');

// this should clear any already set user
$request->unsetSessionVar('user');
header('Location: index.php');
