<?php
include('header.php');
$countries=file_get_contents('files/countries.txt');
$countries_list=explode("\n", $countries);
$count_countries=count($countries_list);
for ($i=0; $i <$count_domains; $i++) { 
    $countries__list[$i]=trim($countries__list[$i]);
}
$requirements=file_get_contents('files/requirements.txt');
$requirements_list=explode("\n", $requirements);
$count_requirements=count($requirements_list);
for ($i=0; $i <$count_requirements; $i++) { 
    $requirements_list[$i]=trim($requirements_list[$i]);
}
$points=file_get_contents('files/points.txt');
$points_list=explode("\n", $points);
$count_points=count($points_list);
for ($i=0; $i <$count_points; $i++) { 
    $points_list[$i]=trim($points_list[$i]);
}
for ($i=0; $i <$count_points; $i++) {
    $points_list_item=explode("#", $points_list[$i]); 
    $points_country[$i]=$points_list_item[0];
    $points_k[$i]=$points_list_item[1];
    $points_b[$i]=$points_list_item[2];
}
$countries_list_for_js=json_encode($countries_list);
$requirements_for_js=json_encode($requirements_list);
$points_country_for_js=json_encode($points_country);
$points_k_for_js=json_encode($points_k);
$points_b_for_js=json_encode($points_b);

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
      /*#panel {
        position: absolute;
        top: 5px;
        left: 50%;
        margin-left: -180px;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
      }*/
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&language=eg"></script>
    <script>
        var geocoder;
        var map;
        countries_list=<?php echo ($countries_list_for_js);?>;
        requirements_list=<?php echo ($requirements_for_js);?>;
        points_country=<?php echo ($points_country_for_js);?>;
        points_k=<?php echo ($points_k_for_js);?>;
        points_b=<?php echo ($points_b_for_js);?>;
        geocoder = new google.maps.Geocoder();
        function initialize() {
          var zoomSize=3;
          var latlng = new google.maps.LatLng(0,0);
          var mapOptions = {
            zoom: zoomSize,
            center: latlng
          };
          map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
        /*function setMarkers() {
          var marker = new google.maps.Marker({
                  map: map,
                  position: results[0].geometry.location
              });
        }*/
        /*function markCountry(address,requirements) {
          //var address = document.getElementById('address').value;
          //var address="Albania";
          //console.log(address);
          geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              console.log(address);
              console.log(results[0].geometry.location);
              //map.setCenter(results[0].geometry.location);
              var marker = new google.maps.Marker({
                  map: map,
                  position: results[0].geometry.location
              });
            } else {
              alert('Geocode was not successful for the following reason: ' + status);
            }
          });
        }*/
        if (countries_list.length!=requirements_list.length){
          alert("Something wron with requirements!");
        }
        else{
          var points_length=points_country.length;
          for (i=0;i<points_length;i++){
            var latlng = new google.maps.LatLng(points_k[i],points_b[i]);
            if (requirements_list[i]=="not required"){
              var pinColor = "67E667";
            }
            else{
              var pinColor = "FF7373";
            }
            var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor);
            /*var marker = new StyledMarker({
              styleIcon:styleIcon,
              position: latlng,
              map:map
              title: points_country[i]
            });*/
            var marker = new google.maps.Marker({
                map: map,
                position: latlng,
                title: points_country[i],
                icon:pinImage
            });
            console.log(points_country[i]);
            console.log(points_k[i]);
            console.log(points_b[i]);
          }

          /*for (i=228;i<233;i++){
            markCountry(countries_list[i],requirements_list[i]); 
            /*console.log(points_country[i]);
            console.log(points_k[i]);
            console.log(points_b[i]);*/
          //}
        }
      }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
  </head>
  <body>
    <div id="map_canvas"></div>
  </body>
</html>
