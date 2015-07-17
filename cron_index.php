<meta charset="utf-8">
<?php
/**
 * Created by PhpStorm.
 * User: andrij
 * Date: 17.07.15
 * Time: 10:29
 */
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once('include/function.php');
include_once('DOM/phpQuery.php');

$url_list=array();
$result=array();
$file_url="files/result.txt";
$subject = "Duplicate title and description from visa"; //Тема
$domains=file_get_contents('files/domains.txt');
$pages=file_get_contents('files/pages.txt');
$domain_list=explode("\n", $domains);
$page_list=explode("\n", $pages);
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
            echo $error_text[]='Страница по адресу '.$url.' выдает '.$code.' код'.'<br>';
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
