<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Search Results</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />    
    
    <link href="../../css/bootstrap.min.css" rel="stylesheet" />
    <link href="../../css/bootstrap-responsive.min.css" rel="stylesheet" />
    
    
    <link href="../../css/font-awesome.css" rel="stylesheet" />
    
    <link href="../../css/adminia.css" rel="stylesheet" /> 
    <link href="../../css/adminia-responsive.css" rel="stylesheet" /> 
    <link href="../../css/pages/dashboard.css" rel="stylesheet" /> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

<body>
<script TYPE="TEXT/JAVASCRIPT" src="http://localhost/js/jquery-1.7.2.min.js"></script>
<?php
$keyword = $_GET["keyword"];
$link = mysqli_connect("localhost:3306", 'root', '', 'FINAL');
?>



<!-- 头部页面 -->   
<div class="navbar navbar-fixed-top">
    
    <div class="navbar-inner">
        
        <div class="container">         
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> 
                <span class="icon-bar"></span> 
                <span class="icon-bar"></span> 
                <span class="icon-bar"></span>              
            </a>
            <?php echo "<a class=\"brand\" href=\"./search_info.php?keyword=$keyword\">Search Results</a>";?>
            
            <div class="nav-collapse">
            
                <ul class="nav pull-right">
                    
                    
                    <li class="dropdown">
                        
                    <div class="input-group">
                     <form action="search_info.php" style="margin:0px">
                      <input type="text" class="form-control" placeholder="Search for more" name="keyword" style="margin:auto;margin-bottom:0px;margin-top:6px">
                      <button class="btn btn-default" type="submit" style="margin:auto;margin-bottom:0px;margin-top:6px" >Go!</button>
                     </form>
                    </div><!-- /input-group -->

                    </li>
                </ul>
                
            </div> <!-- /nav-collapse -->
        </div> <!-- /container -->
    </div> <!-- /navbar-inner -->    
</div> <!-- /navbar -->



<div id="content">
    
    <div class="container">
        
        <div class="row">
            <!-- 左栏标签 -->    
            <div class="span3">
                
                
                <ul id="main-nav" class="nav nav-tabs nav-stacked">
                    
                    <li class="active">
                        <?php echo "<a href=\"./search_info.php?keyword=$keyword\">" ?>
                            <i class="icon-user"></i>
                            Search Results    
                        </a>
                    </li>
                    
                    <li>
                        <?php echo "<a href=\"./search_charts.php?keyword=$keyword\">" ?>
                            <i class="icon-signal"></i>
                            Search Charts
                        </a>
                    </li>
                    <li>
                        <a href="../../index.php">
                            <i class="icon-home"></i>
                            Back to Home                     
                        </a>
                    </li>
                </ul>   
                
                <hr />
                
                <div class="sidebar-extra">
                    <p> Powered by Fang Shaoheng, Dong Shiwen, Yang Hongbo, Zhou Litao.</p>
                </div> <!-- .sidebar-extra -->
                
                <br />
            </div> <!-- /span3 -->


            <!-- 右栏内容 -->
            <div class="span9">
                
                <h1 class="page-title">
                    <i class="icon-user"></i>
                   Search Results               
                </h1>

                <!-- 放置paper相关信息 -->
                <div class="row">
                    
                    <div class="span9">
                                    
                        <div class="widget">
                            
                            <div class="widget-header">
                                <h3><?php echo "Search For $keyword";?></h3>
                            </div> <!-- /widget-header -->
                                                                
                            <div class="widget-content">

