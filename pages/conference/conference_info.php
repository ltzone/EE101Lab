<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Conferences - Conferences Infomation</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />    
    
    <link href="../../css/bootstrap.min.css" rel="stylesheet" />
    <link href="../../css/bootstrap-responsive.min.css" rel="stylesheet" />
    
    
    <link href="../../css/font-awesome.css" rel="stylesheet" />
    
    <link href="../../css/adminia.css" rel="stylesheet" /> 
    <link href="../../css/adminia-responsive.css" rel="stylesheet" /> 
    <link href="../../css/pages/dashboard.css" rel="stylesheet" /> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


</head>

<body>
<script TYPE="TEXT/JAVASCRIPT" src="http://localhost/js/jquery-1.7.2.min.js"></script>
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
                   Conference Information for      <?php echo $conference_name;?>          
                </h1>

                <!-- 放置paper相关信息 -->
                <div class="row">
                    
                    <div class="span9">
                                    


                <div class="stat-container">
                                        
                    <div class="stat-holder">                       
                        <div class="stat">                          
                            <span><?php 

                $result =mysqli_query($link,"SELECT AuthorID, count(distinct AuthorID) from (SELECT PaperID from papers where ConferenceID='$conference_id') A INNER JOIN paper_author_affiliation B on A.PaperID = B.PaperID GROUP BY AuthorID");
                $result = mysqli_fetch_all($result);
                echo count($result);

                            ?></span>                            
                            Total of Authors                       
                        </div> <!-- /stat -->                       
                    </div> <!-- /stat-holder -->
                    
                    <div class="stat-holder">                       
                        <div class="stat">                          
                            <span><?php
                $pubresult = mysqli_query($link, "SELECT count(*) from papers where ConferenceID='$conference_id'");
                $pubresult = mysqli_fetch_all($pubresult)[0][0];
                echo $pubresult; ?>
                            </span>                            
                            Total of Papers                        
                        </div> <!-- /stat -->                       
                    </div> <!-- /stat-holder -->
                    
                    <div class="stat-holder">                       
                        <div class="stat">                          
                            <span><?php

                $result =mysqli_query($link,"SELECT count(*) from (SELECT PaperID, count(distinct PaperID) from papers where ConferenceID='$conference_id' group by PaperID) A INNER JOIN paper_reference2 B on A.PaperID = B.PaperID");
                $result = mysqli_fetch_all($result)[0][0];
                echo $result;
                ?>

                            </span>                         
                            Total of References                          
                        </div> <!-- /stat -->                       
                    </div> <!-- /stat-holder -->

                    
                </div> <!-- /stat-container -->

                    </div> <!-- /span5 -->
                    
                </div> <!-- /row -->        

                <div class="row">
                    <div class="span9">
                        <div class="widget">

