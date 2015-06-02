function save_config(){
		if (confirm('You really want to save this data')) {
			domains=$("textarea[name='domains']").val();
			pages=$("textarea[name='pages']").val();
			test_domains=$("textarea[name='test_domains']").val();
			prefix=$("textarea[name='prefix']").val();
			noindex_page=$("textarea[name='noindex_page']").val();
		}
		else{
			return false;
		}
	$.post('ajax/save_config.php', {domains:domains,pages:pages,test_domains:test_domains,prefix:prefix,noindex_page:noindex_page}, function(result) {});
}