<?php
        $keyword = $_GET["keyword"];
        if ($keyword) {
            $ch = curl_init();
            $timeout = 5;
            $query = urlencode(str_replace(' ', '+', $keyword));
            $url = "http://localhost:8983/solr/FINAL/select?q=keyword%3A".$query."&start=0&rows=100000&wt=json";

            curl_setopt ($ch, CURLOPT_URL, $url);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $result = json_decode(curl_exec($ch), true);
            curl_close($ch);

            

            $num_results =$result['response']['numFound'];
            $totalpage=(integer)(($num_results+9)/10);
            $page=1;

            //分割div

            for ($i=1;$i<=$num_results;$i++) {
                if($i%10==1){echo"<div id=\"$page\"style=\"display:none\">";}
                $paper=$result['response']['docs'][$i-1] ;
                $paper_id = $paper['PaperID'];
                $papername2 = ucwords($paper['PaperName']);
                echo "<a href=\"../papers/paper_info.php?paper_id=$paper_id\"><h3>$papername2</h3></a>";
                echo "<table>";
                echo "<tr><td width = '120'><b> Authors: </b></td><td>";
                foreach ($paper['AuthorName'] as $idx => $author) {
                    $author_id = substr($paper['AuthorID'][$idx],2,-3);
                    $author2 = ucwords($author);
                    echo "<a href=\".\..\authors\author_info.php?page=1&author_id=$author_id\">$author2</a>";
                    echo "; ";
                }
                echo "</td></tr>";
                echo "<tr><td><b> Conference: </b></td><td>";
                $conference_id =$paper['ConferenceID'];
                $conference = $paper['ConferenceName'];
                echo "<a href=\".\..\conference\conference_info.php?page=1&conference_id=$conference_id\">$conference</a>";
                echo "; ";
                echo "</td></tr>";
                echo "</table>";
                echo "<hr>";

                if($i%10==0&&$i>1){echo"Page: ";echo$page;echo"<br>";echo"  Total of Pages: ";echo$totalpage;echo"<br>";echo"Total of Papers: ";echo$num_results;$page+=1;echo"</div>";}
                else if ($i==$num_results){echo"Page: ";echo$page;echo"<br>";echo"  Total of Pages: ";echo$totalpage;echo"<br>";echo"Total of Papers: ";echo$num_results;echo"</div>";}

            }   
//翻页
           echo'
<script type="text/javascript">
var now=1; 
var totalpage ='; echo $totalpage ;echo';
 document.getElementById("1").style.display="";
</script>';
           echo'
<script type="text/javascript">
 $(document).ready(function(){
$("#but1").click(function(){
    document.getElementById(now.toString()).style.display="none";
    document.getElementById("1").style.display="";
    now=1;
    for (var it=1;it<=totalpage;++it){
        var itit="b"+it.toString();
        if(it>5)document.getElementById(itit).style.display="none";
        else document.getElementById(itit).style.display="";
        
    } 
      });});
</script>';
            
            echo'
<script type="text/javascript">
 $(document).ready(function(){
$("#but2").click(function(){
    if(now<totalpage){
    document.getElementById(now.toString()).style.display="none";
    document.getElementById((now+1).toString()).style.display="";
    now+=1;
       }
    else document.getElementById((totalpage).toString()).style.display="";
       for (var it=1;it<=totalpage;++it){
        var itit="b"+it.toString();
        if (document.getElementById(itit)){
        if(it<(now-2)||it>(now+2)) { document.getElementById(itit).style.display="none"; }
        else { document.getElementById(itit).style.display=""; }}
        
    }
    var t;
     if(now>=1&&now<=2){if(t= document.getElementById("b4")) t.style.display="";
     if (t=document.getElementById("b5")) t.style.display="";}
     if(now>totalpage-2)document.getElementById("b"+(totalpage-4).toString()).style.display="";   
     if(now>totalpage-1)document.getElementById("b"+(totalpage-3).toString()).style.display="";
      });});  
</script>';

 echo'
<script type="text/javascript">
 $(document).ready(function(){
$("#but3").click(function(){
   if(now>1){
    document.getElementById(now.toString()).style.display="none";
    document.getElementById((now-1).toString()).style.display="";
    now-=1;}else  document.getElementById("1").style.display=""; 

    for (var it=1;it<=totalpage;++it){
        var itit="b"+it.toString();
        if(it<(now-2)||it>(now+2))document.getElementById(itit).style.display="none";
        else document.getElementById(itit).style.display="";
        
    }if(now>=1&&now<=2){document.getElementById("b4").style.display="";
     document.getElementById("b5").style.display="";}
     if(now>totalpage-2)document.getElementById("b"+(totalpage-4).toString()).style.display="";   
     if(now>totalpage-1)document.getElementById("b"+(totalpage-3).toString()).style.display="";  
        });});
</script>';

echo'
<script type="text/javascript">
 $(document).ready(function(){
$("#but4").click(function(){
    document.getElementById(now.toString()).style.display="none";
    document.getElementById(totalpage.toString()).style.display="";
    now=totalpage;

    for (var it=1;it<=totalpage;++it){
        var itit=\'b\'+it.toString();
        if(it<totalpage-4)document.getElementById(itit).style.display="none";
        else document.getElementById(itit).style.display="";}
        });});
</script>';
echo"<div>";
echo"<style type=\"text/css\">

.button {
    
    display: inline-block;
}</style>";
echo"<div class=\"btn-group\">
<div class=\"button\"><button type=\"button\" class=\"btn btn-default \"id=\"but1\">First</button></div>

<div class=\"button\"><button type=\"button\" class=\"btn btn-default \"id=\"but3\">Previous</button></div>";

for($j=1;$j<=$totalpage;++$j){
    $jj=(string)$j;
    echo"<div class =\"button\"><button type=\"button\" class=\"btn btn-default \"id=\"b$jj\">$jj</button></div>";}
  
echo"<div class=\"button\"><button type=\"button\" class=\"btn btn-default \"id=\"but2\">Next</button></div>
<div class=\"button\"><button type=\"button\" class=\"btn btn-default \"id=\"but4\">Last</button></div></div>";
echo"
<script  type=\"text/javascript\">
 var oBtn=document.getElementsByClassName(\"button\");
 for (var it=6;it<=totalpage;++it){
        var itit='b'+it.toString();
        document.getElementById(itit).style.display=\"none\";
        
        
    }

for(var t=1;t<=totalpage;++t){
    oBtn[t+1].index=t;
    oBtn[t+1].onclick=function(){
       var pppp=(this.index).toString();
       document.getElementById(now.toString()).style.display=\"none\";
       document.getElementById(pppp).style.display=\"\"; 
       now=this.index;
       for (var it=1;it<=totalpage;++it){
        var itit='b'+it.toString();
        if(it<(now-2)||it>(now+2))document.getElementById(itit).style.display=\"none\";
        else document.getElementById(itit).style.display=\"\";
        
    }if(now>=1&&now<=2){document.getElementById('b4').style.display=\"\";
     document.getElementById('b5').style.display=\"\";}
     if(now>totalpage-2)document.getElementById('b'+(totalpage-4).toString()).style.display=\"\";   
     if(now>totalpage-1)document.getElementById('b'+(totalpage-3).toString()).style.display=\"\";  
    }}
 /*$(document).ready(function(){

document.write(c);
var ppp=\"#b\"+(parseInt((this.id))/10000).toString();
 var pppp=(parseInt((this.id))/10000).toString();

$(ppp).click(function(){
    
    document.getElementById(now.toString()).style.display=\"none\";
    document.getElementById(pppp).style.display=\"\";

    now=(parseInt((this.id))/10000);
        });});pp+=1;*/
</script>";
            
        }

else {echo "No Search Results!";}

?>

                    </div> <!-- /widget-content -->
                    
                </div> <!-- /widget -->
 
                
          
                
                

                
            </div> <!-- /span9 -->
            
            
        </div> <!-- /row -->
        
    </div> <!-- /container -->
    
</div> <!-- /content -->


<!-- -->











<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="../../js/jquery-1.7.2.min.js"></script>
<script src="../../js/excanvas.min.js"></script>
<script src="../../js/jquery.flot.js"></script>
<script src="../../js/jquery.flot.pie.js"></script>
<script src="../../js/jquery.flot.orderBars.js"></script>
<script src="../../js/jquery.flot.resize.js"></script>


<script src="../../js/bootstrap.js"></script>

  </body>
</html>