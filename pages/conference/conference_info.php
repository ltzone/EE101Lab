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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

<body>


<?php
$conference_id = $_GET["conference_id"];
$link = mysqli_connect("localhost:3306", 'root', '', 'FINAL');
$result = mysqli_query($link, "SELECT ConferenceName from conferences where ConferenceID='$conference_id'");
        $conference_name = mysqli_fetch_array($result)['ConferenceName'];
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
                        <?php echo "<a href=\"./conference_info.php?conference_id=$conference_id\">" ?>
                            <i class="icon-user"></i>
                            Conference Information      
                        </a>
                    </li>
                    
                    <li >
                        <?php echo "<a href=\"./conference_charts.php?conference_id=$conference_id\">" ?>
                            <i class="icon-signal"></i>
                            Conference Charts
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
                   Conference Information for      <?php echo $conference_name;?>          
                </h1>

                <!-- 放置paper相关信息 -->
                <div class="row">
                    
                    <div class="span9">
                                    
                        <div class="widget">
                            
                            <div class="widget-header">
                                <h3><?php echo $conference_name;?></h3>
                            </div> <!-- /widget-header -->
                                                                
                            <div class="widget-content">
<?php   


        $result = mysqli_query($link, "SELECT count(PaperID) from papers where ConferenceID='$conference_id' ");
        $row = $result->fetch_array();
        $num_results = $row[0];

        echo "<table>";
        echo "<tr><td width = '120'>Papers:</td><td>";
        echo ($num_results);
        echo "</td></tr>";


        echo "</table>";?>
                            </div> <!-- /widget-content -->
                            
                        </div> <!-- /widget -->
                        
                    </div> <!-- /span5 -->
                    
                </div> <!-- /row -->        

                <div class="row">
                    <div class="span9">
                        <div class="widget">

<?php
                    $result = mysqli_query($link, "SELECT PaperID  from papers where ConferenceID='$conference_id'");
                if ($result->num_rows) {              
                echo "
                <div class=\"widget widget-table\">
                                        
                    <div class=\"widget-header\">
                        <i class=\"icon-th-list\"></i>
                        <h3>Reference Table</h3>
                    </div> <!-- /widget-header -->
                    
                    <div class=\"widget-content\">
                    
                        <table class=\"table table-striped table-bordered\">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Paper Name</th>
                                    <th>Authors</th>
                                    <th>Conference</th>
                                    <th>Year</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>  <tbody>";



            $idx = 1;
            while ($row = mysqli_fetch_array($result)) {
                $paper_id_ref = $row['PaperID'];
                $paper_info = mysqli_fetch_array(mysqli_query($link, "SELECT Title, ConferenceID, PaperPublishYear from papers where PaperID='$paper_id_ref'"));
                if ($paper_info){
                    $paper_title = $paper_info['Title'];
                    $conf_id = $paper_info['ConferenceID'];
                    $yr = $paper_info['PaperPublishYear'];
                    $paper_title2 = ucwords($paper_title);
                    echo "<tr><td>$idx</td><td>";
                    echo "<a href=\"paper.php?paper_id=$paper_id_ref\"><h3>$paper_title2</h3></a>";
                    echo "</td>";
                    echo "<td>";
                    $author_info = mysqli_query($link, "SELECT A.AuthorID, AuthorName FROM paper_author_affiliation A LEFT JOIN authors B ON A.AuthorID = B.AuthorID WHERE PaperID = '$paper_id_ref' ORDER BY AuthorSequence ASC");

                    while ($author_row = mysqli_fetch_array($author_info)){
                        $author_name = $author_row['AuthorName'];
                        $author_another_id = $author_row['AuthorID'];
                        $author_name2 = ucwords($author_name);
                        $author_another_id2 = ucwords($author_another_id);
                        echo "<a href=\"author.php?page=1&author_id=$author_another_id2\">$author_name2</a>";
                        echo "; ";
                    }
                    echo "</td><td>";

                    $conference_row = mysqli_fetch_array(mysqli_query($link, "SELECT ConferenceName from conferences WHERE ConferenceID = '$conf_id'"));
                    $conference_name = $conference_row['ConferenceName'];
                    $conference_name2 = ucwords($conference_name);
                    echo "<a href=\"conference.php?page=1&conference_id=$conf_id\">$conference_name2</a>";
                    echo "</td><td>";
                    echo $yr; echo "</td>";

                    echo "              <td class=\"action-td\">
                                        <a href=\"javascript:;\" class=\"btn btn-small btn-warning\">
                                            <i class=\"icon-ok\"></i>                             
                                        </a>                    
                                        <a href=\"javascript:;\" class=\"btn btn-small\">
                                            <i class=\"icon-remove\"></i>                     
                                        </a>
                                    </td></tr>";
                    $idx +=1;
                }
            }
            echo "</tbody></table>";
            echo "  </div> <!-- /widget-content -->";
        }
        else {
                echo "<div class='widget-content'><h4>Papers not found</h4></div>";
        }   ?>
                        </div>




                    </div>
                </div>
 
                
          
                
                

                
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
<script src="../../js/charts/bar.js"></script>

  </body>
</html>
