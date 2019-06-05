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
                    
                    <li>
                        <?php echo "<a href=\"./search_info.php?keyword=$keyword\">" ?>
                            <i class="icon-user"></i>
                            Search Results    
                        </a>
                    </li>
                    
                    <li class="active">
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
                   Search Charts by keyword <?php echo $keyword; ?>              
                </h1>

                <div class="widget">
                    
                    <div class="widget-header">
                        <h3>Area Chart</h3>
                    </div> <!-- /widget-header -->
                                                        
                    <div class="widget-content">
                        
                        <div id="area-chart" class="chart-holder"></div> <!-- /area-chart -->
                        
                        
                                        
                    </div> <!-- /widget-content -->
                    
                </div> <!-- /widget -->
                
                
                
                
                <div class="widget">
                    
                    <div class="widget-header">
                        <h3>Line Chart</h3>
                    </div> <!-- /widget-header -->
                                                        
                    <div class="widget-content">
                        
                        <div id="line-chart" class="chart-holder"></div> <!-- /donut-chart -->
                        
                        
                                        
                    </div> <!-- /widget-content -->
                    
                </div> <!-- /widget -->
                
                
                
                <div class="widget">
                    
                    <div class="widget-header">
                        <h3>Bar Chart</h3>
                    </div> <!-- /widget-header -->
                                                        
                    <div class="widget-content">
                        
                        <div id="bar-chart" class="chart-holder"></div> <!-- /donut-chart -->
                        
                        
                                        
                    </div> <!-- /widget-content -->
                    
                </div> <!-- /widget -->
                
                
                
                
                <div class="widget">
                    
                    <div class="widget-header">
                        <h3>Pie Chart</h3>
                    </div> <!-- /widget-header -->
                                                        
                    <div class="widget-content">
                        
                        <div id="pie-chart" class="chart-holder"></div> <!-- /donut-chart -->
                        
                        
                                        
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