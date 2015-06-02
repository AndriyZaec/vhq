function generate_js_border_overlay($output,$border) {
		$color="0099ff";

		$encoded_polygon_desc = "";
		$remove_warnings_layer = "function removeBordersOverlay() { \n";
		$add_warnings_layer = "function addBordersOverlay() { \n";
		$init_borders = "function initBorders() { \n";
		foreach ($border as $row) {
		  $name=$row['name'];
		  $iso2=$row['iso2'];
		  $string=$row['AsText(ogc_geom)'];
		  //echo $string;
		  $region = $row['region'];
		  //preg_match_all("!\(\((.*?)\)\)!",$string,$multi_polygon);
		  $multi_poligon= explode(")),((",$string);
		  $multi_poligon[0]= substr($multi_poligon[0], 15);
		  $count=count($multi_poligon)-1;
		  $multi_poligon[$count]= substr($multi_poligon[$count],0,-3);
		  $encoded_polygon_desc .= "var encodedPolygon_$iso2;\n";
		  $add_warnings_layer .= "map.addOverlay(encodedPolygon_$iso2); \n";
		  $remove_warnings_layer .= "map.removeOverlay(encodedPolygon_$iso2);\n";
		  $init_borders .= "encodedPolygon_$iso2 =
		    new GPolygon.fromEncoded({\n
		    polylines: [";
		  foreach ($multi_poligon as $points ) {
			  $encoded = Help::encode_by_reducing_pointcount($points);
			  $encoded[0]=preg_replace('/\\\\/', '\\\\\\', $encoded[0]);
			//  echo $encoded[0]."<br>";
		      $init_borders .= "{color: \"$color\",
		      weight: 5,
		      points: '";
		      $init_borders .= $encoded[0]."',
		      levels: \"$encoded[1]\",
		      zoomFactor: 2,\n numLevels: 2}";

		      $init_borders .= ",";
		      $init_borders .= "\n";
			}

		  $init_borders .= "],\n fill:true,
		  opacity:0.7,
		  color: \"#$color\"
		  });";
		}

		$add_warnings_layer .= "\n}";
		$remove_warnings_layer .= "\n}";
		$init_borders .= "\n}";
		$write= $encoded_polygon_desc. "\n". $add_warnings_layer. "\n" .$remove_warnings_layer. "\n". $init_borders;
		$this->load->helper('file');
		write_file("./map_js/$output.js", $write);
	}

	function encode_by_reducing_pointcount ($points) {
	  $dlat =0;
	  $plng =0;
	  $plat = 0;
	  $dlng = 0;
	  $i=0;
	    $points=explode(",",$points);
	  foreach ($points as $point) {
		#straight point reduction algorithm: use every 5th point only
		#use all points if their total count is less than 16
		$map_point=explode(" ",$point);
		$point=array("lat"=>$map_point[1],"lng"=>$map_point[0]);
		  if (fmod($i,5) == 0 && count($points) > 16) {
			$late5 = intval($point['lat'] * 1e5);
			$lnge5 = intval($point['lng'] * 1e5);
			$dlat = $late5 - $plat;
			$dlng = $lnge5 - $plng;
			$plat = $late5;
			$plng = $lnge5;
			$res[0] .= Help::encode_signed_number($dlat);
			$res[0] .= Help::encode_signed_number($dlng);
			$res[1] .= Help::encode_number(3);
		  }
		  if( count($points) <= 16) {
			$late5 = intval($point['lat'] * 1e5);
			$lnge5 = intval($point['lng'] * 1e5);
			$dlat = $late5 - $plat;
			$dlng = $lnge5 - $plng;
			$plat = $late5;
			$plng = $lnge5;
			$res[0] .= Help::encode_signed_number($dlat);
			$res[0] .= Help::encode_signed_number($dlng);
			$res[1] .= Help::encode_number(3);
		  }

		$i++;
	  }
	  return $res;
	}

	function encode_signed_number($num) {
	  $sig_num = $num << 1;
	  if ($sig_num < 0) {
	  $sig_num = ~$sig_num;
	  }
	  $res=Help::encode_number($sig_num);
	  return $res;
	}

	function encode_number($num) {
	  $res = "";
	  while ($num  >= 0x20) {
		$res .= chr((0x20 | ($num & 0x1f)) + 63);
		$num >>=5;
	  }
	  $res .= chr($num + 63);
	  return $res;
	}