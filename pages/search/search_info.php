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
                    <li>
                        <a href="#"><span class="badge badge-warning">7</span></a>
                    </li>
                    
                    <li class="divider-vertical"></li>
                    
                    <li class="dropdown">
                        
                        <a data-toggle="dropdown" class="dropdown-toggle " href="#">
                            Rod Howard <b class="caret"></b>                            
                        </a>
                        
                        <ul class="dropdown-menu">
                            <li>
                                <a href="./account.html"><i class="icon-user"></i> Account Setting  </a>
                            </li>
                            
                            <li>
                                <a href="./change_password.html"><i class="icon-lock"></i> Change Password</a>
                            </li>
                            
                            <li class="divider"></li>
                            
                            <li>
                                <a href="./"><i class="icon-off"></i> Logout</a>
                            </li>
                        </ul>
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
                if($i%10==0&&$i>1){echo"当前第";echo$page;echo"页，共";echo$totalpage;echo"页,共检索到";echo$num_results;echo"条信息";$page+=1;echo"</div>";}
                if ($i==$num_results){echo"当前第";echo$page;echo"页，共";echo$totalpage;echo"页,共检索到";echo$num_results;echo"条信息";echo"</div>";}
            }   

           echo'
<script type="text/javascript">
var now=1; var pp =1;
var totalpage =' .$totalpage.';


var pa=new Array();
if(totalpage==1)pa[0]=1;
else if (totalpage==2){pa[0]=1;pa[1]=2;}
else if (totalpage==3){pa[0]=1;pa[1]=2;pa[2]=3;}
else if (totalpage==4){pa[0]=1;pa[1]=2;pa[2]=3;pa[3]=4;}
else if (totalpage==5||now==1||now==2){pa[0]=1;pa[1]=2;pa[2]=3;pa[3]=4;pa[4]=5;}
else if(now==(totalpage-1)){pa[0]=totalpage-4;pa[1]=totalpage-3;pa[2]=totalpage-2;pa[3]=totalpage-1;pa[4]=totalpage;}
else if(now==totalpage){pa[0]=totalpage-4;pa[1]=totalpage-3;pa[2]=totalpage-2;pa[3]=totalpage-1;pa[4]=totalpage;}
else {pa[0]=now-2;pa[1]=now-1;pa[2]=now;pa[3]=now+1;pa[4]=now+2;}
 document.getElementById("1").style.display="";
</script>';
           echo'
<script type="text/javascript">
 $(document).ready(function(){
$("#but1").click(function(){
    document.getElementById(now.toString()).style.display="none";
    document.getElementById("1").style.display="";
    now=1;
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
       }else document.getElementById((totalpage).toString()).style.display=""; });});
</script>';

 echo'
<script type="text/javascript">
 $(document).ready(function(){
$("#but3").click(function(){
   if(now>1){
    document.getElementById(now.toString()).style.display="none";
    document.getElementById((now-1).toString()).style.display="";
    now-=1;}else  document.getElementById("1").style.display=""; 
        });});
</script>';

echo'
<script type="text/javascript">
 $(document).ready(function(){
$("#but4").click(function(){
    document.getElementById(now.toString()).style.display="none";
    document.getElementById(totalpage.toString()).style.display="";
    now=totalpage;
        });});
</script>';
echo"<div>";
echo"<style type=\"text/css\">

.button {
    
    display: inline-block;
}</style>";
echo "<div class=\"button\"><button id=\"but1\">First</button></div>";

echo "<div class=\"button\"><button id=\"but3\">Previous</button></div>";
echo "<div class=\"button\"><button id=\"but2\">Next</button></div>";
echo "<div class=\"button\"><button id=\"but4\">Last</button></div>";echo"</div>"; // 翻页模块
            
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