<?php 
include('header.php');
$prefix_domains=array('visaheadquarters.com','visaheadquarters.co.uk','visaheadquarters.ca');
$url_list=array();
$prefix_domains_list=array();
$result=array();
$file_url="files/result.txt";
$subject = "Duplicate title and description from visa"; //Тема
$prefix=file_get_contents('files/prefix.txt');
$noindex_page=file_get_contents('files/noindex_page.txt');
if (isset($_POST['search'])) {
	$prefix=trim($_POST['prefix']);
	$noindex_page=trim($_POST['noindex_page']);
	if ($prefix=='' and $noindex_page=='') {
		$error_text[]='Вы не ввели никаких данных для поиска';
		$error=1;
	}
	elseif($prefix=='' and $noindex_page!=''){
		$error_text[]='Вы не ввели имена доменов втрого уровня';
		$error=1;
	}
	elseif($prefix!='' and $noindex_page==''){
		$error=1;
		$error_text[]='Вы не ввели страници для поиска';
	}
	elseif($prefix!='' and $noindex_page!=''){
		$prefix_list=trim($prefix_list);
		$prefix_list=explode("\n", $prefix);
		foreach ($prefix_list as $prefix_key => $prefix_value) {
			foreach ($prefix_domains as $dom_key => $dom_value) {
				$prefix_value=trim($prefix_value);
				$prefix_domains_list[]='http://'.$prefix_value.'.'.$dom_value;
			}
		}
		$page_list=explode("\n", $noindex_page);
	}
	$count_domains=count($prefix_domains_list);
	$count_pages=count($page_list);
	for ($i=0; $i <$count_domains; $i++) { 
		for ($j=0; $j <$count_pages; $j++) { 
			$domen=trim($prefix_domains_list[$i]);
			$page=trim($page_list[$j]);
			$url=$domen.$page;
			$code=Get_status_code($url);
			if ($code>=400) {
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
		$text_content='noindex,nofollow';
		for ($i=0; $i <$count_url; $i++) { 
			//<META NAME="robots" CONTENT="noindex, nofollow">
			$html_content=get_content($url_list[$i]);
			$html = phpQuery::newDocumentHTML($html_content);
			$meta_content=$html->find("meta[name='robots']")->attr('content');
			$meta_content=str_replace(' ', '', $meta_content);
			if ($text_content==$meta_content) {
				$meta_content="<span style='color:#00FF00'>$meta_content</span>";
			}
			else{
				if ($meta_content!='') {
					$meta_content="<span style='color:#FF0000'>$meta_content</span>";
				}
				else{
					$meta_content="<span style='color:#FF0000'>meta tag is missing</span>";
				}
				
			}
			$google_index[$url_list[$i]]=GoogleIndex($url_list[$i]);
			$result[$url_list[$i]]=$meta_content;
		}
	}
}

?>	
<form action="" method="post" accept-charset="utf-8">
	<div class='textarea_block'>
		<div class='textarea_title'>Prefix</div>
		<textarea name="prefix" rows="8" cols="30" placeholder="Введите список доменов через Еnter в формате visahq."><?=$prefix?></textarea>
	</div>
	<div class='textarea_block'>
		<div class='textarea_title'>Noindex-page</div>
		<textarea name="noindex_page" rows="8" cols="50" placeholder="Введите список страниц через Еnter начиная со слеша"><?=$noindex_page?></textarea>
	</div>
	<div style='clear:both'></div>
	<div>
		<input type="submit" name="search" value="Поиск проиндексированых страниц">
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
			<th>URL</th>
			<th>NOINDEX</th>
			<th>GOOGLE</th>
		</tr>
	</thead>
	<tbody>
<?php if (isset($result)) { ?>
		
<?php foreach ($result as $key => $value) { ?>
		<tr>
		<td><?=$key?></td>
		<td><?=$value?></td>
		<td><?=$google_index[$key]?></td>
		</tr>
<?php 
		}
		//sendMail($to,$from_mail,$from_name,$subject,$message,$boundary,$filename,$file_url); 
	} 
?>
		
	</tbody>
</table>
</body>
</html>