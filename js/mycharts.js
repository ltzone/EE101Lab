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

