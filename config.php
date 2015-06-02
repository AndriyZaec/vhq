<?php 
include('header.php');
$domains=file_get_contents('files/domains.txt');
$pages=file_get_contents('files/pages.txt');
$test_domains=file_get_contents('files/test_domains.txt');
$prefix=file_get_contents('files/prefix.txt');
$noindex_page=file_get_contents('files/noindex_page.txt');
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
	<div class='textarea_block'>
		<div class='textarea_title'>Test domains</div>
		<textarea name="test_domains" rows="8" cols="50" placeholder="Введите список доменов через Еnter в формате visahq."><?=$test_domains?></textarea>
	</div>
	<div style='clear:both'></div>
	<div class="config_block">
		PLUGINS NOINDEX CONFIG
		<div style='clear:both'></div>
		<div class='textarea_block'>
			<div class='textarea_title'>Prefix</div>
			<textarea name="prefix" rows="8" cols="50" placeholder="Введите список префиксов через Еnter"><?=$prefix?></textarea>
		</div>
		<div class='textarea_block'>
			<div class='textarea_title'>Noindex-page</div>
			<textarea name="noindex_page" rows="8" cols="50" placeholder="Введите список страниц через Еnter начиная со слеша"><?=$noindex_page?></textarea>
		</div>
		<div style='clear:both'></div>
	</div>

	<div>
		<input type="button" name="" value="SAVE CONFIG" onclick="save_config()">
	</div>
</form>