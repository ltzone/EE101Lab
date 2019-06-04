function conference_graph(conf_nums,conf_names){
// 基于准备好的dom，初始化echarts实例

var Chartbar = echarts.init(document.getElementById('bar'));

// 指定图表的配置项和数据
var baroption = {
    title: {
        text: 'Conferences' 
    },
    tooltip: {},
    legend: {
        data:['Conferences']
    },
    xAxis: {
        data: conf_names
    },
    yAxis: {},
    series: [{
        name: 'Conferences',
        type: 'bar',
        data: conf_nums
    }]
};

// 利用刚刚的配置制图
Chartbar.setOption(baroption);

};


function conference_pie(conf_pie){
// 基于准备好的dom，初始化echarts实例

var Chartpie = echarts.init(document.getElementById('pie'));

// 指定图表的配置项和数据
var pieoption = {
    backgroundColor: '#2c343c',

    title: {
        text: 'Customized Pie',
        left: 'center',
        top: 20,
        textStyle: {
            color: '#ccc'
        }
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
            name:'访问来源',
            type:'pie',
            radius : '55%',
            center: ['50%', '50%'],
            data:conf_pie.sort(function (a, b) { return a.value - b.value; }),
            roseType: 'radius',
            label: {
                normal: {
                    textStyle: {
                        color: 'rgba(255, 255, 255, 0.3)'
                    }
                }
            },
            labelLine: {
                normal: {
                    lineStyle: {
                        color: 'rgba(255, 255, 255, 0.3)'
                    },
                    smooth: 0.2,
                    length: 10,
                    length2: 20
                }
            },
            itemStyle: {
                normal: {
                    color: '#c23531',
                    shadowBlur: 200,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            },

            animationType: 'scale',
            animationEasing: 'elasticOut',
            animationDelay: function (idx) {
                return Math.random() * 200;
            }
        }
    ]
};

// 利用刚刚的配置制图
Chartpie.setOption(pieoption);

};



function relation_chart(mynodes,mylinks){
    
    var myChart = echarts.init(document.getElementById('main'), 'macarons');
  //创建Nodes
    nodes=mynodes;
    //创建links
    links=mylinks;
    categoryArray=[{name:"本文"},{name:"一级引用"},{name:"二级引用"},{name:"三级引用"}]
    jsondata={"categories":categoryArray,"nodes":mynodes,"links":mylinks}  
    //数据格式为Json格式
    createGraph(myChart,jsondata) }



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
        formatter: '{a} : {b}'
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
            itemStyle: {
                normal: {
                    label: {
                        show: true,
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
            useWorker: false,
            minRadius : 15,
            maxRadius : 25,
            gravity: 1.1,
            scaling: 1.1,
            roam: 'move',
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
            var data=params.value
            //点没有source属性
            if(data.source==undefined){
                nodeName=params.name
                window.open("http://www.baidu.com")
            }
 
});
//myChart.hideLoading();
}