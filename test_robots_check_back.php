<?php 
include('header.php');
$check_robots_result=array();
//-meil start---
$subject = "Visa coincidence test robots"; //Тема
$file_url="files/test_robots_res.txt";
//--mail end---
$domains=file_get_contents('files/test_domains.txt');
$robot=file_get_contents('files/test_robots.txt');
if (isset($_POST['search'])) {
	$domains=trim($_POST['domains']);
	if ($_POST['robot']!='') {
		$robot=trim($_POST['robot']);
	}
	file_put_contents('files/test_robots.txt', $robot);
	if ($domains=='' and $robot=='') {
		$error_text[]='Вы не ввели никаких данных для поиска';
		$error=1;
	}
	elseif($domains!='' and $robot==''){
		$error=1;
		$error_text[]='Вы не создали робот для сравнения';
	}
	elseif($domains=='' and $robot!=''){
		$robot_list=robots_ms($robot);
		$domain_list=array('visahq.com');
	}
	elseif($domains!='' and $robot!=''){
		$domain_list=explode("\n", $domains);
		if (!in_array('visaiq.com', $domain_list)) {
				$domain_list[]='visaiq.com';
		}
		$robot_list=robots_ms($robot);
	}
	$count_domains=count($domain_list);
	for ($i=0; $i<$count_domains; $i++) {
		$domain_list[$i]=trim($domain_list[$i]);
		if (substr_count($domain_list[$i], 'http://')>0) {
			$robots_url="$domain_list[$i]/robots.txt";
		}
		else{
			$robots_url="http://www.$domain_list[$i]/robots.txt";
		}
		$get_visa_robots=get_content($robots_url);
		$visa_robots_ms=robots_ms($get_visa_robots);
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
}
?>
<form action="" method="post" accept-charset="utf-8">
	<div class='textarea_block'>
		<div class='textarea_title'>Test domains</div>
		<textarea name="domains" rows="8" cols="30" placeholder="Введите список доменов через Еnter в формате visahq."><?=$domains?></textarea>
	</div>
	<div class='textarea_block'>
		<div class='textarea_title'>Test robot</div>
		<textarea name="robot" rows="8" cols="50" placeholder="Введите текст робота через Еnter"><?=$robot?></textarea>
	</div>
	<div style='clear:both'></div>
	<div>
		<input type="submit" name="search" value="Проверка test_robots.txt (test domains)">
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
			<th>STATUS</th>
		</tr>
	</thead>
	<tbody>
  <?php 
  		if (count($check_robots_result)>0) {
			$f=fopen('files/test_robots_res.txt', 'w+');
			foreach ($check_robots_result as $key => $value) {
			    $str=$key.' = '.$value.'<br>';
			    fputs($f, $str);
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