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
    <script src="../../js/echarts.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

<body>





<?php
set_time_limit(0);
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

// year-chart 需要数组years,count_year
$result = mysqli_query($link, "SELECT PaperID, count(distinct PaperID) from paper_author_affiliation where AffiliationID='$affiliation_id' group by PaperID");
$years_data = array();
$years0 = array();
while ($row = mysqli_fetch_array($result)) {
    $paper_id_ref = $row['PaperID'];
    $paper_year = mysqli_fetch_array(mysqli_query($link, "SELECT PaperPublishYear from papers where PaperID='$paper_id_ref'"));
    if(array_key_exists(intval($paper_year["PaperPublishYear"]), $years_data)){
        $years_data[intval($paper_year["PaperPublishYear"])]++;
    }else{
        $years_data[intval($paper_year["PaperPublishYear"])]=1;
    }
    if(!in_array(intval($paper_year["PaperPublishYear"]),$years0)){
        $years0[] = intval($paper_year["PaperPublishYear"]);
    }
}
for($i=min($years0);$i<=max($years0);$i++){
    if(!array_key_exists($i, $years_data)){
        $years_data[$i] = 0;
    }
}
ksort($years_data);
$years = array();
$count_year = array();
foreach ($years_data as $key => $value) {
    array_push($years,intval($key));
    array_push($count_year,$value);
}
$years = json_encode($years);
$count_year = json_encode($count_year);

//top authors 需要数组authors1, author_num1, authors2, author_num2


// 通过一次查询，获得作者和作者的被引用数的表
$author_list2 = mysqli_fetch_all(mysqli_query($link,"SELECT AuthorID, count(distinct F.PaperID) FROM (SELECT B.AuthorID, PaperID from (SELECT AuthorID from paper_author_affiliation WHERE AffiliationID = '$affiliation_id') A INNER JOIN paper_author_affiliation B on A.AuthorID = B.AuthorID GROUP BY B.AuthorID, PaperID) E INNER JOIN paper_reference2 F on E.PaperID = F.ReferenceID GROUP BY AuthorID"));
$author_list1 = mysqli_fetch_all(mysqli_query($link,"SELECT AuthorID, count(distinct PaperID) FROM (SELECT B.AuthorID, PaperID from (SELECT AuthorID from paper_author_affiliation WHERE AffiliationID = '$affiliation_id') A INNER JOIN paper_author_affiliation B on A.AuthorID = B.AuthorID GROUP BY B.AuthorID, PaperID) G GROUP BY AuthorID"));
$authors_data1 = array();
$authors_data2 = array();
$author_count = count($author_list1);
for ($i=0;$i<$author_count;$i+=1){
    $author_id1 = $author_list1[$i][0];
    $result = mysqli_query($link, "SELECT AuthorName from authors where AuthorID='$author_id1'");
    $author_name1 = mysqli_fetch_array($result)['AuthorName'];    
    $pub_count = $author_list1[$i][1];
    $authors_data1[$author_name1] = $pub_count;
}
$author_count = count($author_list2);
for ($i=0;$i<$author_count;$i+=1){
    $author_id2 = $author_list2[$i][0];
    $result = mysqli_query($link, "SELECT AuthorName from authors where AuthorID='$author_id2'");
    $author_name2 = mysqli_fetch_array($result)['AuthorName'];    
    $ref_count = $author_list2[$i][1];
    $authors_data2[$author_name2] = $ref_count;
}




