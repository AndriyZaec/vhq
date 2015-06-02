<?php 
$config_files=array(
					'domains' => '../files/domains.txt',
					'pages' => '../files/pages.txt',
					'test_domains' => '../files/test_domains.txt',
					'prefix' => '../files/prefix.txt',
					'noindex_page' => '../files/noindex_page.txt'
					);
    foreach ($config_files as $key => $file_url) {
	$f=fopen($file_url, 'w+');
	fwrite($f, $_POST[$key]);
	fclose($f);
}
?>