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

            $page_num=$_GET['page'];
            if(!$page_num)$page_num=1;
            if($page_num<0)$page_num=1;

            $num_results =$result['response']['numFound'];
            $page_total=(integer)(($num_results+9)/10);
            if($page_num>$page_total)$page_num=$page_total;
            if($page_num==$page_total)$l=$num_results;
            else $l=($page_num-1)*10+10;



            for ($i=($page_num-1)*10;$i<$l;$i++) {
                $paper=$result['response']['docs'][$i] ;
                $paper_id = $paper['PaperID'];
                $papername2 = ucwords($paper['PaperName']);
                echo "<a href=\"paper.php?paper_id=$paper_id\"><h3>$papername2</h3></a>";
                echo "<table>";
                echo "<tr><td width = '120'><b> Authors: </b></td><td>";
                foreach ($paper['AuthorName'] as $idx => $author) {
                    $author_id = substr($paper['AuthorID'][$idx],2,-3);
                    $author2 = ucwords($author);
                    echo "<a href=\"author.php?page=1&author_id=$author_id\">$author2</a>";
                    echo "; ";
                }
                echo "</td></tr>";
                echo "<tr><td><b> Conference: </b></td><td>";
                $conference_id =$paper['ConferenceID'];
                $conference = $paper['ConferenceName'];
                echo "<a href=\"conference.php?page=1&conference_id=$conference_id\">$conference</a>";
                echo "; ";
                echo "</td></tr>";
                echo "</table>";
                echo "<hr>";

            }   

            // 翻页模块
            echo '<p>PageCount(10 messages per page):&nbsp;&nbsp;'.$page_total.'    </p>';
            echo '<p>Total messages:&nbsp;&nbsp;'.$num_results.'</p>';
            if($page_total>$page_num )
            {
                if($page_num>1)
                {
                    echo '<a href="./search.php?page=1&keyword='.($keyword).'">first page&nbsp;&nbsp;&nbsp;</a>    ';
                    echo '<a href="./search.php?page='.($page_num-1).'&keyword='.($keyword).'"> previous page&nbsp;&nbsp;&nbsp;</a>';
                    echo " "."$page_num".'/'."$page_total"."&nbsp;&nbsp;&nbsp; ";
                    echo '<a href="./search.php?page='.($page_num+1).'&keyword='.($keyword).'">next page&nbsp;&nbsp;&nbsp;</a>';
                    echo '<a href="./search.php?page='.($page_total).'&keyword='.($keyword).'">    last page</a>';
                }
                else 
                {
                    echo 'first page&nbsp;&nbsp;&nbsp;  ';
                    echo ' previous page&nbsp;&nbsp;&nbsp;';
                    echo " "."$page_num".'/'."$page_total"."&nbsp;&nbsp;&nbsp; ";
                    echo '<a href="./search.php?page='.($page_num+1).'&keyword='.($keyword).'">next page&nbsp;&nbsp;&nbsp;</a>';
                    echo '<a href="./search.php?page='.($page_total).'&keyword='.($keyword).'">    last page&nbsp;&nbsp;&nbsp;</a>';
                }
            }
            else
            {
                if($page_total==1)
                {
                echo 'first page&nbsp;&nbsp;&nbsp;    ';
                echo ' previous page&nbsp;&nbsp;&nbsp;';
                echo " "."$page_num".'/'."$page_total"." &nbsp;&nbsp;&nbsp;";
                echo 'next page&nbsp;&nbsp;&nbsp;';
                echo 'last page&nbsp;&nbsp;&nbsp;';
                }
                else
                {
                    echo '<a href="./search.php?page=1&keyword='.($keyword).'">first page&nbsp;&nbsp;&nbsp;</a>     ';
                    echo '<a href="./search.php?page='.($page_num-1).'&keyword='.($keyword).'"> previous page&nbsp;&nbsp;&nbsp;</a>';
                echo " "."$page_num".'/'."$page_total"."&nbsp;&nbsp;&nbsp; ";
                echo 'next page&nbsp;&nbsp;&nbsp;';
                echo 'last page';
                }
            }
            echo "<form  action=\"search.php\"><div style=\"text-align:left;\">";
            echo "<input type=\"integer\"  id=\"page\" name=\"page\">";
            echo "<input name='keyword' type='hidden' id='keyword' value=$keyword>";
            echo "<button type=\"submit\" class=\"btn btn-default\">jump to the page</button></div></form>";
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