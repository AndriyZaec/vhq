<?php
include('header.php');
$requirements=file_get_contents('requirements.txt');
$requirements_list=explode("\n", $requirements);
$count_requirements=count($requirements_list);
for ($i=0; $i <$count_requirements; $i++) { 
    $requirements_list[$i]=trim($requirements_list[$i]);
}
$country_poly=file_get_contents('countrys_cods.txt');
$country_poly_list=explode("\n", $country_poly);
$count_country=count($country_poly_list);
for ($i=0; $i <$count_country; $i++) { 
    $country_poly_list[$i]=trim($country_poly_list[$i]);
}
for ($i=0; $i <$count_country; $i++) {
    $country_poly_list_item=explode("#", $country_poly_list[$i]); 
    $country[$i]=$country_poly_list_item[0];
    $polylines[$i]=$country_poly_list_item[1];
}
for ($k=0; $k <$count_country; $k++) {
    $polylines[$k]=trim($polylines[$k]);
    $polylines_list[$k]=explode(" ", $polylines[$k]);
    $polylines_list_length=count($polylines_list[$k]);
}
$countries_for_js=json_encode($country);
$polylines_list_for_js=json_encode($polylines_list);
$requirements_list_for_js=json_encode($requirements_list);
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
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&language=eg&libraries=geometry"></script>
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
    countries_list=<?php echo ($countries_for_js);?>;
    var countries_length=countries_list.length;
    polylines_encode_list=<?php echo ($polylines_list_for_js);?>;
    requirements_list=<?php echo ($requirements_list_for_js);?>;
    var polygon_red_list=[];
    var polygon_green_list=[];
    var polygon_red_index=0;
    var polygon_green_index=0;
    for (i=0;i<countries_length;i++){
      var polylines_count=polylines_encode_list[i].length;
      if (requirements_list[i]=="required"){
        for (j=0;j<polylines_count;j++){
          if (polylines_encode_list[i][j]!="??"){
            polygon_red_list[polygon_red_index]=google.maps.geometry.encoding.decodePath(polylines_encode_list[i][j]);
          polygon_red_index=polygon_red_index+1;
          }
          
        }
      }
      else{
        for (j=0;j<polylines_count;j++){
          if (polylines_encode_list[i][j]!="??"){
            if (polylines_encode_list[i][j]!="??"){
              polygon_green_list[polygon_green_index]=google.maps.geometry.encoding.decodePath(polylines_encode_list[i][j]);
              polygon_green_index=polygon_green_index+1;
            }
          }
        }
      }
    }
    var polygons_red_list= new google.maps.Polygon({
          color: "#FF0000",
          paths: polygon_red_list, 
          strokeColor: '#FF0000',
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: '#FF0000',
          fillOpacity: 0.35
        });
    var polygons_green_list= new google.maps.Polygon({
          color: "#FF0000",
          paths: polygon_green_list, 
          strokeColor: '#008000',
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: '#008000',
          fillOpacity: 0.35
        });
    polygons_red_list.setMap(map);
    polygons_green_list.setMap(map);
  }
  console.log(document.getElementById('map_canvas'));
  google.maps.event.addDomListener(window, 'load', initialize);
  </script>
  </head>
  <body>
    <div id="map_canvas"></div>
  </body>
</html>