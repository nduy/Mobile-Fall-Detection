<?php
   // $sname = $_REQUEST["sensorName"];
   $lastSec = $_REQUEST["lastSec"];
   $lastUSec = $_REQUEST["lastUSec"];
   
   /* $file = fopen("test.txt","w");
   fwrite($file, $obj["time"]);
   fclose($file); */
   
   $m = new MongoClient("mongodb://192.168.24.80");
   // echo "Connection to database successfully".PHP_EOL;
   $db = $m->android_test;
   // echo "Database android_test selected".PHP_EOL;
   date_default_timezone_set('Asia/Saigon'); 
   $colName = date('y.m.d.T'); // get current data in Saigon
   $currentCollections =  $db->getCollectionNames(); // Get list all available collections
   if (in_array($colName, $currentCollections)) {
    	  $collection = $db->selectCollection($colName);
        $current= new MongoDate(time());
        $past = new MongoDate($lastSec, $lastUSec);
//		    $resultCursor=$collection-> find(array("indexTime" => array('$gt' => $past, '$lte' => $current), "sensorName" => $sname));
//        $resultCursor=$collection-> find(array("indexTime" => array('$gt' => $past), "sensorName" => $sname));
        if ($lastSec) {
           $resultCursor = $collection->find(array("indexTime" => array('$gt' => $past)))->sort(array("sensorName" => 1, "indexTime" => 1));
        }
        else {
           $resultCursor = $collection->find()->sort(array("indexTime" => -1))->limit(1);

        }
//		    $resultCursor->sort(array("sensorName" => 1));
        $result = decodeMongoData(iterator_to_array($resultCursor), $lastSec);
		    echo json_encode($result);
   }
?>

<?php

// Turn MongoData into 2-dimentional array
function decodeMongoData( $mongoData, $lastSec ) {
   $data = array();
   $count = 0;
   foreach ($mongoData as $key1 => $value1) {
      $data[$count] = array();
      $data[$count]["id"] = $key1;
      foreach ($value1 as $key2 => $value2) {
         if ($key2 == "action") {
            $data[$count]["action"] = $value2;
         }
         elseif ($key2 == "timeStamp") {
            $data[$count]["timeStamp"] = $value2;
         } 
         elseif ($key2 == "sensorName") {
            $data[$count]["sensorName"] = $value2;
         } 
         elseif ($key2 == "deviceID") {
            $data[$count]["deviceID"] = $value2;
         } 
         elseif ($key2 == "indexTime") {
            $data[$count]["timeSec"] = $value2->sec; 
            $data[$count]["timeUSec"] = $value2->usec; 
         } 
         elseif ($key2 == "values") {
            $valcount = 0;
            $values = array();
            foreach ($value2 as $key3 => $value3) {
               foreach ($value3 as $key4 => $value4) {
				  $values[$valcount] = $value4;
               }
               $valcount++;
            }
            $data[$count]["value_count"] = $valcount;
            $data[$count]["values"] = $values;
         } 
      }
      $count++;
   }
/*
   // No need to sort since the 1st call from js is to get mac timeIndex
      
   if (!$lastSec) {
      // Sort the data for the first query, others are sorted in Collection->find()
      foreach ($data as $key => $row) {
          $sensorName[$key]  = $row['sensorName'];
          $indexTime[$key] = $row['timeSec'] * 1000000 + $row['timeUSec'];
      }
      // Sort the data with sensorName, indexTime ascending
      // Add $data as the last parameter, to sort by the common key
      array_multisort($sensorName, SORT_ASC, $indexTime, SORT_ASC, $data);   
   }
*/   
   return $data; 
}

?>