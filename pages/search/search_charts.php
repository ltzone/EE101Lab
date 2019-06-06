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
    <script src="../../js/echarts.js"></script>
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
        }
        //收集echarts 统计数据
        //chart1: years, count_year
        //chart2: conferences, count_conference
        $years_data = array();

        $years0 = array();
        $conferences_data = array();
        $all_paper = $result["response"]["docs"];
        //var_dump($all_paper);
        $authors_data = array();
        foreach ($all_paper as $paper_data) {
            if(array_key_exists(intval($paper_data["Year"]), $years_data)){
                $years_data[intval($paper_data["Year"])]++;
            }else{
                $years_data[intval($paper_data["Year"])]=1;

            }
            if(array_key_exists($paper_data["ConferenceName"], $conferences_data)){
                $conferences_data[$paper_data["ConferenceName"]]++;
            }else{
                $conferences_data[$paper_data["ConferenceName"]]=1;
            }

            if(!in_array(intval($paper_data["Year"]), $years0)){
                $years0[] = intval($paper_data["Year"]);
            }
            foreach ($paper_data["AuthorName"] as $authorName) {
                if(array_key_exists($authorName, $authors_data)){
                    $authors_data[$authorName]++;
                }else{
                    $authors_data[$authorName] = 1;
                }
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

        ksort($conferences_data);
        $conferences = array();
        $count_conference = array();
        foreach ($conferences_data as $key => $value) {
            array_push($conferences,$key);
            array_push($count_conference,$value);
        }

        arsort($authors_data);
        $all_authors = array_keys($authors_data);
        $authors = array();
        $author_num = array();
        for ($i=0; $i < min(10,sizeof($all_authors)); $i++) { 
            array_push($authors,$all_authors[$i]);
            array_push($author_num,$authors_data[$all_authors[$i]]);
        }

        $years = json_encode($years);
        $count_year = json_encode($count_year);
        $conferences = json_encode($conferences);
        $count_conference = json_encode($count_conference);
        $authors = json_encode($authors);
        $author_num = json_encode($author_num);

    ?>
    
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
                    
                                                        
                    <div class="widget-content">
                        
                        <div id="year_chart" style="float: left;width: 400px;height:325px;"></div>
                        <div id="conference_chart" style="float: left;width: 400px;height:325px;"></div>
                        <div id="author_chart" style="float: left;width: 400px;height:325px;"></div>
                        
                                        
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

        <!--echarts Conference Source -->
        <script type="text/javascript">
            var myChart = echarts.init(document.getElementById('conference_chart'));
            var conferences1 = eval(decodeURIComponent('<?php echo urlencode($conferences);?>'));
            var count_conference1 = eval(decodeURIComponent('<?php echo urlencode($count_conference);?>'));
     
            option = {
                title: {
                    text: 'Conference'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data:['number of papers']
                },
                xAxis: {
                    type: 'category',
                    data: conferences1,
                    axisLabel: {
                        interval:0,
                        rotate: 40
                    }
                },
                yAxis: {
                    type: 'value'
                },
                series: [
                {
                    name:'papers',
                    type: 'bar',
                    data: count_conference1
                },
                ]
            };
            
            myChart.setOption(option);
        </script>


        <!-- author charts authors author_num-->
        <script type="text/javascript">
            var myChart = echarts.init(document.getElementById('author_chart'));
            var authors1 = eval(decodeURIComponent('<?php echo urlencode($authors);?>'));
            var author_num1 = eval(decodeURIComponent('<?php echo urlencode($author_num);?>'));

            for(i=0;i<authors1.length;i++){
                authors1[i] = authors1[i].replace(/[+]/g," ");
            }
     
            option = {
                title: {
                    text: 'Top Authors'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data:['number of papers']
                },
                xAxis: {
                    type: 'category',
                    data: authors1,
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
                    data: author_num1
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