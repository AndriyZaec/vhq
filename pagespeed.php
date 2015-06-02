 <?php
include('header.php');
$domains=file_get_contents('files/domains.txt');
$domain_list=explode("\n", $domains);
$count_domains=count($domain_list);
for ($i=0; $i <$count_domains; $i++) { 
    $domain_list[$i]=trim($domain_list[$i]);
}
$pages=file_get_contents('files/pages.txt');
$page_list=explode("\n", $pages);
$count_pages=count($page_list);
for ($i=0; $i <$count_pages; $i++) { 
    $page_list[$i]=trim($page_list[$i]);
}
$domain_list_for_js=json_encode($domain_list);
$pages_for_js=json_encode($page_list);
?>
<script>
    var url=[];
    var score=[];
    var totalRequestBytes=[];
    var numberStaticResources=[];
    var cssResponseBytes=[];
    var imageResponseBytes=[];
    var javascriptResponseBytes=[];
    var otherResponseBytes=[];
    var numberHosts=[];
    var htmlResponseBytes=[];
    var ruleResults=[];
    var domain_list=<?php echo ($domain_list_for_js);?>;
    var pages_list=<?php echo ($pages_for_js);?>;
    var pages_stats=[]; 
    pages_results=[];
    pages_statistics=[];
    queryID=1;
    
    function createStats(url,queryID){
      this.url=url;
      this.score=null;
      this.totalRequestBytes=null;
      this.numberHosts=null;
      this.numberStaticResources=null;
      this.cssResponseBytes=null;
      this.imageResponseBytes=null;
      this.javascriptResponseBytes=null;
      this.otherResponseBytes=null;
      this.htmlResponseBytes=null;
      this.queryID=queryID;
    }

    function getURLsForUpdate(){
        change_pages_results_id=[]; 
        pages_results_length=pages_results.length;
        var pages_statistics_counter=0;
        pages_statistics=new Array();
        var change_pages_count=0;
        for (i=0;i<=pages_results_length;i++){
            if (pages_results[i]!=undefined){
                if ((pages_results[i].score==null)||(pages_results[i].totalRequestBytes==null)||
                        (pages_results[i].numberHosts==null)||(pages_results[i].numberStaticResources==null)||
                            (pages_results[i].cssResponseBytes==null)||(pages_results[i].imageResponseBytes==null)||
                                (pages_results[i].javascriptResponseBytes==null)||(pages_results[i].otherResponseBytes==null)||
                                    (pages_results[i].htmlResponseBytes==null)) {
                    change_pages_results_id[change_pages_count]=i;
                    pages_statistics[pages_statistics_counter]=pages_results[i]; 
                    pages_statistics_counter=pages_statistics_counter+1;
                    change_pages_count=change_pages_count+1;
                }
            }
        }
    }

    function setUpdateStats(){
        var change_pages_count=change_pages_results_id.length;
        for (i=0;i<change_pages_count;i++){
            pages_results[change_pages_results_id[i]]=pages_statistics[i];
        }
       var pages_results_add_id=pages_results.length;
        for (i=change_pages_count;i<pages_statistics.length;i++){
            pages_results[pages_results_add_id]=pages_statistics[i];
            pages_results_add_id=pages_results_add_id+1;
        }
    }
    function printCell(cell_text,newtr,tr_id,cell_id){
       newtd = document.createElement("td");
       $(newtd).text(cell_text);
       $(newtd).attr("id",tr_id+'.'+cell_id);
       $(newtd).appendTo($(newtr));  
    }
    function updateCell(cell_text,id,cell_id){
        var name_input = document.getElementById(id+'.'+cell_id);
        if ((name_input!=undefined)&&(name_input!=null)&&(cell_text!=null)&&(cell_text!=undefined)){
            name_input.innerText=cell_text;

        }
        else{
            if ((name_input!=undefined)&&(name_input!=null)){
            name_input.innerText=''; 
            }
        }
    }
    function findChange(id){
        var change_pages_length=change_pages_results_id.length;
        for (i=0;i<change_pages_length;i++){
            if (change_pages_results_id[i]==id){
                break;
            }
        }
        if (i==(change_pages_length-1)){
            return -1;
        }
        else{
            return i;
        }
    }
    var API_KEY = 'AIzaSyB71_ng5cLqJ22iyV55r4VysWIBsisWrjM';
    var API_URL = 'https://www.googleapis.com/pagespeedonline/v1/runPagespeed?';
    var CHART_API_URL = 'http://chart.apis.google.com/chart?';
    // Object that will hold the callbacks that process results from the
    // PageSpeed Insights API.
         
    // Invokes the PageSpeed Insights API. The response will contain
    // JavaScript that invokes our callback with the PageSpeed results.         
    function runPagespeed() {
            pages_length=pages_statistics.length;
            for (i=0;i<pages_length;i++){
                if (pages_statistics[i]!=undefined){
                    var s = document.createElement('script');
                    s.type = 'text/javascript';
                    s.async = true;
                    if (pages_statistics[i].url!=null){
                        var query = [
                            'url=' +pages_statistics[i].url,
                            'callback=runPagespeedCallbacks',
                            'key=' + API_KEY
                        ].join('&');
                        s.src = API_URL + query;
                        document.head.insertBefore(s, null);
                    }
                }
            }
        }

    var callbacks = {};
    $(document).ready(function () {
        fragment = document.createDocumentFragment();
        var domains_length=domain_list.length+1;
            for (i=0;i<=domains_length;i++){
                if (i==0){
                    newdiv = document.createElement("div");
                    $(newdiv).addClass("child_radio_div");
                }
                if (i==Math.floor(domains_length/4)||(i==2*Math.floor(domains_length/4))||(i==3*Math.floor(domains_length/4))){
                    fragment.appendChild(newdiv.cloneNode(true));
                    newdiv = document.createElement("div");
                    $(newdiv).addClass("child_radio_div");
                }
                if (i==domains_length){
                    fragment.appendChild(newdiv.cloneNode(true));
                }
                newradio = document.createElement("input");
                $(newradio).attr("type","radio");
                $(newradio).attr("name","domains");
                if (i==0){
                    $(newradio).attr("value","visahq.com"); 
                    $(newradio).appendTo($(newdiv));
                    newspan = document.createElement("span");
                    $(newspan).append("visahq.com");
                    $(newspan).appendTo($(newdiv));
                }
                else{
                    $(newradio).attr("value",domain_list[i-1]);
                    $(newradio).appendTo($(newdiv));
                    newspan = document.createElement("span");
                    $(newspan).append(domain_list[i-1]);
                    $(newspan).appendTo($(newdiv));
                }

               
                newbr = document.createElement("br");
                $(newbr).appendTo($(newdiv));
            }
            for (i=0;i<=Math.floor((domains_length)/4);i++){
               newbr = document.createElement("br");
               fragment.appendChild(newbr.cloneNode(true));
            }
        $(fragment).appendTo("#parent_radio_div");
    });

        // Our JSONP callback. Checks for errors, then invokes our callback handlers.
        function runPagespeedCallbacks(result) {
            if (result.error) {
                var errors = result.error.errors;
                for (var i = 0, len = errors.length; i < len; ++i) {
                    if (errors[i].reason === 'badRequest' && API_KEY === 'yourAPIKey') {
                        alert('Please specify your Google API key in the API_KEY variable.');
                    } else {
         h           // NOTE: your real production app should use a better
                   // mechanism than alert() to communicate the error to the user.
                        alert(errors[i].message);
                    }
                }
            return;
            }

            // Dispatch to each function on the callbacks object.
            for (var fn in callbacks) {
                var f = callbacks[fn];
                if (typeof f === 'function') {
                    callbacks[fn](result);
                }
            }
        }



    function showLostStats(){
    pages_statistics=[];
    var pages_length=pages_list.length;
    getURLsForUpdate();

        // Invoke the callback that fetches results. Async here so we're sure
        // to discover any callbacks registered below, but this can be
        // synchronous in your code.
        setTimeout(runPagespeed, 0); 
        page_index=0;
        callbacks.SeachPageSpeed = function(result) {
            score[page_index]=result.score;
            totalRequestBytes[page_index]=result.pageStats.totalRequestBytes;
            numberStaticResources[page_index]=result.pageStats.numberStaticResources;
            htmlResponseBytes[page_index]=result.pageStats.htmlResponseBytes;
            cssResponseBytes[page_index]=result.pageStats.cssResponseBytes;
            imageResponseBytes[page_index]=result.pageStats.imageResponseBytes;
            javascriptResponseBytes[page_index]=result.pageStats.javascriptResponseBytes;
            otherResponseBytes[page_index]=result.pageStats.otherResponseBytes;
            numberHosts[page_index]=result.pageStats.numberHosts;
            ruleResults[page_index]=result.formattedResults.ruleResults;
            page_index=page_index+1;
        };
        for (i=0;i<pages_length;i++){
            if (pages_statistics[i]!=undefined){
                if (score[i]!=null){
                    pages_statistics[i].score=score[i];
                    pages_statistics[i].numberStaticResources=numberStaticResources[i];
                    pages_statistics[i].numberHosts=numberHosts[i];
                    pages_statistics[i].totalRequestBytes=totalRequestBytes[i];
                    pages_statistics[i].cssResponseBytes = cssResponseBytes[i];
                    pages_statistics[i].imageResponseBytes = imageResponseBytes[i];
                    pages_statistics[i].javascriptResponseBytes = javascriptResponseBytes[i];
                    pages_statistics[i].otherResponseBytes = otherResponseBytes[i];
                    pages_statistics[i].htmlResponseBytes = htmlResponseBytes[i];
                }
            }
        }
        if (pages_results[pages_results.length-1]!=undefined){
            if (pages_results[pages_results.length-1].queryID!=undefined){
                var query_counts=pages_results[pages_results.length-1].queryID;
                for (i = 1; i <= query_counts; i++) {
                    var pages_length=pages_results.length;
                    var pages_for_sort=[];
                    var sorts_ids=[];
                    sorting_index=0;
                    for (j = 0; j < pages_length; j++) { 
                        if (pages_results[j]!=undefined){ 
                            if (pages_results[j].queryID==i){
                                pages_for_sort[sorting_index]=pages_results[j];
                                sorts_ids[sorting_index]=j;
                                sorting_index=sorting_index+1;
                            }
                        }
                    }    
                    var pages_length=pages_for_sort.length;
                    
                    for ( j=pages_length-1; j >= 0; j--){
                        for ( k = 0; k < j; k++){
                            if((pages_for_sort[k]!=undefined)&&(pages_for_sort[k+1]!=undefined)){
                                if (pages_for_sort[k].score > pages_for_sort[k+1].score){
                                    var tmp = pages_for_sort[k];
                                    pages_for_sort[k] = pages_for_sort[k + 1];
                                    pages_for_sort[k + 1] = tmp;
                                }    
                            }
                        }
                    }
                    for (j=0;j<pages_length;j++){
                        pages_results[sorts_ids[j]]=pages_for_sort[j];
                    }
                }
            }
        }



         $(document).ready(function () {
            fragment = document.createDocumentFragment();
            result_length=pages_results.length;
            for (i=0;i<result_length;i++){
               if ((document.getElementById(i)!=undefined)&&(document.getElementById(i)!=null)){
                  if ((pages_results[i]!=undefined)&&(pages_results[i]!=null)){
                    if((pages_results[i].score!=null)&&(pages_results[i].score!=undefined)&&($(document.getElementById(i)).is(":visible")==false)){
                         $(document.getElementById(i)).show();
                    }
                    if ((pages_results[i].url!=null)&&(pages_results[i].url!=undefined)){
                        updateCell(pages_results[i].url,i,0);
                    }
                    if ((pages_results[i].url==null)||(pages_results[i].url==undefined)){
                        updateCell("",i,0);
                    }
                    if ((pages_results[i].score!=null)&&(pages_results[i].score!=undefined)){
                        updateCell(pages_results[i].score,i,1);
                    }
                    if ((pages_results[i].score==null)||(pages_results[i].score==undefined)){
                        updateCell("",i,1);
                    }
                    if ((pages_results[i].numberStaticResources!=null)&&(pages_results[i].numberStaticResources!=undefined)){
                        updateCell(pages_results[i].numberStaticResources,i,2);
                    }
                    if ((pages_results[i].numberStaticResources==null)||(pages_results[i].numberStaticResources==undefined)){
                        updateCell("",i,2);
                    }
                    if ((pages_results[i].numberHosts!=null)&&(pages_results[i].numberHosts!=undefined)){
                        updateCell(pages_results[i].numberHosts,i,3);
                    }
                    if ((pages_results[i].numberHosts==null)||(pages_results[i].numberHosts==undefined)){
                        updateCell("",i,3);
                    }
                    if ((pages_results[i].totalRequestBytes!=null)&&(pages_results[i].totalRequestBytes!=undefined)){
                        updateCell(pages_results[i].totalRequestBytes,i,4);
                    }
                    if ((pages_results[i].totalRequestBytes==null)||(pages_results[i].totalRequestBytes==undefined)){
                        updateCell("",i,4);
                    }
                    if ((pages_results[i].htmlResponseBytes!=null)&&(pages_results[i].htmlResponseBytes!=undefined)){
                        updateCell(pages_results[i].htmlResponseBytes,i,5);
                    }
                    if ((pages_results[i].htmlResponseBytes==null)||(pages_results[i].htmlResponseBytes==undefined)){
                        updateCell("",i,5);
                    }
                    if ((pages_results[i].cssResponseBytes!=null)&&(pages_results[i].cssResponseBytes!=undefined)){
                        updateCell(pages_results[i].cssResponseBytes,i,6);
                    }
                    if ((pages_results[i].cssResponseBytes==null)||(pages_results[i].cssResponseBytes==undefined)){
                        updateCell("",i,6);
                    }
                    if ((pages_results[i].imageResponseBytes!=null)&&(pages_results[i].imageResponseBytes!=undefined)){
                        updateCell(pages_results[i].imageResponseBytes,i,7);
                    }
                    if ((pages_results[i].imageResponseBytes==null)||(pages_results[i].imageResponseBytes==undefined)){
                        updateCell("",i,7);
                    }
                    if ((pages_results[i].javascriptResponseBytes!=null)&&(pages_results[i].javascriptResponseBytes!=undefined)){
                        updateCell(pages_results[i].javascriptResponseBytes,i,8);
                    }
                    if ((pages_results[i].javascriptResponseBytes==null)||(pages_results[i].javascriptResponseBytes==undefined)){
                        updateCell("",i,8);
                    }
                    if ((pages_results[i].otherResponseBytes!=null)&&(pages_results[i].otherResponseBytes!=undefined)){
                        updateCell(pages_results[i].otherResponseBytes,i,9);
                    }
                    if ((pages_results[i].otherResponseBytes==null)||(pages_results[i].otherResponseBytes==undefined)){
                        updateCell("",i,9);
                    }
                  }
               } 
             else{
                 if ((pages_results[i]!=undefined)&&(pages_results[i]!=null)){
                            newtr = document.createElement("tr");
                                if (pages_results[i].queryID%2==0){
                                    var class_for_rows="second_rows";
                                }
                                else{
                                     var class_for_rows="first_rows";
                                }
                            $(newtr).addClass(class_for_rows);
                            $(newtr).attr("id",i);
                            printCell(pages_results[i].url,newtr,i,0);
                            $(newtd).addClass("url_cell");
                            printCell(pages_results[i].score,newtr,i,1);
                            printCell(pages_results[i].numberStaticResources,newtr,i,2);
                            printCell(pages_results[i].numberHosts,newtr,i,3);
                            printCell(pages_results[i].totalRequestBytes,newtr,i,4);
                            printCell(pages_results[i].htmlResponseBytes,newtr,i,5);
                            printCell(pages_results[i].cssResponseBytes,newtr,i,6);
                            printCell(pages_results[i].imageResponseBytes,newtr,i,7);
                            printCell(pages_results[i].javascriptResponseBytes,newtr,i,8);
                            printCell(pages_results[i].otherResponseBytes,newtr,i,9);
                            if (pages_results[i].score==null){
                                $(newtr).hide();
                            }
                            fragment.appendChild(newtr.cloneNode(true));
                            $(fragment).appendTo("#table_body");
                 }
               }
            }

        
        
        });
    }  



    function showPageSpeed(){
    var domain=$("input[name='domains']:checked").val();
    if (domain==null){
        alert("You must select the domain!");
    }
    else{
    var pages_length=pages_list.length;

    getURLsForUpdate();
    function addNewURLs(){
        var pages_length=pages_list.length;
        for(i=0;i<pages_length;i++){
            var url=pages_list[i];
            url=url.replace('visahq.com',domain);
            pages_statistics[change_pages_results_id.length+i]=new createStats(url,queryID);
        }
    }
    addNewURLs();
        // Invoke the callback that fetches results. Async here so we're sure
        // to discover any callbacks registered below, but this can be
        // synchronous in your code.
        setTimeout(runPagespeed, 0); 
        page_index=0;
        callbacks.SeachPageSpeed = function(result) {
            score[page_index]=result.score;
            totalRequestBytes[page_index]=result.pageStats.totalRequestBytes;
            numberStaticResources[page_index]=result.pageStats.numberStaticResources;
            htmlResponseBytes[page_index]=result.pageStats.htmlResponseBytes;
            cssResponseBytes[page_index]=result.pageStats.cssResponseBytes;
            imageResponseBytes[page_index]=result.pageStats.imageResponseBytes;
            javascriptResponseBytes[page_index]=result.pageStats.javascriptResponseBytes;
            otherResponseBytes[page_index]=result.pageStats.otherResponseBytes;
            numberHosts[page_index]=result.pageStats.numberHosts;
            ruleResults[page_index]=result.formattedResults.ruleResults;
            page_index=page_index+1;
        };
        for (i=0;i<pages_length;i++){
            if (score[i]!=null){
                if (pages_statistics[i]!=undefined){
                    pages_statistics[i].score=score[i];
                    pages_statistics[i].numberStaticResources=numberStaticResources[i];
                    pages_statistics[i].numberHosts=numberHosts[i];
                    pages_statistics[i].totalRequestBytes=totalRequestBytes[i];
                    pages_statistics[i].cssResponseBytes = cssResponseBytes[i];
                    pages_statistics[i].imageResponseBytes = imageResponseBytes[i];
                    pages_statistics[i].javascriptResponseBytes = javascriptResponseBytes[i];
                    pages_statistics[i].otherResponseBytes = otherResponseBytes[i];
                    pages_statistics[i].htmlResponseBytes = htmlResponseBytes[i];
                }
            }
        }
        setTimeout(setUpdateStats, 0); 
        if (pages_results[pages_results.length-1]!=undefined){
            if (pages_results[pages_results.length-1].queryID!=undefined){
                var query_counts=pages_results[pages_results.length-1].queryID;
                for (i = 1; i <= query_counts; i++) {
                    var pages_length=pages_results.length;
                    var pages_for_sort=[];
                    var sorts_ids=[];
                    sorting_index=0;
                    for (j = 0; j < pages_length; j++) { 
                        if (pages_results[j]!=undefined){ 
                            if (pages_results[j].queryID==i){
                                pages_for_sort[sorting_index]=pages_results[j];
                                sorts_ids[sorting_index]=j;
                                sorting_index=sorting_index+1;
                            }
                        }
                    }    
                    var pages_length=pages_for_sort.length;
                    
                    for ( j=pages_length-1; j >= 0; j--){
                        for ( k = 0; k < j; k++){
                            if((pages_for_sort[k]!=undefined)&&(pages_for_sort[k+1]!=undefined)){
                                if (pages_for_sort[k].score > pages_for_sort[k+1].score){
                                    var tmp = pages_for_sort[k];
                                    pages_for_sort[k] = pages_for_sort[k + 1];
                                    pages_for_sort[k + 1] = tmp;
                                }    
                            }
                        }
                    }
                    for (j=0;j<pages_length;j++){
                        pages_results[sorts_ids[j]]=pages_for_sort[j];
                    }
                }
            }
        }

    $(document).ready(function () {
            fragment = document.createDocumentFragment();
            result_length=pages_results.length;
            for (i=0;i<result_length;i++){
               if ((document.getElementById(i)!=undefined)&&(document.getElementById(i)!=null)){
                  if ((pages_results[i]!=undefined)&&(pages_results[i]!=null)){
                    if((pages_results[i].score!=null)&&(pages_results[i].score!=undefined)&&($(document.getElementById(i)).is(":visible")==false)){
                         $(document.getElementById(i)).show();
                    }
                    if ((pages_results[i].url!=null)&&(pages_results[i].url!=undefined)){
                        updateCell(pages_results[i].url,i,0);
                    }
                    if ((pages_results[i].url==null)||(pages_results[i].url==undefined)){
                        updateCell("",i,0);
                    }
                    if ((pages_results[i].score!=null)&&(pages_results[i].score!=undefined)){
                        updateCell(pages_results[i].score,i,1);
                    }
                    if ((pages_results[i].score==null)||(pages_results[i].score==undefined)){
                        updateCell("",i,1);
                    }
                    if ((pages_results[i].numberStaticResources!=null)&&(pages_results[i].numberStaticResources!=undefined)){
                        updateCell(pages_results[i].numberStaticResources,i,2);
                    }
                    if ((pages_results[i].numberStaticResources==null)||(pages_results[i].numberStaticResources==undefined)){
                        updateCell("",i,2);
                    }
                    if ((pages_results[i].numberHosts!=null)&&(pages_results[i].numberHosts!=undefined)){
                        updateCell(pages_results[i].numberHosts,i,3);
                    }
                    if ((pages_results[i].numberHosts==null)||(pages_results[i].numberHosts==undefined)){
                        updateCell("",i,3);
                    }
                    if ((pages_results[i].totalRequestBytes!=null)&&(pages_results[i].totalRequestBytes!=undefined)){
                        updateCell(pages_results[i].totalRequestBytes,i,4);
                    }
                    if ((pages_results[i].totalRequestBytes==null)||(pages_results[i].totalRequestBytes==undefined)){
                        updateCell("",i,4);
                    }
                    if ((pages_results[i].htmlResponseBytes!=null)&&(pages_results[i].htmlResponseBytes!=undefined)){
                        updateCell(pages_results[i].htmlResponseBytes,i,5);
                    }
                    if ((pages_results[i].htmlResponseBytes==null)||(pages_results[i].htmlResponseBytes==undefined)){
                        updateCell("",i,5);
                    }
                    if ((pages_results[i].cssResponseBytes!=null)&&(pages_results[i].cssResponseBytes!=undefined)){
                        updateCell(pages_results[i].cssResponseBytes,i,6);
                    }
                    if ((pages_results[i].cssResponseBytes==null)||(pages_results[i].cssResponseBytes==undefined)){
                        updateCell("",i,6);
                    }
                    if ((pages_results[i].imageResponseBytes!=null)&&(pages_results[i].imageResponseBytes!=undefined)){
                        updateCell(pages_results[i].imageResponseBytes,i,7);
                    }
                    if ((pages_results[i].imageResponseBytes==null)||(pages_results[i].imageResponseBytes==undefined)){
                        updateCell("",i,7);
                    }
                    if ((pages_results[i].javascriptResponseBytes!=null)&&(pages_results[i].javascriptResponseBytes!=undefined)){
                        updateCell(pages_results[i].javascriptResponseBytes,i,8);
                    }
                    if ((pages_results[i].javascriptResponseBytes==null)||(pages_results[i].javascriptResponseBytes==undefined)){
                        updateCell("",i,8);
                    }
                    if ((pages_results[i].otherResponseBytes!=null)&&(pages_results[i].otherResponseBytes!=undefined)){
                        updateCell(pages_results[i].otherResponseBytes,i,9);
                    }
                    if ((pages_results[i].otherResponseBytes==null)||(pages_results[i].otherResponseBytes==undefined)){
                        updateCell("",i,9);
                    }
                  }
               } 
               else{
                 if ((pages_results[i]!=undefined)&&(pages_results[i]!=null)){
                            newtr = document.createElement("tr");
                                if (pages_results[i].queryID%2==0){
                                    var class_for_rows="second_rows";
                                }
                                else{
                                     var class_for_rows="first_rows";
                                }
                            $(newtr).addClass(class_for_rows);
                            $(newtr).attr("id",i);
                            printCell(pages_results[i].url,newtr,i,0);
                            $(newtd).addClass("url_cell");
                            printCell(pages_results[i].score,newtr,i,1);
                            printCell(pages_results[i].numberStaticResources,newtr,i,2);
                            printCell(pages_results[i].numberHosts,newtr,i,3);
                            printCell(pages_results[i].totalRequestBytes,newtr,i,4);
                            printCell(pages_results[i].htmlResponseBytes,newtr,i,5);
                            printCell(pages_results[i].cssResponseBytes,newtr,i,6);
                            printCell(pages_results[i].imageResponseBytes,newtr,i,7);
                            printCell(pages_results[i].javascriptResponseBytes,newtr,i,8);
                            printCell(pages_results[i].otherResponseBytes,newtr,i,9);
                            if (pages_results[i].score==null){
                                $(newtr).hide();
                            }
                            fragment.appendChild(newtr.cloneNode(true));
                            $(fragment).appendTo("#table_body");
                 }
               }
            }

        
        });

        queryID=queryID+1;
    }
}

