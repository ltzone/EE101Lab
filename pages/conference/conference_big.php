<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Conferences</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />    
    
    <link href="../../css/bootstrap.min.css" rel="stylesheet" />
    <link href="../../css/bootstrap-responsive.min.css" rel="stylesheet" />
    
    
    <link href="../../css/font-awesome.css" rel="stylesheet" />
    
    <link href="../../css/adminia.css" rel="stylesheet" /> 
    <link href="../../css/adminia-responsive.css" rel="stylesheet" /> 
    <link href="../../css/pages/dashboard.css" rel="stylesheet" /> 
    <script src="../../js/echarts.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

<body>


<?php
$conference_id = $_GET["conference_id"];
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


            <?php echo "<a class=\"brand\" href=\"./conference_info.php?conference_id=$conference_id\">Conferences</a>";?>
                        <div class="nav-collapse">
            
                <ul class="nav pull-right">
                    
                    
                    <li class="dropdown">
                        
                    <div class="input-group">
                     <form action="../search/search_info.php" style="margin:0px">
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
                    
                    <li>
                        <?php echo "<a href=\"./conference_info.php?conference_id=$conference_id\">" ?>
                            <i class="icon-user"></i>
                            Conference Information      
                        </a>
                    </li>
                    <li>
                        <?php echo "<a href=\"./conference_charts.php?conference_id=$conference_id\">" ?>
                            <i class="icon-signal"></i>
                            Conference Charts
                        </a>
                    </li>
                    <li  class="active">
                        <?php echo "<a href=\"./conference_big.php?conference_id=$conference_id\">" ?>
                            <i class="icon-signal"></i>
                            Big Graph
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
                    Conference Charts              
                </h1>

                <div class="widget">
                    
                    <div class="widget-header">
                        <h3>Overall Picture</h3>
                    </div> <!-- /widget-header -->
                                                        
                    <div class="widget-content">
                    <iframe src="../../img/<?php echo $conference_id;?>.svg" width="700" height="800"></iframe>
                        
                                        
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