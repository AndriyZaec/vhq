<meta charset="utf-8">
<?php
/**
 * Created by PhpStorm.
 * User: andrij
 * Date: 16.07.15
 * Time: 16:06
 */
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

include_once('include/function.php');
$check_robots_result=array();
$domains=file_get_contents('files/domains.txt');
$robot=file_get_contents('files/robots.txt');
trim($robot);
trim($domains);
$domain_list=array('visahq.com');
$domain_list=explode("\n", $domains);
$robot_list=robots_ms($robot);


$count_domains=count($domain_list);
for ($i=0; $i<$count_domains; $i++) {
    $domain_list[$i]=trim($domain_list[$i]);
    $robots_url="http://www.$domain_list[$i]/robots.txt";
    $get_visa_robots=file_get_contents($robots_url);
    $visa_robots_ms=robots_ms($get_visa_robots);
    // echo '<pre>';
    // print_r($visa_robots_ms);
    // echo '<pre>';
    if (count($robot_list)!=count($visa_robots_ms)) {
        $check_robots_result[$robots_url]='<span style=color:red>Не идентичны</span>';
    }
    else{
        $tmp_res=array_diff($robot_list, $visa_robots_ms);
        if (count($tmp_res)!=0) {
            $check_robots_result[$robots_url]='<span style=color:red>Не идентичны</span>';
        }
        else{
            $check_robots_result[$robots_url]='<span style=color:green>Идентичны</span>';
        }
    }
}
arsort($check_robots_result);
if (count($check_robots_result)>0) {
    //$f=fopen('files/robots_res.txt', 'w+');
    foreach ($check_robots_result as $key => $value) {
        echo $key.' = '.$value.'<br>';
    }
}