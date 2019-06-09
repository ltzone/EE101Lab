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
    $author_id = $_GET["author_id"];
    $link = mysqli_connect("localhost:3306", 'root', '', 'FINAL');

    # 有关echarts会议的统计数据 conference_name, conference_number
    $result = mysqli_query($link,"SELECT count(*) AS ConferenceName, ConferenceName FROM (paper_author_affiliation C INNER JOIN (SELECT A.PaperID, B.ConferenceName FROM papers A INNER JOIN conferences B ON A.ConferenceID = B.ConferenceID) D ON D.PaperID = C.PaperID) WHERE C.AuthorID = '$author_id' GROUP BY ConferenceName");
    $conference_num = mysqli_fetch_all($result);
    // conference_pie & bar
    $conference_names = array();
    $conference_counts = array();
    foreach ($conference_num as $conference_num_line){
        array_push($conference_counts,intval($conference_num_line[0]));
        array_push($conference_names,$conference_num_line[1]);
    }
    $conference_names = json_encode($conference_names);
    $conference_counts = json_encode($conference_counts);
    $conference_pie = array();
    foreach ($conference_num as $conference_num_line){
        $conference_elem = array("value"=>intval($conference_num_line[0]),"name"=>$conference_num_line[1]);
        array_push($conference_pie,$conference_elem);
    }
    $conference_pie = json_encode($conference_pie);


    // paper_year, paper_number
    $result = mysqli_query($link,"SELECT count(*) AS count, PaperPublishYear FROM (papers A INNER JOIN (SELECT PaperID, AuthorID FROM paper_author_affiliation WHERE AuthorID = '$author_id') B ON A.PaperID = B.PaperID) GROUP BY PaperPublishYear ORDER BY PaperPublishYear");

    $year_data = mysqli_fetch_all($result);
    $years=array();
    $year_number=array();
    foreach ($year_data as $year_data_line) {
        array_push($years,$year_data_line[1]);
        array_push($year_number,$year_data_line[0]);
    }
    $years = json_encode($years);
    $year_number = json_encode($year_number);
    $year_pie = array();
    foreach ($year_data as $year_data_line) {
        $year_elem = array("value"=>intval($year_data_line[0]),"name"=>$year_data_line[1]);
        array_push($year_pie, $year_elem);
    }
    $year_pie = json_encode($year_pie);



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


            <?php echo "<a class=\"brand\" href=\"./author_info.php?author_id=$author_id\">Authors</a>";?>
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
                        <?php echo "<a href=\"./author_info.php?author_id=$author_id\">" ?>
                            <i class="icon-user"></i>
                            Author Information      
                        </a>
                    </li>
                    
                    <li  class="active">
                        <?php echo "<a href=\"./author_charts.php?author_id=$author_id\">" ?>
                            <i class="icon-signal"></i>
                            Author Charts
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
                   Author Charts              
                </h1>

                <div class="widget">
                    
                    <div class="widget-header">
                        <h3>Publish Year</h3>
                    </div> <!-- /widget-header -->
                                                        
                    <div class="widget-content">
                        


                            <div id="year_bar" style="float:left;width:400px;height:325px;"></div>
                            <div id="year_pie" style="float:left;width:400px;height:325px;"></div>

                        
                        
                                        

                    
                </div> <!-- /widget -->
                
                
                
                
                <div class="widget">
                    
                    <div class="widget-header">
                        <h3>Conference</h3>
                    </div> <!-- /widget-header -->
                                                        
                    <div class="widget-content">
                        
                            <div id="conf_bar" style="float:left;width:400px;height:325px;"></div>
                            <div id="conf_pie" style="float:left;width:400px;height:325px;"></div>
                        
                        
                                        

                    
                </div> <!-- /widget -->
                

                
            </div> <!-- /span9 -->
            
            
        </div> <!-- /row -->

    <!--echarts year-bar图 需要数组years，year_number-->
    <script type="text/javascript">
        var myChart = echarts.init(document.getElementById('year_bar'));

        var years1 = eval(decodeURIComponent('<?php echo urlencode($years);?>'));
        var number1 = eval(decodeURIComponent('<?php echo urlencode($year_number);?>')); 
        option = {
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
                type: 'bar',
                data: number1
            },
            ]
        };
        
        myChart.setOption(option);
    </script>


    <!--echarts author-year pie图 需要关系数组year_pie-->
    <script type="text/javascript">
        var Chartpie = echarts.init(document.getElementById('year_pie'));
        var y_pie = eval(decodeURIComponent('<?php echo urlencode($year_pie);?>'));
        var y_list = eval(decodeURIComponent('<?php echo urlencode($years);?>'));
        var pieoption = {
            // backgroundColor: '#2c343c',

            legend: {
                orient: 'vertical',
                x: 'left',
                data: y_list
            },

            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },

            visualMap: {
                show: false,
                min: 80,
                max: 600,
                inRange: {
                    colorLightness: [0.4, 0.6]
                }
            },
            series : [
                {
                    name:'Year',
                    type:'pie',
                    radius : ['50%','70%'],
                    //center: ['50%', '50%'],
                    avoidLabelOverlap: false,
                    label: {
                        normal: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            show: true,
                            textStyle: {
                                fontSize: '30',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    labelLine:{
                        normal: {
                            show: false
                        }
                    },
                    data:y_pie.sort(function (a, b) { return a.value - b.value; }),
                }
            ]
        };

        // 利用刚刚的配置制图
        Chartpie.setOption(pieoption);

    </script>




    <!--echarts author-conference bar图 需要数组conference_names，conference_counts-->
    <script type="text/javascript">
        var Chartbar = echarts.init(document.getElementById('conf_bar'));
        var conf_names = eval(decodeURIComponent('<?php echo urlencode($conference_names);?>'));
        var conf_nums = eval(decodeURIComponent('<?php echo urlencode($conference_counts);?>')); 
        var baroption = {
            tooltip: {},
            xAxis: {
                data: conf_names
            },
            yAxis: {
                minInterval: 1
            },
            series: [{
                name: 'Conferences',
                type: 'bar',
                data: conf_nums
            }]
        };
        Chartbar.setOption(baroption);
    </script>


    <!--echarts author-conference pie图 需要关系数组conference_pie-->
    <script type="text/javascript">
        var Chartpie = echarts.init(document.getElementById('conf_pie'));
        var conf_pie = eval(decodeURIComponent('<?php echo urlencode($conference_pie);?>'));
        var conf_names = eval(decodeURIComponent('<?php echo urlencode($conference_names);?>'));
        var pieoption = {
            // backgroundColor: '#2c343c',

            legend: {
                orient: 'vertical',
                x: 'left',
                data: conf_names
            },

            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },

            visualMap: {
                show: false,
                min: 80,
                max: 600,
                inRange: {
                    colorLightness: [0.4, 0.6]
                }
            },
            series : [
                {
                    name:'Conference',
                    type:'pie',
                    radius : ['50%','70%'],
                    //center: ['50%', '50%'],
                    avoidLabelOverlap: false,
                    label: {
                        normal: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            show: true,
                            textStyle: {
                                fontSize: '30',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    labelLine:{
                        normal: {
                            show: false
                        }
                    },
                    data:conf_pie.sort(function (a, b) { return a.value - b.value; }),
                }
            ]
        };

        // 利用刚刚的配置制图
        Chartpie.setOption(pieoption);

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