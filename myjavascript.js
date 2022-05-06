var count = 0;
var maxCount = 1000;
var maxChartData = 40;
var interval = 1000;
var processing = false;
var sName = "3-axis Accelerometer";
//alert(sName);
var sensorsList = [sName]//["3-axis Accelerometer":3];
var timePoint = 0;
var elapsed = 0;
var _Sec = 0;
var _USec = 0;
var ssName = "";
var oldName = "";
var cancel = false;
var chartDataCount=0;

// Chart
var myLineChart;

// Blank Data
var data  = {};

$(document).ready(function() {
	// This will get the first returned node in the jQuery collection.

	var ctx = document.getElementById("mynetwork");
	data = createDataPackage(10);
	myLineChart = new Chart(ctx).Line(data, {
		datasetFill : false, animation : false, pointDot : false, datasetStroke : false, showTooltips: false
	});

	process();
});

function process() {
	if (cancel) return;
	if (processing) {
		setTimeout(process, interval);
		return;
	}
	else {
		
		processing = true;
		ssName = "";
		oldName = "";
	
  	$.ajax({
		   method: "POST",
		   url: "getMongoDB.php",
		   data: { lastSec : _Sec, lastUSec : _USec }
		})
		.done(function( json_data ) {
			if (count > 0) $("#count_data").text(count);
			var msg = jQuery.parseJSON(json_data);
			var content = "";
			var datatxt = "";
			var timetxt = "";
			var header = "";
			$.each( msg, function( i, row ) {
				if (ssName != row["sensorName"] ) {
					ssName = row["sensorName"];
					// Update Sensor list
					var oldListSize = Object.keys(sensorsList).length;
					sensorsList[ssName]= row["value_count"];
					if (oldListSize!= Object.keys(sensorsList).length) 
						$("#sensorSelect").append( new Option(ssName) ); // Update the combo box
					

/*
					
					for(var index in sensorsList) { 
						var attr = sensorsList[index]; 
						$("#sensorSelect").append( new Option(index) );
					}
*/	
					header = "<h4>" + ssName + " (" + sensorsList[ssName] + " values)</h4>";					
				}

				timetxt = row["timeStamp"] + " : ";
				if (_Sec < row["timeSec"]) {
				   _Sec = row["timeSec"];
				   _USec = row["timeUSec"];
				}
				else if (_Sec == row["timeSec"] && _USec < row["timeUSec"]) {
				   _USec = row["timeUSec"];
				} 
				datatxt = parsedata( row["values"], row["value_count"] );
				if (oldName != ssName) {
					oldName = ssName;
					content += header;
				}
				content += "<div>" + timetxt + datatxt + "</div>";
				
				// Add data to chart
				if (ssName == sName) {
					if (chartDataCount > maxChartData) {
					//	alert("Hello! I am an alert box!!");
						myLineChart.removeData();
						chartDataCount--;
					}
					
					if (timePoint == 0) timePoint = _Sec;
					var xText = "";
					if (elapsed != _Sec - timePoint) {
						elapsed = _Sec - timePoint;
						xText = elapsed;
					}
					myLineChart.addData( row["values"], xText );
					chartDataCount++;
				}
			});

			if (count > 0) {
				
				// Update sensor data
				$("#return_data").html(content);
			}
			processing = false;
			count++;
		});
		
		if (count < maxCount) {
			setTimeout(process, interval);
		}
	}
}

function parsedata( sensordata, datacount ) {
	var txt = "[ ";
	for( var i = 0; i < datacount; i++ ) {
	   txt += sensordata[i] + ",  ";
	}
	txt += " ]";
  return txt.replace(",   ]", " ]");
}

function pause() {
	if (!cancel) {
		cancel = true;
	}
	else {
		cancel = false;
		elapsed = 0;
		process();
	}
}



function createDataPackage(numValues){ // number of values
	// Init defaults
	var data_result = {
		labels: [""]
	}
	
	// Init datasets
	var datasets = [] // An array
	for (var i=0; i< numValues; i ++){
		// generate color
		var redCode = getRandomInt(0,255);
		var greenCode = getRandomInt(0,255);
		var blueCode = getRandomInt(0,255);
		// new object
		var obj =  {
            label: "The" + i + "th dataset",
            fillColor: "rgba("+redCode + "," + greenCode + "," + blueCode +",0.2)",
            strokeColor: "rgba("+redCode + "," + greenCode + "," + blueCode +",1)",
            pointColor: "rgba("+redCode + "," + greenCode + "," + blueCode +",1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba("+redCode + "," + greenCode + "," + blueCode +",1)",
            data: [0]
        }
		datasets.push(obj);
	}
	// put all attribute together
	data_result["datasets"]= datasets;
	//alert(JSON.stringify(data_result));
	return data_result;
}

function getRandomInt(min, max) {
  return Math.floor(Math.random() * (max - min)) + min;
}

jQuery.fn.removeAttributes = function() {
  return this.each(function() {
    var attributes = $.map(this.attributes, function(item) {
      return item.name;
    });
    var img = $(this);
    $.each(attributes, function(i, item) {
    img.removeAttr(item);
    });
  });
}


function sensorChange() {
	//alert($("#sensorSelect option:selected").text());
	sName = $("#sensorSelect option:selected").text();
	//alert(sName);
	//alert(sensorsList[sName]);
	//data = createDataPackage(sensorsList[sName]);
	//alert(JSON.stringify(data));
	//myLineChart=null;

	for (var i=0; i<=chartDataCount; i++){
		myLineChart.removeData();
	}
	chartDataCount = 0;
	//processing = false;
	//myLineChart = new Chart(ctx).Line(data, {
	//	datasetFill : false, animation : false, pointDot : false, datasetStroke : false, showTooltips: false
	//})
	//processing = true;
	//myLineChart.update();
	
//	myLineChart.clear();
//	myLineChart.destroy();
//	myLineChart= null;
	;
	
}