<?php 
function get_content($url,$advanced_options=array()){
	$ch = curl_init();
	$encUrl = $url;
	$options = array(
	CURLOPT_RETURNTRANSFER => true,	 // return web page
	CURLOPT_HEADER	 => false,	// don't return headers
	CURLOPT_FOLLOWLOCATION => false,	 // follow redirects
	CURLOPT_ENCODING	 => "",	 // handle all encodings
	CURLOPT_USERAGENT	 => 'MJ12bot', // who am i
	CURLOPT_REFERER => 'http://localhost/',
	CURLOPT_AUTOREFERER	=> true,	 // set referer on redirect
	CURLOPT_CONNECTTIMEOUT => 5,	 // timeout on connect
	CURLOPT_TIMEOUT	 => 10,	 //timeout on response
	CURLOPT_MAXREDIRS	 => 3,	 //stop after 10 redirects
	CURLOPT_URL	 => $encUrl,
	CURLOPT_SSL_VERIFYHOST => 0,
	CURLOPT_SSL_VERIFYPEER => false,
	);
	$options=$advanced_options+$options;
	curl_setopt_array($ch, $options);
	$content = curl_exec($ch);
	return $content;
}
function get_error($url,$advanced_options=array()){
	$ch = curl_init();
	$encUrl = $url;
	$header=array(
			'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
			'Accept-Encoding:gzip,deflate,sdch',
			'Accept-Language:ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
			'Cache-Control:max-age=0',
			'Connection:keep-alive',
			'Host:validator.w3.org'
		);
	$options = array(
	CURLOPT_RETURNTRANSFER => true,	 // return web page
	CURLOPT_HEADER	 => false,	// don't return headers
	CURLOPT_FOLLOWLOCATION => false,	 // follow redirects
	CURLOPT_HTTPHEADER => $header,
	CURLOPT_ENCODING	 => "",	 // handle all encodings
	CURLOPT_USERAGENT	 => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36', // who am i
	CURLOPT_AUTOREFERER	=> true,	 // set referer on redirect
	CURLOPT_CONNECTTIMEOUT => 5,	 // timeout on connect
	CURLOPT_TIMEOUT	 => 10,	 //timeout on response
	CURLOPT_MAXREDIRS	 => 3,	 //stop after 10 redirects
	CURLOPT_URL	 => $encUrl,
	CURLOPT_SSL_VERIFYHOST => 0,
	CURLOPT_SSL_VERIFYPEER => false,
	);
	$options=$advanced_options+$options;
	curl_setopt_array($ch, $options);
	$content = curl_exec($ch);
	return $content;
}
function Get_status_code($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_USERAGENT, 'MJ12bot');
	$html = curl_exec($ch);
	$status_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
	return $status_code;
}

function find_duplicate($ms){
	$res=array();
	$tmp_ms=array_count_values($ms);
	foreach ($tmp_ms as $key => $value) {
		if ($value>1) {
			$res[]=array_keys($ms,$key);
		}
	}
	return $res;
}
function robots_ms($robots){
	$ms=array_map(function($val){ 
						if ($val!='') {
							$val=trim($val);
							return($val); 
						}
						
					}, explode("\n", $robots));
	$ms=array_diff($ms,array(''));
	sort($ms);
	return $ms;
}



function sendMail($to,$from_mail,$from_name,$subject,$message,$boundary,$filename,$file_url) {
	  /* Заголовки */
	  $file = fopen($file_url, "r"); //Открываем файл
	  $text = fread($file, filesize($file_url)); //Считываем весь файл
	  fclose($file); //Закрываем файл
	  $message= $text;
	  $headers = "From: $from_name <$from_mail>\n"; 
	  $headers .= "Reply-To: $to\n";
	  $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"";
	  $body = "--$boundary\n";
	  /* Присоединяем текстовое сообщение */
	  $body .= "Content-type: text/html; charset=utf-8 \n";
	  $body .= "Content-Transfer-Encoding: quoted-printablenn";
	  $body .= "Content-Disposition: attachment; filename==?utf-8?B?".base64_encode($filename)."?=\n\n";
	  $body .= $message." \n";
	  $body .= "--$boundary\n";
	  
	  /* Добавляем тип содержимого, кодируем текст файла и добавляем в тело письма */
	   $body .= "Content-Type: application/octet-stream; name==?utf-8?B?".base64_encode($filename)."?=\n"; 
	   $body .= "Content-Transfer-Encoding: base64\n";
	   $body .= "Content-Disposition: attachment; filename==?utf-8?B?".base64_encode($filename)."?=\n\n";
	   $body .= chunk_split(base64_encode($text))."\n";
	  $body .= "--".$boundary ."--\n";
	  mail($to, $subject, $body, $headers); //Отправляем письмо
}



function GoogleIndex($url) {  
	$content = file_get_contents('http://ajax.googleapis.com/ajax/services/search/web?v=1.0&filter=0&q=site:' .urlencode($url)); 
	$data = json_decode($content,true); 
	if (isset($data['responseData']['cursor']['estimatedResultCount'])) {
		$res='<span style="color:#00FF00">indexed</span>';
	}
	else{
		$res='<span style="color:#FF0000">is not indexed</span>';
	}
	return $res; 
}

