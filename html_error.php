<?php 
include('header.php');
$ms_errors=array();
//-meil start---
$subject = "Visa HTML error"; //Тема
$file_url="files/html_error.txt";
//--mail end---
$pages=file_get_contents('files/pages.txt');
if (isset($_POST['search'])) {
	$pages=trim($_POST['pages']);
	$page_list=explode("\n", $pages);
	if ($pages!='') {
		$page_list=explode("\n", $pages);
	}
	$count_pages=count($page_list);					
	for ($i=0; $i<$count_pages; $i++) { 
		$page_list[$i]=trim($page_list[$i]);
		$visa_url_check='http://validator.w3.org/check?uri='.urlencode($page_list[$i]).'&charset='.urlencode('(detect automatically)').'&doctype=Inline&group=0&user-agent=W3C_Validator%2F1.3+http%3A%2F%2Fvalidator.w3.org%2Fservices';
		//$visa_url_check='http://validator.w3.org/check?uri='.urlencode($page_list[$i]);
		$html_content=file_get_contents($visa_url_check); 					#method get_error
		$html = phpQuery::newDocumentHTML($html_content);
		$error=$html->find('td[class="invalid"]')->text();
		if ($error==null) {
			$error=$html->find('td[class="valid"]')->text();
		}
		$ms_errors[$page_list[$i]]="<a href='$visa_url_check'>$error</a>";
		phpQuery::unloadDocuments();
		sleep(1);
		//exit();
	}
}
?>
<form action="html_error.php" method="post" accept-charset="utf-8">
	<div class='textarea_block'>
		<div class='textarea_title'>Pages</div>
		<textarea name="pages" rows="8" cols="50" placeholder="Введите список страниц через Еnter"><?=$pages?></textarea>
	</div>
	<div style='clear:both'></div>
	<div>
		<input type="submit" name="search" value="Поиск ошибок верстки (pages)">
	</div>
</form>
<table width="100%" border="1" cellspacing="0" cellpadding="4">
	<thead>
		<tr>
			<th>URL</th>
			<th>ERROR</th>
		</tr>
	</thead>
	<tbody>
  <?php 
  		if (count($ms_errors)>0) {
			$f=fopen('files/html_error_copy.txt', 'w+');
			foreach ($ms_errors as $key => $value) {
			    $str=$key.' = '.$value.'<br>';
			    fputs($f,$str);
	?>
			<tr>
				<td><?=$key?></td>
				<td><?=$value?></td>
			</tr>
	<?php
			}
			fclose($f);
			sendMail($to,$from_mail,$from_name,$subject,$message,$boundary,$filename,$file_url); 
		}
		
	?>
	</tbody>
</table>
</body>
</html>