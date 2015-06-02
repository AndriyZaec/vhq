<?php 
$domains=file_get_contents('files/domains.txt');
$test_domains=file_get_contents('files/test_domains.txt');
$pages=file_get_contents('files/pages.txt');
function send_post($url,$domains,$pages){
	$curlObj = curl_init();
	$encUrl = $url;
	$username = "admin";
	$password = 'redirect';
	curl_setopt($curlObj, CURLOPT_URL, $encUrl);
	curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curlObj, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($curlObj, CURLOPT_POSTFIELDS, "search=sark&domains=$domains&pages=$pages");
	curl_setopt($curlObj, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	$output = curl_exec($curlObj);
	echo $output;
	$transferInfo = curl_getinfo($curlObj);
	curl_close($curlObj);
}
$url='http://lisikua.biz/visahq/visa_meta_check/index.php';
send_post($url,$domains,$pages);

$url='http://lisikua.biz/visahq/visa_meta_check/robots_check.php';
send_post($url,$domains,$pages);

$url='http://lisikua.biz/visahq/visa_meta_check/test_robots_check.php';
send_post($url,$test_domains,$pages);

$url='http://lisikua.biz/visahq/visa_meta_check/html_error.php';
send_post($url,$domains,$pages);
exit();
?>
