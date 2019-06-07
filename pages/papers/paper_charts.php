<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Dashboard - Bootstrap Admin</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />    
    <meta http-equiv="content-type" content="text/html; charset=gbk" />
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
            <?php echo "<a class=\"brand\" href=\"./paper_info.php?paper_id=$paper_id\">"; ?>
            Papers</a>
            
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
            <div class="span3">
                
                
                <ul id="main-nav" class="nav nav-tabs nav-stacked">
                    
                    <li>
                        <?php echo "<a href=\"./paper_info.php?paper_id=$paper_id\">" ?>
                            <i class="icon-user"></i>
                            Paper Information      
                        </a>
                    </li>
                    
                    <li class="active">
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
                            <span class="label label-warning pull-right">5</span>
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
            
            <div class="span9">
                
                <h1 class="page-title">
                    <i class="icon-signal"></i>
                    Charts                  
                </h1>
                
                
                
                
                <div class="widget">
                    
                    <div class="widget-header">
                        <h3>Relation Chart</h3>
                    </div> <!-- /widget-header -->
                                                        
                    <div class="widget-content">
                        
                        <div id="main" class="chart-holder" style="height:600px"></div> <!-- /area-chart -->

<?php

    function get_paper_name($link,$paper_id){
        $res = mysqli_query($link,"SELECT Title from papers where PaperID='$paper_id'");
        if ($res) {
                return ucwords(mysqli_fetch_array($res)['Title']);
            }
        else return ('Paper Not Found');
    }
    $paper_id = $_GET["paper_id"];
    $result = mysqli_query($link, "SELECT paperID,referenceID FROM paper_reference2 WHERE paperID = '$paper_id'");

    $links = [];
    $nodes = [array('category'=>0,'name'=> $paper_id,'value'=>20, 'label'=> get_paper_name($link,$paper_id))];
    $node_records = array($paper_id);


    $connect = mysqli_fetch_all($result);
    foreach ($connect as $connect_elem){
        $link_item = array('source'=>$connect_elem[1],  'target'=> $connect_elem[0] ,'name'=>'reference',
                        'label'=>get_paper_name($link,$connect_elem[1])."<br>is referenced by <br>".get_paper_name($link,$connect_elem[0]));
        if (!(in_array($connect_elem[1],$node_records))){
            $node_item = array('category'=>1, 'name'=> $connect_elem[1], 'value'=>16, 'label'=> get_paper_name($link,$connect_elem[1]));
            array_push($node_records,$connect_elem[1]);
            array_push($nodes,$node_item);
        }
        array_push($links,$link_item);
    }

    $newconnect =array();
    for ($depth=2;$depth<4;$depth+=1){
        foreach ($connect as $connect_elem){
            $result = mysqli_query($link, "SELECT paperID,referenceID FROM paper_reference2 WHERE paperID = '$connect_elem[1]'");
            $connection = mysqli_fetch_all($result);
            $newconnect = array_merge($newconnect,$connection);
            foreach ($connection as $connection_elem){
                $link_item = array('source'=>$connection_elem[1],  'target'=> $connection_elem[0],'name'=>'reference',
                                    'label'=>get_paper_name($link,$connect_elem[1])."<br>is referenced by <br>".get_paper_name($link,$connect_elem[0]));
                if (!(in_array($connection_elem[1],$node_records))){
                    $node_item = array('category'=>$depth, 'name'=> $connection_elem[1], 'value'=>(20-4*$depth), 'label'=> get_paper_name($link,$connection_elem[1]));
                    array_push($node_records,$connection_elem[1]);
                    array_push($nodes,$node_item);
                }
                array_push($links,$link_item);
            }
        }
        $connect = $newconnect;
        $newconnect = array();
    }

    $nodes = json_encode($nodes);
    $links = json_encode($links);

     ?>
                        
                                        
                    </div> <!-- /widget-content -->
                    
                </div> <!-- /widget -->
                
                
 
                
                
                
            </div> <!-- /span9 -->
            
            
        </div> <!-- /row -->
        
    </div> <!-- /container -->
    
