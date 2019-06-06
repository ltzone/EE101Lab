<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Papers - Paper Information</title>
    
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
$affiliation_id = $_GET["affiliation_id"];
$link = mysqli_connect("localhost:3306", 'root', '', 'FINAL');
$result = mysqli_query($link, "SELECT AffiliationName from affiliations where AffiliationID='$affiliation_id'");
        $affiliation_name = mysqli_fetch_array($result)['AffiliationName'];
        $affiliation_name2 = ucwords($affiliation_name);

# 查询本机构所有作者（不重复）
$result = mysqli_query($link, "SELECT AuthorID, count(distinct AuthorID) from paper_author_affiliation where AffiliationID='$affiliation_id' group by AuthorID");
$author_list = mysqli_fetch_all($result);
$author_count= count($author_list );

# 查询本机构所有文章 （不重复）
# SELECT PaperID, count(distinct PaperID) from paper_author_affiliation where AffiliationID='01109E6D' group by PaperID
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


            <?php echo "<a class=\"brand\" href=\"./affiliation_info.php?affiliation_id=$affiliation_id\">Affiliations</a>";?>
            
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
                        <?php echo "<a href=\"./affiliation_info.php?affiliation_id=$affiliation_id\">" ?>
                            <i class="icon-user"></i>
                            Affiliation Information      
                        </a>
                    </li>

                    <li >
                        <?php echo "<a href=\"./affiliation_paper.php?affiliation_id=$affiliation_id\">" ?>
                            <i class="icon-th-list"></i>
                            Affiliation Papers   
                        </a>
                    </li>
                    
                    <li>
                        <?php echo "<a href=\"./affiliation_charts.php?affiliation_id=$affiliation_id\">" ?>
                            <i class="icon-signal"></i>
                            Affiliation Charts
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
                   Affiliation Information               
                </h1>

                <!-- 放置affiliation相关信息 -->
                <div class="row">
                    
                    <div class="span9">
                                    
                        <div class="widget">
                            
                            <div class="widget-header">
                                <h3><?php echo $affiliation_name2;?></h3>
                            </div> <!-- /widget-header -->
                                                                
                            <div class="widget-content">
<?php   

        echo "<table>";
        echo "<tr><td width = '120'>Authors:</td><td>";
        echo ($author_count);
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

                if ($author_count>0) {              
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
                                    <th>Author Name</th>
                                    <th>Affiliations</th>
                                    <th>Publications</th>
                                    <th>Citations</th>
                                </tr>
                            </thead>  <tbody>";




            for ($i=0;$i<$author_count;$i+=1){
                $author_id = $author_list[$i][0];
                $result = mysqli_query($link, "SELECT AuthorName from authors where AuthorID='$author_id'");
                $author_name = mysqli_fetch_array($result)['AuthorName'];
                echo "<tr>";
                echo "<td>";
                echo $i+1;
                echo "</td>";
                echo "<td>".ucwords($author_name)."</td>";
                $Affresult = mysqli_query($link, "SELECT Affiliations.AffiliationID, Affiliations.AffiliationName from (select AffiliationID, count(*) as cnt from paper_author_affiliation where AuthorID='$author_id' and AffiliationID is not null group by AffiliationID order by cnt desc) as tmp inner join Affiliations on tmp.AffiliationID = Affiliations.AffiliationID");
                echo "<td>";
                if ($Affresult->num_rows!=0){
                    foreach ($Affresult as $affline){
                        $Affi_name = ucwords($affline['AffiliationName']);
                        echo "$Affi_name;\n";}
                    echo "</td>";
                    }
                echo "<td>";
                $result = mysqli_query($link, "SELECT count(PaperID) from paper_author_affiliation where AuthorID='$author_id'");
                $pub_count =  mysqli_fetch_array($result)[0];
                echo $pub_count;echo "</td>";
                echo "<td>";
                $result = mysqli_query($link, "SELECT count(*) from paper_reference2 A INNER JOIN (SELECT PaperID from paper_author_affiliation where AuthorID='$author_id') B on A.PaperID = B.PaperID");
                $ref_count =  mysqli_fetch_array($result)[0];
                echo $ref_count;
                echo "</td>";
                echo "</tr>";
                }
            echo "</tbody></table>";
            echo "  </div> <!-- /widget-content -->";
        }
        else {
                echo "<div class='widget-content'><h4>Authors not found</h4></div>";
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
