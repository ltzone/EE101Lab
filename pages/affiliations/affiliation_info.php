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
<script TYPE="TEXT/JAVASCRIPT" src="http://localhost/js/jquery-1.7.2.min.js"></script>

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
$result = mysqli_query($link, "SELECT PaperID, count(distinct PaperID) from paper_author_affiliation where AffiliationID= '$affiliation_id' group by PaperID");
$pub_list = mysqli_fetch_all($result);
$pub_count= count($pub_list);
# 查询本机构所有文章 （不重复）
# 
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
                    Affiliation Authors of <?php echo $affiliation_name2; ?>              
                </h1>
                
                <div class="stat-container">
                                        
                    <div class="stat-holder">                       
                        <div class="stat">                          
                            <span><?php echo $author_count;?></span>                            
                            Authors                         
                        </div> <!-- /stat -->                       
                    </div> <!-- /stat-holder -->
                    
                    <div class="stat-holder">                       
                        <div class="stat">                          
                            <span><?php
                $pubresult = mysqli_query($link, "SELECT PaperID, count(distinct PaperID) from paper_author_affiliation where AffiliationID='$affiliation_id' group by PaperID");
                echo $pubresult->num_rows;?>
                            </span>                            
                            Total of Publications                         
                        </div> <!-- /stat -->                       
                    </div> <!-- /stat-holder -->
                    
                    <div class="stat-holder">                       
                        <div class="stat">                          
                            <span><?php

                $result =mysqli_query($link,"SELECT count(*) from (SELECT PaperID, count(distinct PaperID) from paper_author_affiliation where AffiliationID='$affiliation_id' group by PaperID) A INNER JOIN paper_reference2 B on A.PaperID = B.PaperID");
                $result = mysqli_fetch_all($result)[0][0];
                echo $result;
                ?>

                            </span>                         
                            Total of References                          
                        </div> <!-- /stat -->                       
                    </div> <!-- /stat-holder -->

                    
                </div> <!-- /stat-container -->

                <div class="row">
                    <div class="span9">
                        <div class="widget">

