<meta charset="utf-8">
<?php
    $f=fopen('files/cron_reports.txt','r');

    while(!feof($f)){
        $buffer = fgets($f);
        echo $buffer."<br>";
    }
    fclose($f);
