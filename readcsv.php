<?php

namespace Pmeth\RBI;

include('bootstrap.php');

/*
  // Option 1
  //$csv = new parseCSV('../uploads/sample2.txt', null, null, 'Contact First Name = PETER AND Priority = 0001');

  $csv = new parseCSV('../uploads/sample2.txt');

  foreach($csv->data as $line) {
  print_r($line);
  }
 */

// Option 2
try {
	$csv = new \Pmeth\Common\CSVIterator('../uploads/sample2.txt');

	foreach ($csv as $line) {
		print_r($line);
	}
} catch (\Exception $e) {
	die('A problem occurred opening the file. Message: ' . $e->getMessage());
}
