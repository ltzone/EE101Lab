




function conference_graph(conf_nums,conf_names){
// 基于准备好的dom，初始化echarts实例
var myChart = echarts.init(document.getElementById('main'));

// 指定图表的配置项和数据
var option = {
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
myChart.setOption(option);

};
