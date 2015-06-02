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
$countries_list_for_js=json_encode($countries_list);
$requirements_for_js=json_encode($requirements_list);
?>
<!DOCTYPE html>
<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      countries_list=<?php echo ($countries_list_for_js);?>;
      requirements_list=<?php echo ($requirements_for_js);?>;
      google.load("visualization", "1", {packages:["geochart"]});
      google.setOnLoadCallback(drawRegionsMap);
      var countries_length=countries_list.length;
        for (i=0;i<countries_length;i++){
          
        }
      function drawRegionsMap() {

        var data = google.visualization.arrayToDataTable([
          ['Country', 'Requirements'],
          ['Germany', 0],
          ['United States', 1],
          ['Brazil', 1 ],
          ['Canada', 0],
          ['France', 1],
          ['Russia', 0]
        ]);

        var options = {colorAxis: {colors: ['red', 'green']}};

        var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="regions_div" style="height: 100%"></div>
  </body>
</html>