<?php  
	function strToMongoDate($datString) // Convert datetime to mongodate
	{
		$sec = strtotime($datString);
		$usec = intval(substr($datString,strpos($datString, '.')+1,3));
		return (new MongoDate($sec,$usec*1000));
	}

	//$json=$_GET ['json'];
	$json = file_get_contents('php://input');
	$obj = json_decode($json,true);
	//$obj = array("Volvo", "BMW", "Toyota");
	//fix the date from string into mongodate
	//$time = new MongoDate(strtotime($obj['timeStamp']));
	$objMogoDate = strToMongoDate($obj['timeStamp']);
	$obj['indexTime'] = $objMogoDate;
	//print_r($timeArray) ;
	//$myarr = array_merge($obj,$timeArray);
	//print_r($myarr) ;
   // connect to mongodb
   $m = new MongoClient("mongodb://127.0.0.1");
   //echo "Connection to database successfully".PHP_EOL;
   // select a database
   $db = $m->android_test;
   //echo "Database android_test selected".PHP_EOL;
   // Check if the collection for named the current date has already been created
   date_default_timezone_set('Asia/Saigon'); 
   $colName = date('y.m.d.T'); // get current data in Saigon
   $currentCollections =  $db->getCollectionNames(); // Get list all available collections
   if (!in_array($colName, $currentCollections)) {
     $collection = $db->createCollection($colName);
	}
	// Now select the collection
	$collection = $db->selectCollection($colName);
	// Insert the new record
	$collection->insert($obj);
	//echo "Document inserted successfully".PHP_EOL;
	echo "DataACK"

  ?>