<?php


    function get_author_name($link,$author_id){
        $res = mysqli_query($link,"SELECT AuthorName from authors where AuthorID='$author_id'");
        if ($res) {
                return ucwords(mysqli_fetch_array($res)['AuthorName']);
            }
        else return ('Author Not Found');
    }

    function get_affiliation_name($link,$affiliation_id){
        $res = mysqli_query($link,"SELECT AffiliationName from affiliations where AffiliationID='$affiliation_id'");
        if ($res) {
                return ucwords(mysqli_fetch_array($res)['AffiliationName']);
            }
        else return ('Affiliation Not Found');
    }



                if ($author_count>0) {              
                echo "
                <div class=\"widget widget-table\">
                                        
                    <div class=\"widget-header\">
                        <i class=\"icon-th-list\"></i>
                        <h3>Author Table</h3>
                    </div> <!-- /widget-header -->
                    
                    <div class=\"widget-content\">
                    <div id=\"1\"style=\"display:none\">
                        <table class=\"table table-striped table-bordered\">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Author Name</th>
                                    <th>Affiliations</th>
                                    <th>Publications</th>";
     //                               <th>Citations</th>
echo"                                </tr>
                            </thead>  <tbody>";

# result 查询机构所有文章

                            $result = mysqli_query($link, "SELECT B.AuthorID, PaperID, AffiliationID from (SELECT AuthorID from paper_author_affiliation WHERE AffiliationID = '$affiliation_id') A INNER JOIN paper_author_affiliation B on A.AuthorID = B.AuthorID GROUP BY B.AuthorID, PaperID");
                            // 通过一次查询，获得了一张包含该机构所有作者+作者所有著作+所在会议的表
                            $result = (mysqli_fetch_all($result));
                            $result_rows=count($result);
                            $last_author = $result[0][0];
                            $last_affiliation = array();

                            // 对第一行信息的操作
                            $idx = 1;$page=1;
                            echo "<tr><td>$idx</td><td>"."<a href=\"../authors/author_info.php?author_id=".$result[0][0]."\">".get_author_name($link,$result[0][0])."</a></td>";
                            echo "<td>";
                            if ($result[0][2]!=NULL) {
                                array_push($last_affiliation,$result[0][2]);
                            echo "<a href=\"../affiliations/affiliation_info.php?affiliation_id=".$result[0][2]."\">".get_affiliation_name($link,$result[0][2])."</a>;\n";}

                            $pub_count=1;



                            for ($i=1;$i<$result_rows;$i+=1){ // 从第二行开始
                                if ($result[$i][0]!=$last_author) {$idx+=1; $last_author=$result[$i][0];$last_affiliation=array();// 另一位作者开始了
                                    echo "</td>";
                                    echo "<td>";echo $pub_count;


                                     echo "</td></tr>";$pub_count=1;
                                     if($idx%10==1&&$idx>1){echo "</tbody></table>";echo"Page: ";echo$page;echo"<br>";echo"         Total of Pages: ";echo(int)(($author_count-1)/10+1);$page+=1;echo"</div>";}
                //输出上一位作者的count
                                    if($idx%10==1){echo"<div id=\"$page\"style=\"display:none\">";
                  
                       echo" <table class=\"table table-striped table-bordered\">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Author Name</th>
                                    <th>Affiliations</th>
                                    <th>Publications</th>";
     //                               <th>Citations</th>
echo"                                </tr>
                            </thead>  <tbody>";}
                                    // 新作者的信息
                                    echo "<tr><td>$idx</td><td>"."<a href=\"../authors/author_info.php?author_id=".$result[$i][0]."\">".get_author_name($link,$result[$i][0])."</a></td>";
                                    echo "<td>";
                                    if ($result[$i][2]!=NULL) {
                                        array_push($last_affiliation,$result[$i][2]);
                                        echo "<a href=\"../affiliations/affiliation_info.php?affiliation_id=".$result[$i][2]."\">".get_affiliation_name($link,$result[$i][2])."</a>;\n";}


                                }
                                else {
                                    if (!(in_array($result[$i][2],$last_affiliation)) AND $result[$i][2]!=NULL) {
                                    array_push($last_affiliation,$result[$i][2]);
                                    echo "<a href=\"../affiliations/affiliation_info.php?affiliation_id=".$result[$i][2]."\">".get_affiliation_name($link,$result[$i][2])."</a>;\n";}
                                    $pub_count+=1;
                                    

                                    // 还是这位作者



                                }




                                                               
                                }
                            echo "<td>$pub_count</td></tr>";
echo "</tbody></table>";echo"Page: ";echo$page;echo"<br>";echo"        Total of Pages: ";echo(int)(($author_count-1)/10+1);echo"</div>";







                            



//             for ($i=0;$i<$author_count;$i+=1){
//                 $author_id = $author_list[$i][0];
//                 $result = mysqli_query($link, "SELECT AuthorName from authors where AuthorID='$author_id'");
//                 $author_name = mysqli_fetch_array($result)['AuthorName'];
//                 echo "<tr>";
//                 echo "<td>";
//                 echo $i+1;
//                 echo "</td>";
//                 echo "<td>"."<a href=\"../authors/author_info.php?author_id=$author_id\">".ucwords($author_name)."</a></td>";
//                 $Affresult = mysqli_query($link, "SELECT Affiliations.AffiliationID, Affiliations.AffiliationName from (select AffiliationID, count(*) as cnt from paper_author_affiliation where AuthorID='$author_id' and AffiliationID is not null group by AffiliationID order by cnt desc) as tmp inner join Affiliations on tmp.AffiliationID = Affiliations.AffiliationID");
//                 echo "<td>";
//                 if ($Affresult->num_rows!=0){
//                     foreach ($Affresult as $affline){
//                         $Affi_id = $affline['AffiliationID'];
//                         $Affi_name = ucwords($affline['AffiliationName']);
//                         echo "<a href=\"../affiliations/affiliation_info.php?affiliation_id=$Affi_id\">$Affi_name</a>;\n";}
//                     echo "</td>";
//                     }
//                 echo "<td>";
//                 $result = mysqli_query($link, "SELECT count(PaperID) from paper_author_affiliation where AuthorID='$author_id'");
//                 $pub_count =  mysqli_fetch_array($result)[0];
//                 echo $pub_count;echo "</td>";
//     //            echo "<td>";
// //                $result = mysqli_query($link, "SELECT count(*) from paper_reference2 A INNER JOIN (SELECT PaperID from paper_author_affiliation where AuthorID='$author_id') B on A.PaperID = B.PaperID");
// //                $ref_count =  mysqli_fetch_array($result)[0];
//  //               echo $ref_count;
//   //              echo "</td>";
//                 echo "</tr>";
//                 }
            $totalpage=(int)(($author_count-1)/10+1);
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
        
    }if(now>=1&&now<=2){document.getElementById("b4").style.display="";
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
        
    }if(now>=1&&now<=2){document.getElementById(\'b4\').style.display="";
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
