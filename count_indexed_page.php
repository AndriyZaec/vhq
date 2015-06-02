<?php 
include('header.php');
$prefix_domains=array('visaheadquarters.com','visaheadquarters.co.uk','visaheadquarters.ca');
$url_list=array();
$google_indexed=array();
$prefix_domains_list=array();
$result=array();
$file_url="files/result.txt";
//$subject = "Duplicate title and description from visa"; //Тема
$prefix=file_get_contents('files/prefix.txt');
//$noindex_page=file_get_contents('files/noindex_page.txt');
if (isset($_POST['search'])) {
	$url=trim($_POST['url']);
	if ($url=='') {
		$error_text[]='Вы не ввели никаких данных для поиска';
		$error=1;
	}
	else{
		foreach ($prefix_list as $prefix_key => $prefix_value) {
			foreach ($prefix_domains as $dom_key => $dom_value) {
				
				$prefix_domains_list[]='http://'.$prefix_value.'.'.$dom_value;
			}
		}
		$url_ms=explode("\n", $url);
		foreach ($url_ms as $url_key => $url_value) {
			foreach ($prefix_domains as $dom_key => $dom_value) {
				$url_value=trim($url_value);
				$prefix_domains_list[]='http://'.$url_value.'.'.$dom_value;
			}
		}
	}

	foreach ($prefix_domains_list as $url_key => $url_value) {
		$code=Get_status_code($url_value);
		if ($code>=400) {
			$error=1;
			$error_text[]='Страница по адресу '.$url_value.' выдает '.$code.' код';
		}
		else{
			$url_list[]=$url_value;
		}
	}
	if (count($url_list)>0) {
		$count_url=count($url_list);
		for ($i=0; $i <$count_url; $i++) { 
			$CountIndexed=GoogleCountIndexed($url_list[$i]);
			if ($CountIndexed!='0') {
				$google_indexed[$url_list[$i]]=$CountIndexed;
			}
			
		}
	}
}

?>	
<form action="" method="post" accept-charset="utf-8">
	<div class='textarea_block'>
		<div class='textarea_title'>Indexed domeid</div>
		<textarea name="url" rows="8" cols="36" placeholder="Введите список доменов через Еnter"><?=$prefix?></textarea>
	</div>
	<div style='clear:both'></div>
	<div>
		<input type="submit" name="search" value="Поиск количества проиндексированых страниц">
	</div>
</form>
<div>
	<?php
	if ($error==1) {
		foreach ($error_text as $key => $value) {
			echo $value.'<br>';
		}
	}
	?>
</div>
<table width="100%" border="1" cellspacing="0" cellpadding="4">
	<thead>
		<tr>
			<th>URL DOMAIN</th>
			<th>COUNT INDEXED PADE</th>
		</tr>
	</thead>
	<tbody>	
<?php foreach ($google_indexed as $key => $value) { ?>
		<tr>
		<td><?=$key?></td>
		<td><?=$value?></td>
		</tr>
<?php 
		}
		//sendMail($to,$from_mail,$from_name,$subject,$message,$boundary,$filename,$file_url); 
?>
		
	</tbody>
</table>
</body>
</html>