</script>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>PageSpeed</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>  
       
    <style>
        .table_score{
            width:70%;  
            margin: auto;
            text-align:center;
        }
        .first_rows{
            background-color:#ffffff;
        }
        .second_rows{
            background-color:#dddddd;
        }
        .parent_radio_div{
            width:100%; //опционально
            padding:5px;
            white-space:nowrap;
        }
        .child_radio_div{
            width:300px; //опционально 
            margin:5px;
            float:left;
        }
        .url_cell{
          text-align:left; 
        }
    </style>

</head>
<body>
    
 <div class="parent_radio_div" id="parent_radio_div">
  
   
    </div>
  <br>
  <input id="showPageSpeed" value="Добавить данные по домену" onclick="showPageSpeed();" type="button" />
  <input id="showLostStats" value="Обновить данные" onclick="showLostStats();" type="button" />
  <input id="gotoStatistics" value="Перейти к статистике" onclick="location.href='https://console.developers.google.com/project/apps~sunlit-cove-628'" type="button" />
    <br>
<table border="1" cellspacing="0" cellpadding="4" class="table_score">
    <thead>
        <tr>
            <th>URL</th>
            <th>score</th>
            <th>number of static resources</th>
            <th>number of hosts</th>
            <th>total request bytes</th>
            <th>html response bytes</th>
            <th>css response bytes</th>
            <th>image response bytes</th>
            <th>javascript response bytes</th>
            <th>other response bytes</th>   
        </tr>
    </thead>
    <tbody id="table_body">
         
        
    </tbody>
</table>

</body>
</html>


