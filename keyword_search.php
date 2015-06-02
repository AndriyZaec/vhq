<?php 
include('header.php');
$prefix_domains=array('visaheadquarters.com','visaheadquarters.co.uk','visaheadquarters.ca');
$url_list=array();
$google_indexed=array();
$prefix_domains_list=array();
$result=array();
$file_url="files/result.txt";
//$subject = "Duplicate title and description from visa"; //Тема
//$prefix=file_get_contents('files/prefix.txt');
//$noindex_page=file_get_contents('files/noindex_page.txt');
if (isset($_POST['search'])) {
	$keyword=trim($_POST['keyword']);
	$search_site=trim($_POST['search_site']);
	if ($keyword=='' and $search_site=='') {
		$error_text[]='Вы не ввели никаких данных для поиска';
		$error=1;
	}
	elseif($keyword!='' and $search_site==''){
		$error=1;
		$error_text[]='Вы не ввели сайт для поиска';
	}
	elseif($keyword!='' and $search_site!=''){
		$keyword_ms=explode("\n", $keyword);
	}
	if (count($keyword_ms)>0) {
		foreach ($keyword_ms as $keyword_key => $keyword_value) {
			$value=trim($keyword_value);
			$result[$value]=GoogleSearch($value,$search_site);
		}
	}
}

?>	
<form action="" method="post" accept-charset="utf-8">
	<div class='textarea_block'>
		<div class='textarea_title'>Indexed domeid</div>
		<textarea name="keyword" rows="8" cols="36" placeholder="Введите список ключевых слов для поиска через Еnter">china visa</textarea>
	</div>
	<div style='clear:both'></div>
	<div><input type="text" name="search_site" value="visahq.com" placeholder="Искомый сайт"></div>
	<div>
		<input type="submit" name="search" value="Определить позицию сайта">
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
			<th>KEYWORD</th>
			<th>URL</th>
			<th>POSITION</th>
		</tr>
	</thead>
	<tbody>	
<?php foreach ($result as $result_key => $result_value) { 
		foreach ($result_value as $key => $value) {?>
		<tr>
		<td><?=$result_key?></td>
		<td><?=$value?></td>
		<td><?=$key?></td>
		</tr>
<?php 
		}
	}
		//sendMail($to,$from_mail,$from_name,$subject,$message,$boundary,$filename,$file_url); 
?>
		
	</tbody>
</table>
</body>
</html>