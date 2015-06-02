<?php
	include('header.php');
?>
	
	<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Visa requirements on map</title>
    <style>
      html, body {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
      #map_canvas { height: 100% }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&language=eg"></script>
    <script>
	function initialize() {
	var map;
	var zoomSize=3;
        var latlng = new google.maps.LatLng(0,0);
        var mapOptions = {
            zoom: zoomSize,
            center: latlng
        };
    map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
	var polyline1_1 = new Polyline.fromEncoded({
  		color: "#0000ff",
  		weight: 4,
  		opacity: 0.8,
  		points: "mew~Fyt}jAm]_h@~g@i^qIhgA",
  		levels: "PHIP",
  		zoomFactor: 2,
  		numLevels: 18
	});
	map.addOverlay(polyline1_1);
	initBorders();
	addBordersOverlay();
	//encodedPolygon_ES.setFillStyle({color:"blue",opacity:1})');
	}
	google.maps.event.addDomListener(window, 'load', initialize);
	</script>
  </head>
  <body>
    <div id="map_canvas"></div>
  </body>
</html>