<?php
/**
 * Created by PhpStorm.
 * User: andrij
 * Date: 12.06.15
 * Time: 15:30
 */

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once("include/function.php");

//$robot=file_get_contents("files/robots.txt");
$domains=file_get_contents('files/domain_test.txt');
$domain_list=explode("\n", $domains);
//$count_domains=count($domain_list);
//
//
//for($i=0;$i<$count_domains;$i++){
//    $domain_list[$i]=trim($domain_list[$i]);
//    $robots_url="http://www.$domain_list[$i]/robots.txt";
//    $get_visa_robots=file_get_contents($robots_url);
//    $visa_robots_ms[$i]=robots_ms($get_visa_robots);
//    echo $visa_robots_ms[$i];
//    echo '<hr>';
//}
//foreach ($domain_list as $el) {
    $curl = curl_init();
    //$el=trim($el);
    $encUrl = "http://www.visahq.sg/robots.txt";
    $options = array(
        CURLOPT_RETURNTRANSFER => true,	 // return web page
        CURLOPT_HEADER	 => false,	// don't return headers
        CURLOPT_FOLLOWLOCATION => false,	 // follow redirects
        CURLOPT_ENCODING	 => "",	 // handle all encodings
        CURLOPT_USERAGENT	 => 'MJ12bot', // who am i
        CURLOPT_REFERER => 'http://localhost/',
        CURLOPT_AUTOREFERER	=> true,	 // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 5,	 // timeout on connect
        CURLOPT_TIMEOUT	 => 10,	 //timeout on response
        CURLOPT_MAXREDIRS	 => 3,	 //stop after 10 redirects
        CURLOPT_URL	 => $encUrl,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => false,
    );
    curl_setopt_array($curl, $options);
    $content = curl_exec($curl);
    echo $content.'<hr>';
//}

