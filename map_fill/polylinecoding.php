<?php
ini_set('memory_limit','512M');
if (!empty($_POST["string_to_write"])){
    $test=fwrite($fp, $string_to_write[$i]);
    fclose($fp); 
}
else{
function string_to_dec($temp_number,$string,$string_size){
    $degree=1;
        for ($j=$string_size-1;$j>=0;$j--){
            if ($string[$j]=='1'){
                $temp_number=$temp_number+$degree;
            }
            $degree=$degree*2;
        }
    return $temp_number;
}
include('header.php');
$country_poly=file_get_contents('polyline_points.txt');
$country_poly_list=explode("\n", $country_poly);
$count_country=count($country_poly_list);
for ($i=0; $i <$count_country; $i++) { 
    $country_poly_list[$i]=trim($country_poly_list[$i]);
}
for ($i=0; $i <$count_country; $i++) {
    //echo ($country_poly_list[$i]);
    $country_poly_list_item=explode("#", $country_poly_list[$i]); 
    $country[$i]=$country_poly_list_item[0];
    $polylines[$i]=$country_poly_list_item[1];
}
//$polyline="19.2325632994,43.5155181096,0 19.5105541482,43.6858269832,0 19.239454062,44.010609136,0 19.6185630175,44.0526180614,0 19.1044452385,44.3558270393,0 19.3713912534,44.8891641871,0 19.0397181703,44.8613820337,0 19.1673542718,45.2143002239,0 19.4250000622,45.2179181886,0 18.9812631127,45.3822180758,0 19.0979271353,45.5188642203,0 18.9022361824,45.5731362053,0 18.956663065,45.7825002216,0 18.8170180431,45.9129642096,0 19.5652730563,46.1727730339,0 20.2610271805,46.1148540828,0 20.80160009,45.758673153,0 20.8083270703,45.4788822068,0 21.4827730555,45.1836090471,0 21.3734361574,45.0136091304,0 21.5539541891,44.892427262,0 21.3986631715,44.7831002546,0 22.1375001105,44.4802731561,0 22.466109105,44.7137450348,0 22.7625091278,44.5526000959,0 22.4615272215,44.4833270187,0 22.6814361749,44.2247000428,0 22.3672182376,43.8269450407,0 22.5416632452,43.4756180711,0 23.0050002695,43.1927730946,0 22.7415910825,42.8921450722,0 22.4429091705,42.8204090566,0 22.5584733197,42.4833271314,0 22.3652731332,42.3238822102,0 21.110836327,42.2006911996,0 20.5896452275,41.8821911156,0 20.5251360888,42.2130540039,0 20.0728608298,42.5598031133,0 20.0102527067,42.7449628754,0 20.3762746615,42.8505460219,0 20.0067334809,43.066991573,0 19.6248739201,43.2060094608,0 19.2746897391,43.4189356183,0 19.2325632994,43.5155181096,0";
//$country="Serbia";
//$fp=fopen('countrys_cods.txt','w');
for ($k=0; $k <100; $k++) {
//for ($k=0; $k <$count_country; $k++) {
    $polylines[$k]=trim($polylines[$k]);
    $polylines_list=explode("@", $polylines[$k]);
    $polylines_list_length=count($polylines_list);
    $country_polylines_count[$k]=$polylines_list_length;
    $cords_list=array();
    for ($j=0;$j<$polylines_list_length;$j++){
        $polylines_list[$j]=trim($polylines_list[$j]);
        $points_list=explode(" ", $polylines_list[$j]);
        $points_list_length=count($points_list);
        for ($i=0; $i<$points_list_length; $i++) { 
            $points_list[$i]=trim($points_list[$i]);
        }
        $points_list_length=count($points_list);
        //$cords_list_length=count($cords_list);
        for ($i=0; $i<$points_list_length; $i++) {
            $points_list_item=explode(",", $points_list[$i]); 
            $cords_list[$j][$i*2]=$points_list_item[0];
            $cords_list[$j][$i*2+1]=$points_list_item[1];
            //$cords_list[$j][$cords_list_length+$i*2]=$points_list_item[0];
            //$cords_list[$j][$cords_list_length+$i*2+1]=$points_list_item[1];
        }
    //$country_last_point[$k][$j]=count($cords_list[$j]);
    /*for ($i=0; $i <$points_list_length; $i++) {
        $points_list_item=explode(",", $points_list[$i]); 
        $cords_list[$i*2]=$points_list_item[0];
        $cords_list[$i*2+1]=$points_list_item[1];
        //$null_list[$i]=$points_list_item[2];
        //echo ($points_list_item[0].'   ');
        //echo ($points_list_item[1].'   ');
        //echo ($points_list_item[2].'   ');
    }
    //$cords_list=explode(",",$points_list);
    //$points_list=str_word_count($polyline,1);
    /*$cords_list_length=count($cords_list);
    $polyline_in_code='';
    for ($i=0;$i<$cords_list_length;$i++){
    //for ($i=0;$i<1;$i++){
        if ($cords_list[$i]<0){
            $sign="true";
            $cords_list[$i]=abs($cords_list[$i]);
        }
        else{
            $sign="false";
        }
        $cords_list[$i]=round($cords_list[$i]*100000);
        $cords_list[$i]=decbin($cords_list[$i]);
        //$n = 5; 
        //$n=printf('%04b', $n); 
        //echo($n."   ");
        //echo($cords_list[$i]."   ");
        //echo($sign."  ");

        //-----------------------------------------------------------
        // Перевод в дополнительный код
        //-----------------------------------------------------------

        if($sign=="true"){
            $string=pack("a*",$cords_list[$i]);
            //$string=(string)$cords_list[$i];
            $string_size=strlen($string);
            if ($string_size<32){
                for ($j=$string_size;$j<32;$j++){
                   $string='0'.$string; 
                }
            }
            $string_size=strlen($string);
            for ($j=0;$j<$string_size;$j++){
                if($string[$j]=='1'){
                    $string[$j]='0';
                }
                else{
                    $string[$j]='1';
                }
            } 
            $temp_number=0;
            $temp_number=string_to_dec($temp_number,$string,$string_size);
            $temp_number=$temp_number+1;
            $cords_list[$i]=decbin($temp_number);
            //echo ($cords_list[$i]."  ");
            //$temp_array=unpack("c2/n",$string);
            /*$cords_list[$i]="";
            for ($j=0;$j<count($temp_array);$j++){
                $cords_list[$i]=$cords_list[$i].$temp_array[$j];
            }*/
            //echo($cords_list[$i]."  ");
            //pack("a",$cords_list[$i]);
            //$cords_list[$i]=(int)$string;
            //$cords_list[$i]=$cords_list[$i]+1; 
            //$cords_list[$i]=decbin($cords_list[$i]);
            //echo($cords_list[$i]."   ");


       // }
        //echo ($cords_list[$i]." ");
        //------------------------------------------------------------
        // Подготовка и получение пятиразрядных последовательностей
        //------------------------------------------------------------

        //$cords_list[$i]<<1;
       /* $temp_string=(string)$cords_list[$i];
        //echo ($string."  ");
        $string_size=strlen($temp_string);
        if ($string_size<32){
            for ($j=$string_size;$j<32;$j++){
            $temp_string='0'.$temp_string; 
            }
        }
        $string=substr($temp_string,1).'0';
        $string_size=strlen($string);
        /*if ($string_size<32){
            for ($j=$string_size;$j<32;$j++){
                $string='0'.$string; 
            }
        }*/
        /*if($sign=="true"){
            for ($j=0;$j<$string_size;$j++){
                if($string[$j]=='1'){
                    $string[$j]='0';
                }
                else{
                    $string[$j]='1';
                }
            } 
        }
        $temp_index=0;
        //echo($string." ");
        $cords_list[$i]="";
        $one_more_segment='false';
        //for($j=ceil($string_size/5);$j>=0;$j--){
        for($j=1;$j<=ceil($string_size/5);$j++){
            if ($temp_index<6){
                $substrings[$temp_index]=substr($string,(-$j*5),5);
                //echo($substrings[$temp_index]."   ");
                $temp_index=$temp_index+1;
            }
            //echo($string_size%5);
            //if ($string_size%5!=0){
        }
        $string_size=$temp_index;
        for($j=0;$j<($string_size-1);$j++){
            //echo($one_more_segment."  ");
            if ($j!=($string_size-1)){
                //$temp=$substrings[$j];
                $substrings[$j]='1'.$substrings[$j];
                //echo($temp."  ");
                /*$temp_number=0;
                $temp_number=string_to_dec($temp_number,$temp,strlen($temp));
                echo($temp_number."  ");
                $substrings[$j]=$temp_number;
                //$substrings[$temp_index]=(int)$temp;
                //echo($substrings[$temp_index]."   ");
                $substrings[$j]=dechex($substrings[$j]);
                echo($substrings[$j]."   ");
                $substrings[$j]=$substrings[$j]+0x20;
                echo($substrings[$j]."   ");
                $substrings[$j]=decbin(hexdec($substrings[$j]));
                $substrings[$j]=(string)$substrings[$j];
                echo($substrings[$j]."   ");*/
         /*   }
            else{
                $substrings[$j]='0'.$substrings[$j];  
            }
            //echo($substrings[$temp_index]."   ");
            //echo($substrings[$j]."   ");
            $temp_number=0;
            //$temp_number=string_to_dec($temp_number,$temp,strlen($temp));
            $temp_number=string_to_dec($temp_number,$substrings[$j],strlen($substrings[$j]));
            $substrings[$j]=$temp_number;
            //$substrings[$temp_index]=(int)$substrings[$temp_index];
            //$substrings[$temp_index]=bindec($substrings[$temp_index]);
            $substrings[$j]=$substrings[$j]+63;
            $substrings[$j]=chr($substrings[$j]);
            //echo($substrings[$j]."   ");
            $cords_list[$i]=$cords_list[$i].$substrings[$j];
            //$temp_index=$temp_index+1;
        }
        //echo ($cords_list[$i]."  ");
        $polyline_in_code=$polyline_in_code.$cords_list[$i];
        //echo ($cords_list[$i]."  ");
        //$cords_list[$i]=$cords_list[$i]*100000;
       //echo ($cords_list[$i].'   ');
    }
    //echo ($polyline_in_code);
    $string_to_write=$country[$k]." # ".$polyline_in_code;
    echo($polyline_in_code);
    //echo($string_to_write);
    //$test = fwrite($fp, $country);
    //$test = fwrite($fp, $polyline_in_code);
    $test=fwrite($fp, $string_to_write);
    echo ($test);
    /*if ($test) echo ' good!';
    else echo '   fail';*/
    }
    $polylines_encode[$k]=$cords_list;
    //var_dump($polylines_encode[$k]);
    /*for($b=0;$b<10;$b++){
        for ($a=0;$a<count($polylines_encode[$k]);$a++){
            for ($q=0;$q<count($polylines_encode[$k][$a]);$q++){
                echo($polylines_encode[$b][$a][$q]);
            }
            echo("blabla");
        }
        echo("ulala");
    }  */ 
}
//var_dump($polylines_encode);
$countries_for_js=json_encode($country);
//$country_polylines_count
$polylines_encode_for_js=json_encode($polylines_encode);
//$cords_list_for_js=json_encode($cords_list);
//$country_last_point_list_for_js=json_encode($country_last_point);
//fclose($fp); //Закрытие файла*/
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Borders on file</title>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&language=eg&libraries=geometry"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>  
    <script>
        countries_list=<?php echo ($countries_for_js);?>;
        //cords_list=<?php echo ($cords_list_for_js);?>;
        polylines_encode_list=<?php echo ($polylines_encode_for_js);?>;
        console.log(polylines_encode_list);
        //country_last_point=<?php echo ($country_last_point_list_for_js);?>;
        //console.log(country_last_point);
        var countries_list_length=countries_list.length;        
        var string_to_write=[];
        for (i=0;i<100;i++){
        //for (i=0;i<countries_list_length;i++){
            //console.log(polylines_encode_list[i]);
            var country_polylines_count=polylines_encode_list[i].length;
            string_to_write[i]="";
            for (j=0;j<country_polylines_count;j++){
                var points=new Array();
                /*if (i==0){
                    first_index=0;
                }
                else{
                    first_index=country_last_point[i][j-1]/2;
                }*/
                var q=0;
                /*console.log(first_index);
                console.log(country_last_point[i][j]/2);*/
                //for (k=first_index;k<(country_last_point[i][j]/2);k++){
                var polyline_last_point=polylines_encode_list[i][j].length;
                for (k=0;k<(polyline_last_point/2);k++){
                //for (k=0;k<(country_last_point[i][j]/2);k++){
                    polylines_encode_list[i][j][2*k]=parseFloat(polylines_encode_list[i][j][2*k]);
                    //console.log(polylines_encode_list[i][j][2*k]);
                    polylines_encode_list[i][j][2*k+1]=parseFloat(polylines_encode_list[i][j][2*k+1]);
                    //console.log(polylines_encode_list[i][j][2*k+1]);
                    points[q]= new google.maps.LatLng(polylines_encode_list[i][j][2*k+1],polylines_encode_list[i][j][2*k]);
                    q=q+1;
                }
                var cript_polyline=google.maps.geometry.encoding.encodePath(points);
                if (string_to_write[i]==""){
                    string_to_write[i]=countries_list[i]+" # "+cript_polyline;
                }
                else{
                    string_to_write[i]=string_to_write[i]+" "+cript_polyline;
                }
                cript_polyline=google.maps.geometry.encoding.decodePath(cript_polyline);
                //console.log(cript_polyline);
            }
            console.log(string_to_write[i]);
        }
        $.ajax({ // отправляем данные
            url: 'polylinecoding.php',
            type: 'post',
            data: {string_to_write: string_to_write}
        });
    </script>
  </head>
  <body>
  </body>
</html>