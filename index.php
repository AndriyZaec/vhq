<?php 
include('header.php');
$url_list=array();
$result=array();
$file_url="files/result.txt";
$subject = "Duplicate title and description from visa"; //Тема
$domains=file_get_contents('files/domains.txt');
$pages=file_get_contents('files/pages.txt');
if (isset($_POST['search'])) {
	$domains=trim($_POST['domains']);
	$pages=trim($_POST['pages']);
	if ($domains=='' and $pages=='') {
		$error_text[]='Вы не ввели никаких данных для поиска';
		$error=1;
	}
	elseif($domains=='' and $pages!=''){
		$page_list=explode("\n", $pages);
		$domain_list=array('visahq.com');
	}
	elseif($domains!='' and $pages==''){
		$error=1;
		$error_text[]='Вы не ввели страници для поиска';
	}
	elseif($domains!='' and $pages!=''){
		$domain_list=explode("\n", $domains);
		if (!in_array('visahq.com', $domain_list)) {
				$domain_list[]='visahq.com';
		}
		$page_list=explode("\n", $pages);
	}
	$count_domains=count($domain_list);
	$count_pages=count($page_list);
	for ($i=0; $i <$count_domains; $i++) { 
		for ($j=0; $j <$count_pages; $j++) { 
			$domen=trim($domain_list[$i]);
			$page=trim($page_list[$j]);
			$url=str_replace('visahq.com', $domen, $page);
			//sleep(2);
			$code=Get_status_code($url);
			// echo $url.'='.$code.'<br>';
			// $ss=get_content('http://www.visahq.ca/');
			// echo $ss;
			// exit();
			if ($code!=200) {
				$error=1;
				$error_text[]='Страница по адресу '.$url.' выдает '.$code.' код';
			}
			else{
				$url_list[]=$url;
			}
		}
	}
	if (count($url_list)>1) {
		$count_url=count($url_list);
		for ($i=0; $i <$count_url; $i++) { 
			//sleep(2);
			$html_content=get_content($url_list[$i]);
			$html = phpQuery::newDocumentHTML($html_content);
			$description=$html->find("meta[name='description']")->attr('content');
			if ($description!='') {
				$result['description'][$url_list[$i]]=$description;
			}
			$title=$html->find("title")->text();
			if ($title!='') {
				$result['title'][$url_list[$i]]=$title;
			}
		}
		if (isset($result['description'])) {
			$duplicates_desc=find_duplicate($result['description']);
		}
		if (isset($result['title'])) {
			$duplicates_title=find_duplicate($result['title']);
		}
		
	}
}

?>	
<form action="" method="post" accept-charset="utf-8">
	<div class='textarea_block'>
		<div class='textarea_title'>Domains</div>
		<textarea name="domains" rows="8" cols="30" placeholder="Введите список доменов через Еnter в формате visahq."><?=$domains?></textarea>
	</div>
	<div class='textarea_block'>
		<div class='textarea_title'>Pages</div>
		<textarea name="pages" rows="8" cols="50" placeholder="Введите список страниц через Еnter"><?=$pages?></textarea>
	</div>
	<div style='clear:both'></div>
	<div>
		<input type="submit" name="search" value="Поиск дублирующих метатегов">
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
			<th>Duplicate description</th>
			<th>Duplicate title</th>
		</tr>
	</thead>
	<tbody>
<?php if (isset($duplicates_desc)  or isset($duplicates_title)) { ?>
		<tr>
			<td>
		<?php
		$f=fopen('files/result.txt', 'w+');
		if(isset($duplicates_desc)){
			fputs($f,'Duplicate description'."<br>");
			foreach ($duplicates_desc as $des_key => $des_value) {
				foreach ($des_value as $key => $value) {
					fputs($f,$value."<br>");
					echo $value.'<br>';
				}
				fputs($f,"=====================================<br>");
				echo '---------------------------------------------------------------<br>';
			} 
		}  
?>
			</td>
			<td>
<?php 
		if(isset($duplicates_title)){
			fputs($f,'Duplicate title'."<br>");
			foreach ($duplicates_title as $title_key => $title_value) {
				foreach ($title_value as $key => $value) {
					fputs($f,$value."<br>");
					echo $value.'<br>';
				}
				fputs($f,"=====================================<br>");
				echo '---------------------------------------------------------------<br>';
			} 
		} 
		fclose($f);
		sendMail($to,$from_mail,$from_name,$subject,$message,$boundary,$filename,$file_url); 
	}
?>
			</td>
		</tr>
	</tbody>
</table>
</body>
</html>