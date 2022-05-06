
<?php
 $m = new MongoClient("mongodb://127.0.0.1");
   echo "Connection to database successfully".PHP_EOL;
   // select a database
   $db = $m->android_test;
   echo "Database android_test selected".PHP_EOL;
   // Check if the collection for named the current date has already been created
   date_default_timezone_set('Asia/Saigon'); 
   $colName = date('y.m.d.T'); // get current data in Saigon
   $currentCollections =  $db->getCollectionNames(); // Get list all available collections
   if (in_array($colName, $currentCollections)) {
	// Collection exist, so query
		$collection = $db->selectCollection($colName);
		$content = "";
		// get current date
		$current= new MongoDate(time());
//		$resultCursor=$colName-> find(array("indexTime" => array('$gt' => $current-5, '$lte' => $current)));
		$resultCursor=$collection-> find();
		echo json_encode(iterator_to_array($resultCursor));
   }
?>


