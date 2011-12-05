<?php
include('bootstrap.php');

$csv = new parseCSV('../uploads/sample2.txt', null, null, 'Contact First Name = PETER AND Priority = 0001');

foreach($csv->data as $line) {
	print_r($line);
}
