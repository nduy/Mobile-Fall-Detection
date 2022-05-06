<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title></title>
		<script src="jquery-1.11.3.min.js"></script>
		<script src="Chart.js"></script>
		<script src="jquery-ui.js"></script>
		<script src="myjavascript_off.js"></script>
	</head>
	<body onclick="pause()">
		<select name="SensorList" id="sensorSelect" onChange="sensorChange()"> 
		</select>
		 <br> 
		<canvas id="myChart" width="800" height="200"></canvas>
		<h1>Sensors</h1>
		<div id="count_data"></div>
		<div id="return_data"></div>
	</body>
</html>