</div> <!-- /content -->
                    
    

    

<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="../../js/jquery-1.7.2.min.js"></script>
<script src="../../js/excanvas.min.js"></script>
<script src="../../js/jquery.flot.js"></script>
<script src="../../js/jquery.flot.pie.js"></script>
<script src="../../js/jquery.flot.orderBars.js"></script>
<script src="../../js/jquery.flot.resize.js"></script>

    <script src="../../js/echarts.js"></script>

<script src="../../js/bootstrap.js"></script>

<!-- 图表生成js
==================================================== -->

<script>


    function getOption(graphInfo){
    //给节点设置样式
    graphInfo.nodes.forEach(function (node) {
        //node.itemStyle = null;//
        //node.symbolSize = node.size;//强制指定节点的大小   
        // Use random x, y
        node.x = node.y = null;
        node.draggable = true;
    });
     
     
    title=graphInfo['title']
    nodes=graphInfo['nodes']
    links=graphInfo['links']
    categories=graphInfo['categories']
     
    //设置option样式
    option = {
        title : {
            text:title,
            x:'right',
            y:'bottom'
        },
        tooltip : {
            trigger: 'item',
            formatter:
                function (a){
                    return a["data"]['label'].split('+').join(' ')
                }

            // ['{label}','{c} : {d} : {e} ','{a} : {b.label}','sdasda'].join('\n'),
            //formatter: function(params){//触发之后返回的参数，这个函数是关键
            //if (params.data.category !=undefined) //如果触发节点
            //   window.open("http://www.baidu.com")
            //}
        },
        color:['#EE6A50','#4F94CD','#B3EE3A','#DAA520'],
        toolbox: {
            show : true,
            feature : {
                restore : {show: true},
                magicType: {show: true, type: ['force', 'chord']},
                saveAsImage : {show: true}
            }
        },
        legend: {
            x: 'left',
            data: categories.map(function (a) {//显示策略
                return a.name;
            })
        },
        series : [
            {
                type:'graph',
                layout:'force',
                name : title,
                ribbonType: false,
                categories : categories,

                roam:true,
                focusNodeAdjacency:true,
                itemStyle: {
                    normal: {
                        label: {
                            show: false,
                            textStyle: {
                                color: '#333'
                            }
                        },
                        nodeStyle : {
                            brushType : 'both',
                            borderColor : 'rgba(255,215,0,0.4)',
                            borderWidth : 1
                        },
                        linkStyle: {
                            type: 'curve'
                        }
                    },
                    emphasis: {
                        label: {
                            show: false
                            // textStyle: null      // 默认使用全局文本样式，详见TEXTSTYLE
                        },
                        nodeStyle : {
                            //r: 30
                        },
                        linkStyle : {}
                    }
                },
                force: {repulsion:80},
                useWorker: false,
                minRadius : 15,
                maxRadius : 25,
                gravity: 1.1,
                scaling: 1.1,
                nodes:nodes,
                links:links
            }
        ]
    };
    return option   
    }
    function createGraph(myChart,mygraph){
    //设置option样式
    option=getOption(mygraph)
    //使用Option填充图形
    myChart.setOption(option);
    //点可以跳转页面
    myChart.on('click', function (params) {
                var data=params.name.slice(0,8)
                //点没有source属性
                if(data.source==undefined){
                    nodeName=params.name
                    window.open("./paper_info.php?paper_id="+data)
                }
     
    });
    //myChart.hideLoading();
    }

    
    var myChart = echarts.init(document.getElementById('main'), 'macarons');
  //创建Nodes
    nodes=eval(decodeURIComponent('<?php echo urlencode($nodes);?>'));
    //创建links
    links=eval(decodeURIComponent('<?php echo urlencode($links);?>'));
    categoryArray=[{name:"本文"},{name:"一级引用"},{name:"二级引用"},{name:"三级引用"}];
    jsondata={"categories":categoryArray,"nodes":nodes,"links":links}  ;
    //数据格式为Json格式
    createGraph(myChart,jsondata);



</script>



  </body>
</html>
