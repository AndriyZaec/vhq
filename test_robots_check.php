<?php 
include('header.php');
$check_robots_result=array();
$check_robots_resultnew=array();
$i = 0;
$max = 0;
$sendmail = 0;
$pathfolder = 'files/robotsforms';
$folderdomains = 'files/robotsfiles';
//-meil start---
$subject = "Visa coincidence test robots"; //Тема
$file_url="files/test_robots_res.txt";
//--mail end---
$domains=file_get_contents('files/robotsfiles/test_domains.txt');
$robot=file_get_contents('files/robotsfiles/test_robots.txt');

echo "<form id='robotsform' action='' method='post' accept-charset='utf-8'><div id='newform'></div>";
//echo $_POST['senddata'];
$delete = $_GET['delete'];
if ($delete > 0) {
	$numberfile = $_GET['delete'];
	$filename = $pathfolder."/test_forms$numberfile.txt";
	$namedomains = $folderdomains."/test_domains$numberfile.txt";
	$namerobots = $folderdomains."/test_robots$numberfile.txt";
	if(file_exists($filename)) {
		unlink($filename);
	}
	if(file_exists($namedomains)) {
		unlink($namedomains);
	}
	if(file_exists($namerobots)) {
		unlink($namerobots);
		echo '<script type="text/javascript">'; 
		echo 'window.location.href="'.$_SERVER["PHP_SELF"].'"'; 
		echo '</script>';
		exit;
	}		
}

if (isset($_POST['senddata']) or isset($_POST['senddatarepeat']) or isset($_GET['cron'])) {

if ($_POST['senddata'] > 0) {
	$countform = $_POST['senddata'];
}
else
{
	$countform = $_POST['senddatarepeat'];
}
$max = maxnumberfile(0, $pathfolder);
if ($max > 0) {
	$countform += $max;
}
else
{
	$max=0;
}
    for ($i=0; $i <= $countform; $i++) {
	    if (isset ($_GET['cron'])) {
		$filerobots = "files/robotsfiles/test_robots".$i.".txt";
		$filedomain = "files/robotsfiles/test_domains".$i.".txt";
		if ($i == 0) {
			$filerobots = "files/robotsfiles/test_robots.txt";
			$filedomain = "files/robotsfiles/test_domains.txt";
			$mainform = 0;
		}
		else
		{
			$mainform = 1;
		}
		      
		      if (file_exists($filerobots) and file_exists($filedomain)) {
			  $cronfile = fopen($filerobots, "r");
			  $cronrobot = fread($cronfile, filesize($filerobots));
			  fclose($cronfile);
			  $cronfile = fopen($filedomain, "r");
			  $crondomain = fread($cronfile, filesize($filedomain));
			  fclose($cronfile);
			  $check_robots_result[] = robot_test ($crondomain, $cronrobot, $mainform);
		      }
		if ($i == $countform) {
			  echo "<p><span style='color:red;'>Запущен режим работы скрипта по крону для продолжения тестирования файлов Robots нужно перейти по ссылке <a href ='".$_SERVER['PHP_SELF']."'>Вернуться к работе с формами тестирования</a></span></p>";
		}
					
	    }
	    if (strlen($_POST["domains".$i]) > 0 and strlen($_POST["robot".$i]) > 0) {
			$textarearobots = trim($_POST["robot".$i]);
			file_put_contents("files/robotsfiles/test_robots".$i.".txt", $textarearobots);
			$textareadomains = trim($_POST["domains".$i]);
			file_put_contents("files/robotsfiles/test_domains".$i.".txt", $textareadomains);
			$filename = "files/robotsforms/test_forms".$i.".txt";
			$form = printform($textareadomains, $textarearobots, '', $i);
			file_put_contents($filename, $form);
			$check_robots_result [] = robot_test ($textareadomains, $textarearobots);
	    }
	}
//	$check_robots_result[]= $check_robots_result1;
	arsort($check_robots_result);
}
else
{
   echo printform ($domains, $robot, '', '', 'mainform');
}

if (isset($_POST['domains']) or isset($_POST['robot'])) {
      $textrobot = $_POST['robot'];
      $textdomain = $_POST['domains'];
      mainformcheck ($textdomain, $textrobot, $domains, $robots);
      $check_robots_result [] = robot_test ($textdomain, $textrobot, 0);
}


function mainformcheck ($textareadomains, $textarearobots, $domains, $robots) {
    
    	  file_put_contents("files/robotsfiles/test_domains.txt", $textareadomains);
	  file_put_contents("files/robotsfiles/test_robots.txt", $textarearobots);
	  echo printform ($textareadomains, $textarearobots);

}



function maxnumberfile ($out = 0, $pathfolder) {
if ($handle = opendir($pathfolder)) {
    while (false !== ($file = readdir($handle))) { 
        $files .= $file;
		if ($out > 0) {
			if (strlen($file)>0)	{
				$filesname = $pathfolder."/".$file;
				if (file_exists($filesname)) {	
					$fileopen = fopen($filesname, "r");
					$text = fread($fileopen, filesize($filesname));
					echo $text;
				}
			}
		}	
    }
    closedir($handle); 
    $max=parse_filename($files);
    return $max;
}}

maxnumberfile(1, $pathfolder);

echo '<div>	
</div><input type="hidden" name="senddatarepeat" value="'.$i.'';
	echo '"/>
		<input id="submitrobots" type="submit" value="Проверка test_robots.txt (test domains)" onclick=window.location.href="">
	</form><div style="padding: 10px 0 20px 0;"><input id="addform" type="button" value="Добавить форму проверки" onclick="CreateForm('.$i.')" /></div>';


?>



<table width="100%" border="1" cellspacing="0" cellpadding="4">
	<thead>
		<tr>
			<th>URL</th>
			<th>STATUS</th>
		</tr>
	</thead>
	<tbody>
  <?php 
 
  		 
	
	 function checkout ($check_robots_result,$file = 0,$ident='') {
		global $sendmail;
		if ($file == 1) {
			$option = 'a+';
		}
		elseif ($file == 0) {
			$option = 'w+';
		}
			$file=fopen('files/test_robots_res.txt', $option);
			
			foreach ($check_robots_result as $key => $value) {
		if ($ident==0) {
			$optionscheck="Не идентичны";
		}
		elseif ($ident==1) {
			$optionscheck="Идентичны";
		}
		$pos=strpos($value, $optionscheck);
			if ($pos > 0) {
				    if ($ident==0) {
					$sendmail = 1;
				    }
				
					if (!is_array($value)) {
				
						$str .= $key.' = '.$value.'<br>';
						fputs($file, $str);
					
						echo	"<tr>
								<td>$key</td>
								<td>$value</td>
							</tr>";
					}	
			}
				if (is_array($value)) {
					$str = checkout($value, 1, $ident);

				}
			
			}
		fclose($file);	
		}
	if (count($check_robots_result)>0) {
			checkout($check_robots_result,0,0);
			checkout($check_robots_result,1,1);
			if ($sendmail==1) {
				sendMail($to,$from_mail,$from_name,$subject,$message,$boundary,$filemail,$file_url);
			}
		}

	?>
	</tbody>
</table>

	
</body>
</html>