<?php
                    $result = mysqli_query($link, "SELECT PaperID  from papers where ConferenceID='$conference_id'");
                    $num_results = $result->num_rows;
                if ($result->num_rows) {              
                


echo "
                <div class=\"widget widget-table\">
                                        
                    <div class=\"widget-header\">
                        <i class=\"icon-th-list\"></i>
                        <h3>Paper Table</h3>
                    </div> <!-- /widget-header -->
                    
                    <div class=\"widget-content\">";
            $idx = 1;
            $page=1;

            while ($row = mysqli_fetch_array($result)) {
                
                if($idx%10==1){echo"<div id=\"$page\"style=\"display:none\">";
                  
                       echo" <table class=\"table table-striped table-bordered\">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Paper Name</th>
                                    <th>Authors</th>
                                    <th>Conference</th>
                                    <th>Year</th>
                                </tr>
                            </thead>  <tbody>";}

                $paper_id_ref = $row['PaperID'];
                $paper_info = mysqli_fetch_array(mysqli_query($link, "SELECT Title, ConferenceID, PaperPublishYear from papers where PaperID='$paper_id_ref'"));
                if ($paper_info){
                    $paper_title = $paper_info['Title'];
                    $conf_id = $paper_info['ConferenceID'];
                    $yr = $paper_info['PaperPublishYear'];
                    $paper_title2 = ucwords($paper_title);
                    echo "<tr><td>$idx</td><td>";
                    echo "<a href=\"../papers/paper_info.php?paper_id=$paper_id_ref\"><h3>$paper_title2</h3></a>";
                    echo "</td>";
                    echo "<td>";
                    $author_info = mysqli_query($link, "SELECT A.AuthorID, AuthorName FROM paper_author_affiliation A LEFT JOIN authors B ON A.AuthorID = B.AuthorID WHERE PaperID = '$paper_id_ref' ORDER BY AuthorSequence ASC");

                    while ($author_row = mysqli_fetch_array($author_info)){
                        $author_name = $author_row['AuthorName'];
                        $author_another_id = $author_row['AuthorID'];
                        $author_name2 = ucwords($author_name);
                        $author_another_id2 = ucwords($author_another_id);
                        echo "<a href=\"../authors/author_info.php?page=1&author_id=$author_another_id2\">$author_name2</a>";
                        echo "; ";
                    }
                    echo "</td><td>";

                    $conference_row = mysqli_fetch_array(mysqli_query($link, "SELECT ConferenceName from conferences WHERE ConferenceID = '$conf_id'"));
                    $conference_name = $conference_row['ConferenceName'];
                    $conference_name2 = ucwords($conference_name);
                    echo "<a href=\"conference_info.php?page=1&conference_id=$conf_id\">$conference_name2</a>";
                    echo "</td><td>";
                    echo $yr; echo "</td>";


                    $idx +=1;

                }if($idx%10==1&&$idx>1){echo "</tbody></table>";echo"Page: ";echo$page;echo"<br>";echo"         Total of Pages: ";echo(int)(($num_results-1)/10+1);$page+=1;echo"</div>";}
                else if ($idx==$num_results+1){echo "</tbody></table>";echo"Page: ";echo$page;echo"<br>";echo"        Total of Pages: ";echo(int)(($num_results-1)/10+1);echo"</div>";}
            }
    $totalpage=(int)(($num_results-1)/10+1);
            //分页
            echo'
<script type="text/javascript">
var now=1; 
var totalpage =' .$totalpage.';


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
       }else document.getElementById((totalpage).toString()).style.display="";
for (var it=1;it<=totalpage;++it){
        var itit="b"+it.toString();
        if(it<(now-2)||it>(now+2))document.getElementById(itit).style.display="none";
        else document.getElementById(itit).style.display="";
        
    }if(totalpage>=1&&totalpage<=5)document.getElementById("b1").style.display=""; 
    else if(totalpage>=2&&totalpage<=5)document.getElementById("b2").style.display="";else if(totalpage>=3&&totalpage<=5)document.getElementById("b3").style.display="";else if(totalpage>=4&&totalpage<=5)document.getElementById("b4").style.display="";else if(
    totalpage==5)document.getElementById("b5").style.display="" ;else
    if(now>=1&&now<=2){document.getElementById("b4").style.display="";
     document.getElementById("b5").style.display="";}
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
        var itit=\'b\'+it.toString();
        if(it<(now-2)||it>(now+2))document.getElementById(itit).style.display="none";
        else document.getElementById(itit).style.display="";
        
    }if(totalpage>=1&&totalpage<=5)document.getElementById("b1").style.display=""; 
    else if(totalpage>=2&&totalpage<=5)document.getElementById("b2").style.display="";else if(totalpage>=3&&totalpage<=5)document.getElementById("b3").style.display="";else if(totalpage>=4&&totalpage<=5)document.getElementById("b4").style.display="";else if(
    totalpage==5)document.getElementById("b5").style.display="" ;else
    if(now>=1&&now<=2){document.getElementById(\'b4\').style.display="";
     document.getElementById(\'b5\').style.display="";}
     if(now>totalpage-2)document.getElementById(\'b\'+(totalpage-4).toString()).style.display="";   
     if(now>totalpage-1)document.getElementById(\'b\'+(totalpage-3).toString()).style.display="";  

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
        else document.getElementById(itit).style.display="";
        
    }
        });});
</script>';
echo"<div>";
echo"<style type=\"text/css\">
.button {
    
    display: inline-block;
}</style>";
echo "<div class=\"button\"><button type=\"button\" class=\"btn btn-default\"id=\"but1\">First</button></div>";

echo "<div class=\"button\"><button type=\"button\" class=\"btn btn-default\"id=\"but3\">Previous</button></div>";

echo"     ";
for($j=1;$j<=$totalpage;++$j){
    $jj=(string)$j;
    echo "<div class =\"button\"><button type=\"button\" class=\"btn btn-default\"id=\"b$jj\">$jj</button></div>";}
    echo"     ";
echo "<div class=\"button\"><button type=\"button\" class=\"btn btn-default\"id=\"but2\">Next</button></div>";
echo "<div class=\"button\"><button type=\"button\" class=\"btn btn-default\"id=\"but4\">Last</button></div>";echo"</div>";

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
        
    }if(totalpage>=1&&totalpage<=5)document.getElementById('b1').style.display=\"\"; 
    else if(totalpage>=2&&totalpage<=5)document.getElementById('b2').style.display=\"\";else if(totalpage>=3&&totalpage<=5)document.getElementById('b3').style.display=\"\";else if(totalpage>=4&&totalpage<=5)document.getElementById('b4').style.display=\"\";else if(
    totalpage==5)document.getElementById('b5').style.display=\"\" ;else
    if(now>=1&&now<=2){document.getElementById('b4').style.display=\"\";
     document.getElementById('b5').style.display=\"\";}
     if(now>totalpage-2)document.getElementById('b'+(totalpage-4).toString()).style.display=\"\";   
     if(now>totalpage-1)document.getElementById('b'+(totalpage-3).toString()).style.display=\"\";  
    }}
 
</script>";


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