// $authors_data1 = array();
// $authors_data2 = array();
// for ($i=0;$i<$author_count;$i+=1){
//     $author_id = $author_list[$i][0];
//     $result = mysqli_query($link, "SELECT AuthorName from authors where AuthorID='$author_id'");
//     $author_name = mysqli_fetch_array($result)['AuthorName'];
//     $result = mysqli_query($link, "SELECT count(PaperID) from paper_author_affiliation where AuthorID='$author_id'");
//     $pub_count =  mysqli_fetch_array($result)[0];
//     $result = mysqli_query($link, "SELECT count(*) from paper_reference2 A INNER JOIN (SELECT PaperID from paper_author_affiliation where AuthorID='$author_id') B on A.PaperID = B.PaperID");
//     $ref_count =  mysqli_fetch_array($result)[0];
//     $authors_data1[$author_name] = $pub_count;
//     $authors_data2[$author_name] = $ref_count;
//     }
arsort($authors_data1);
$all_authors1 = array_keys($authors_data1);
$authors1 = array();
$author_num1 = array();
for ($i=0; $i < min(10,sizeof($all_authors1)); $i++) { 
    array_push($authors1,$all_authors1[$i]);
    array_push($author_num1,$authors_data1[$all_authors1[$i]]);
}
arsort($authors_data2);
$all_authors2 = array_keys($authors_data2);
$authors2 = array();
$author_num2 = array();
for ($i=0; $i < min(10,sizeof($all_authors2)); $i++) { 
    array_push($authors2,$all_authors2[$i]);
    array_push($author_num2,$authors_data2[$all_authors2[$i]]);
}
$authors1 = json_encode($authors1);
$author_num1 = json_encode($author_num1);
$authors2 = json_encode($authors2);
$author_num2 = json_encode($author_num2);


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
                    
                    <li>
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
                    
                    <li class="active">
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
                    Affiliation Charts              
                </h1>

                <div class="widget">
                    
                    <div class="widget-header">
                        <h3>Chart</h3>
                    </div> <!-- /widget-header -->
                                                        
                    <div class="widget-content">
                        
                        <div id="year_chart" style="width: 400px;height:325px;"></div>
                        <div id="author_chart1" style="float: left;width: 400px;height:325px;"></div>
                        <div id="author_chart2" style="float: left;width: 400px;height:325px;"></div>
                        
                                        
                    </div> <!-- /widget-content -->
                    
                </div> <!-- /widget -->
                
            
                

                
            </div> <!-- /span9 -->
            
            
        </div> <!-- /row -->

        <!-- echarts Publish Year -->
        <script type="text/javascript">
            var myChart = echarts.init(document.getElementById('year_chart'));
            var years1 = eval(decodeURIComponent('<?php echo urlencode($years);?>'));
            var count_year1 = eval(decodeURIComponent('<?php echo urlencode($count_year);?>'));
     
            option = {
                title: {
                    text: 'Publish Year'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data:['number of papers']
                },
                xAxis: {
                    type: 'category',
                    data: years1
                },
                yAxis: {
                    type: 'value',
                    minInterval: 1
                },
                series: [
                {
                    name:'papers',
                    type: 'line',
                    data: count_year1
                },
                ]
            };
            
            myChart.setOption(option);
        </script>

        <!-- author charts authors1 author_num1-->
        <script type="text/javascript">
            var myChart = echarts.init(document.getElementById('author_chart1'));
            var a1 = eval(decodeURIComponent('<?php echo urlencode($authors1);?>'));
            var a_num1 = eval(decodeURIComponent('<?php echo urlencode($author_num1);?>'));

            for(i=0;i<a1.length;i++){
                a1[i] = a1[i].replace(/[+]/g," ");
            }
     
            option = {
                title: {
                    text: 'Top Authors By Publication'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data:['number of papers']
                },
                xAxis: {
                    type: 'category',
                    data: a1,
                    axisLabel: {
                        interval: 0,
                        rotate: 40,
                        textStyle: {
                            fontSize: 9
                        }
                    }
                },
                yAxis: {
                    type: 'value',
                    minInterval: 1
                },
                series: [
                {
                    name:'papers',
                    type: 'bar',
                    data: a_num1
                },
                ]
            };
            
            myChart.setOption(option);
        </script>

        <!-- author charts authors2 author_num2-->
        <script type="text/javascript">
            var myChart = echarts.init(document.getElementById('author_chart2'));
            var a2 = eval(decodeURIComponent('<?php echo urlencode($authors2);?>'));
            var a_num2 = eval(decodeURIComponent('<?php echo urlencode($author_num2);?>'));

            for(i=0;i<a2.length;i++){
                a2[i] = a2[i].replace(/[+]/g," ");
            }
     
            option = {
                title: {
                    text: 'Top Authors By Reference'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data:['number of papers']
                },
                xAxis: {
                    type: 'category',
                    data: a2,
                    axisLabel: {
                        interval: 0,
                        rotate: 40,
                        textStyle: {
                            fontSize: 9
                        }
                    }
                },
                yAxis: {
                    type: 'value',
                    minInterval: 1
                },
                series: [
                {
                    name:'papers',
                    type: 'bar',
                    data: a_num2
                },
                ]
            };
            
            myChart.setOption(option);
        </script>
        
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