<?php 
	$m = new MongoClient("mongodb://127.0.0.1");
	echo "Connection to database successfully".PHP_EOL;
	// select a database
	$db = $m->android_test;
	echo "Database android_test selected".PHP_EOL;
	$collection =  $db->selectCollection('15.05.22.ICT');
	echo "Collection connected".PHP_EOL;
	$resultCursor=$collection-> find();
	$resultCursor->getNext();
	//echo "Reading from: ", $resultCursor->info(), "\n";
	//print_r(array_values($resultCursor->info()))
	echo $resultCursor->count();
?>