function GoogleCountIndexed($url) {
	$url=urlencode($url);
	$google_search_url='https://www.google.com/uds/GwebSearch?callback=google.search.WebSearch.RawCompletion&rsz=large&hl=ru&source=gsc&gss=.com&sig=a075bd7791aa5f99550ea5c9db01a0d5&q=site:'.$url.'&gl=www.google.com&qid=1425b41678c33971d&context=1&key=notsupplied&v=1.0&nocache=1384510482257';  
	//$content = file_get_contents('http://ajax.googleapis.com/ajax/services/search/web?v=1.0&filter=0&q=site:' .urlencode($url)); 
	$content = get_content($google_search_url);
	//$data = json_decode($content,true);
	preg_match('/\{(.*)\}/', $content,$res);
	$data=json_decode($res[0],true);
	if (isset($data['results']) and count($data['results'])>0) {
		$res='<span style="color:#0A6C00">'.$data['cursor']['estimatedResultCount'].'</span>';
	}
	else{
		$res=0;
	}
	return $res; 
}
function GoogleSearch($query,$search_url) {
	$results=array();
	$query=urlencode($query);
	for ($i=0; $i<8; $i++){
		$start=$i*8;
		$google_search_url='https://www.google.com/uds/GwebSearch?callback=google.search.WebSearch.RawCompletion&rsz=large&hl=ru&source=gsc&gss=.com&sig=a075bd7791aa5f99550ea5c9db01a0d5&start='.$start.'&q='.$query.'&gl=www.google.com&qid=1425b41678c33971d&context=1&key=notsupplied&v=1.0&nocache=1384510482257';
		$content = get_content($google_search_url);
		preg_match('/\{(.*)\}/', $content,$res);
		$result=json_decode($res[0],true);
		foreach ($result['results'] as $key => $value) {
			if (substr_count($value['url'], $search_url)>0) {
				$key=$start+$key+1;
				$results[$key]=$value['url'];
			}
		}
		
	}
	return $results;
}


function printform ($textareadomains='', $textarearobots='', $filename='', $numform='', $mainform = '') {
	$form = "<div id='robotsform".$numform."'><div class='textarea_block'>
			<div class='textarea_title'>Test domains</div>
			<textarea name='domains".$numform."' rows='8' cols='30' placeholder='Введите список доменов через Еnter в формате visahq.'>".$textareadomains."</textarea>
			</div>
			<div class='textarea_block'>
				  <div class='textarea_title2'>Test robot</div>
				  <textarea name='robot".$numform."' rows='8' cols='50' placeholder='Введите текст робота через Еnter' style='float: left;'>".$textarearobots."</textarea>";
			if ($numform != 0) {
			 $form .= "<div style = 'background: url(images/delete.png) no-repeat; float: left; height:15px; width: 15px; margin-left: 5px; cursor: pointer;' onclick= window.location.href='?delete=".$numform."'></div>";
		}
			$form .= "</div>
			<div style='clear:both'></div></div>";
	
	return $form;
  
}


function searchelement ($arr, $searchkey) {
    foreach ($arr as $v) {
	if (trim($v) == trim($searchkey)) {
	    return true;
	}
    }
    return false;
}


function robot_test ($domains, $robot, $mainform = 1) {
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
	//	$domain_list=array('visahq.com');
	}
	elseif($domains!='' and $robot!=''){
		$domain_list=explode("\n", $domains);
	/*	if ($mainform == 0) {
			if (!searchelement($domain_list, 'http://www.visaiq.com')) {
				$domain_list[]='http://www.visaiq.com';
				$f=fopen('files/robotsfiles/test_domains.txt', 'a+');
				fputs($f, "\r\nhttp://www.visaiq.com");
				fclose($f);
			}
		}*/
		$robot_list=robots_ms($robot);
	}
	$count_domains=count($domain_list);
	for ($i=0; $i<$count_domains; $i++) {
		$domain_list[$i]=trim($domain_list[$i]);
		if (substr_count($domain_list[$i], 'https://')>0 || substr_count($domain_list[$i], 'http://')>0) {
			$robots_url="$domain_list[$i]/robots.txt";
		}
		else{
			$robots_url="http://www.$domain_list[$i]/robots.txt";
		}
		$get_visa_robots=get_content($robots_url);
		$visa_robots_ms=robots_ms($get_visa_robots);
		if (count($robot_list)!=count($visa_robots_ms)) {
			$check_robots_result[][$robots_url]='<span style="color:red">Не идентичны</span>';
		}
		else{
			$tmp_res=array_diff($robot_list, $visa_robots_ms);
			if (count($tmp_res)!=0) {
				$check_robots_result[][$robots_url]='<span style="color:red">Не идентичны</span>';
			}
			else{
				$check_robots_result[][$robots_url]='<span style="color:green">Идентичны</span>';
			}
		}
	}
	if ($error==1) {
		echo '<div>';
		foreach ($error_text as $key => $value) {
			echo $value.'<br>';
		}
		echo '</div>';
	}
	return $check_robots_result;
}

function parse_filename ($filesarray) {
	preg_match_all('/\d+/', $filesarray, $out);
	for ($i=0; $i<=count($out, COUNT_RECURSIVE); $i++) {
		if ($max < $out[0][$i]) {
			$max = $out[0][$i];
		}
	}
	return $max;
}

