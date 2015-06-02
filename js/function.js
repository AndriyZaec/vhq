var i = 1;
var removeel = [];
var inc = '';

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

function parseGetParams() { 
   var $_GET = {}; 
   var __GET = window.location.search.substring(1).split("&"); 
   for(var i=0; i<__GET.length; i++) { 
      var getVar = __GET[i].split("="); 
      $_GET[getVar[0]] = typeof(getVar[1])=="undefined" ? "" : getVar[1]; 
   } 
   return $_GET; 
} 

function increment (v) {
	//var urlparams = parseGetParams;
	/*if (urlparams.length > 0) {
	    v = v + parseint(urlparams);
	}*/
	v = v + 1;
	return v;
}


function RemoveForm (v) {
	$("#robotsform" + v).remove();
	/*var fd = new File("/files/robotsforms/test_forms"+v+".txt");
	alert (fd);
	fd.remove();*/
}

function CreateForm (inc) {
  $.trim(inc);
  if (inc > 0) {
	i = i + parseInt(inc);
  };
  //alert (i);
	i = increment (i);
	id = 'robotsform' + i;
	$("#newform").append(
	$("<div/>", {id: 'robotsform' + i}).append(
	$("<div/>", {class: 'textarea_block'}).append(
	$("<div/>", {class: 'textarea_title'}).append(
	$("<span/>").text('Test domains')),
	$("<textarea/>", { name: 'domains'+i, rows: '8', cols: '30', placeholder: 'Введите список доменов через Еnter'})),
	$("<div/>", {class: 'textarea_block'}).append(
	$("<div/>", {class: 'textarea_title2'}).append(
	$("<span/>").text('Test robot')),
	$("<textarea/>", { name: 'robot'+i, rows: '8', cols: '50', placeholder: 'Введите текст робота через Еnter', style: 'float: left'}),
	$("<div/>", {style: 'background: url(images/delete.png) no-repeat; height:15px; width: 15px; float: left; margin-left: 5px; cursor: pointer;', onclick: 'RemoveForm('+i+')'})),
	$("<div/>", {style: 'clear:both'}),
	$("<input/>", { type: 'hidden', name: 'senddata', value: ''+i+''}),
	$("<input/>", { type: 'hidden', name: 'search'+i, value: 'Проверка test_robots.txt (test domains)'})
	))
	}
