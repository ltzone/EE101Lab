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
$paper_id = $_GET["paper_id"];
$link = mysqli_connect("localhost:3306", 'root', '', 'FINAL');
$result = mysqli_query($link, "SELECT Title from papers where PaperID='$paper_id'");
if ($result) {
    $paper_name = mysqli_fetch_array($result)['Title'];
    $paper_name2 = ucwords($paper_name);
}
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
            
            <?php echo "<a class=\"brand\" href=\"./paper_info.php?paper_id=$paper_id\">";
             ?>Papers</a>
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
                        <?php echo "<a href=\"./paper_info.php?paper_id=$paper_id\">" ?>
                            <i class="icon-user"></i>
                            Paper Information      
                        </a>
                    </li>
                    
                    <li>
                        <?php echo "<a href=\"./paper_charts.php?paper_id=$paper_id\">" ?>
                            <i class="icon-signal"></i>
                            Paper Charts
                        </a>
                    </li>
                    
                    <li>
                        <?php echo "<a href=\"./paper_reference.php?paper_id=$paper_id\">" ?>
                            <i class="icon-th-list"></i>
                            References    
                        </a>
                    </li>
                    
                    <li>
                        <?php echo "<a href=\"./paper_citation.php?paper_id=$paper_id\">" ?>
                            <i class="icon-th-large"></i>
                            Citations
                        </a>
                    </li>
                    
                    <li>
                        <?php echo "<a href=\"./paper_recommendation.php?paper_id=$paper_id\">" ?>
                            <i class="icon-pushpin"></i>
                            Recommendations
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
                             <?php echo $paper_name2;?>         
                </h1>

                <!-- 放置paper相关信息 -->
                <div class="row">
                    
                    <div class="span4">
                                    
                        <div class="widget">
                            
                            <div class="widget-header">
                                
                                <h3>Paper Information </h3>
                            </div> <!-- /widget-header -->
                                                                
                            <div class="widget-content">

                               <?php 
           $result = mysqli_query($link, "SELECT PaperID from paper_author_affiliation where PaperID='$paper_id'");
        
            $row = mysqli_fetch_array($result);
                echo "<tr>";
                $paper_id = $row['PaperID'];
                $paper_info = mysqli_fetch_array(mysqli_query($link, "SELECT ConferenceID , PaperPublishYear from papers where PaperID='$paper_id'"));
                if ($paper_info){
                    echo "<table>";
                    echo "<tr><td width = '120'><b> Conference: </b></td><td>";
                    
                    $conference_id = $paper_info['ConferenceID'];
                    $year= $paper_info['PaperPublishYear'];

                }

                
                    $conference_row = mysqli_fetch_array(mysqli_query($link, "SELECT ConferenceName from conferences WHERE ConferenceID = '$conference_id'"));
                    $conference_name = $conference_row['ConferenceName'];
                    $conference_name2 = ucwords($conference_name);
                    echo "<a href=\"..\conference\conference_info.php?page=1&conference_id=$conference_id\">$conference_name2; </a>";
                    echo "</td>";
                    echo "</tr>";
                    echo "<tr><td><b> Year: </b></td><td>";
                    echo $year;
                    echo "</td></tr>";

                    echo "<tr><td><b> Cited: </b></td><td>";
                    $cited_count = mysqli_fetch_array(mysqli_query($link,"SELECT count(*) FROM paper_reference2 WHERE ReferenceID = '$paper_id';"));
                    echo ($cited_count[0]);
                    echo "</td></tr>";


                    echo "<tr><td><b> Reference: </b></td><td>";
                    $refer_count = mysqli_fetch_array(mysqli_query($link,"SELECT count(*) FROM paper_reference2 WHERE PaperID = '$paper_id';"));
                    echo ($refer_count[0]);
                    echo "</td></tr>";

                    echo "<tr><td><b> Details: </b></td><td>";
                    echo "<a href='https://www.acemap.info/paper?paperID=$paper_id'>See Acemap</a>";
                    echo "</td></tr>";
                    

            echo "</table>";
            ?>
                            </div> <!-- /widget-content -->
                            
                        </div> <!-- /widget -->
                        
                    </div> <!-- /span5 -->
        
                    <div class="span5">
                        
                        <div class="widget">
               <div class="widget widget-table">
                                        
                    <div class="widget-header">
                        <i class="icon-th-list"></i>
                        <h3>Authors</h3>
                    </div> <!-- /widget-header -->
                    
                    <div class="widget-content">
                    
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Author Name</th>
                                    <th>Affiliation</th>
                                </tr>
                            </thead>
                            
                            <tbody>
<?php               $author_info = mysqli_query($link, "SELECT A.AuthorID, AuthorName,AffiliationID FROM paper_author_affiliation A LEFT JOIN authors B ON A.AuthorID = B.AuthorID WHERE PaperID = '$paper_id' ORDER BY AuthorSequence ASC");
                    $idx = 1;
                    while ($author_row = mysqli_fetch_array($author_info)){
                        echo "<tr>"; echo "<td>$idx</td>";
                        $author_name = $author_row['AuthorName'];
                        $author_another_id = $author_row['AuthorID'];
                        echo "<td>"."<a href=\"../authors/author_info.php?author_id=$author_another_id\">".ucwords($author_name)."</a></td>";
                        $author_aff = $author_row['AffiliationID'];
                        
                        $Affresult = mysqli_query($link, "SELECT Affiliations.AffiliationID, Affiliations.AffiliationName from (select AffiliationID, count(*) as cnt from paper_author_affiliation where AuthorID='$author_another_id' and AffiliationID is not null group by AffiliationID order by cnt desc) as tmp inner join Affiliations on tmp.AffiliationID = Affiliations.AffiliationID");
                        echo "<td>";
                        if ($Affresult->num_rows!=0){
                            foreach ($Affresult as $affline){
                                $Affi_id = $affline['AffiliationID'];
                                $Affi_name = ucwords($affline['AffiliationName']);
                                echo "<a href=\"../affiliations/affiliation_info.php?affiliation_id=$Affi_id\">$Affi_name</a>;\n";}
                            echo "</td>";
                            }
                        echo "</tr>";
                        $idx += 1;
                    }

                        ?>

                            </tbody>
                        </table>
                    
                    </div> <!-- /widget-content -->
                    
                </div> <!-- /widget -->
                            </div> <!-- /widget-content -->
                            
                        </div> <!-- /widget -->
                    </div> <!-- /span4 -->
                    
                </div> <!-- /row -->        
                <!-- 放置作者信息 -->
 
                
          
                
                

                
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
