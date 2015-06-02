<?php 
$file_dom='../files/domains.txt';
$file_pag='../files/pages.txt';
if ($_POST['flag']=='save') {
	$f=fopen($file_dom, 'w+');
	fwrite($f, $_POST['domains']);
	fclose($f);
	$f=fopen($file_pag, 'w+');
	fwrite($f, $_POST['pages']);
	fclose($f);
}
if ($_POST['flag']=='load') {
	$urls=file_get_contents($file);
	echo $urls;
}
?>