<!DOCTYPE html> 
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=gbk" />
    <meta charset="utf-8">
    <script src="js/echarts.js"></script>
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="css/style.css" rel="stylesheet" type="text/css" />
<title>Paper Page</title>

</head>



<body>
 	<div class="header">
      <div class="container"> 
  	     <div class="logo">
			<h1><a href="index.php">Paper Page</a></h1>
		 </div>
		 <div class="clearfix">
		 </div>
		</div>
	</div>

<div class="container">
	<?php
		$paper_id = $_GET["paper_id"];
		$link = mysqli_connect("localhost:3306", 'root', '770528', 'FINAL');
		echo "<div class = 'authorinfo'>";
		$result = mysqli_query($link, "SELECT Title from papers where PaperID='$paper_id'");
		if ($result) {
			$paper_name = mysqli_fetch_array($result)['Title'];
			$paper_name2 = ucwords($paper_name);
			echo "<h1 style=\"font-family:Arial Black\"> $paper_name2</h1>";
		}
		
		


		#查找paper对应的Author，conference，year



		$result = mysqli_query($link, "SELECT PaperID from paper_author_affiliation where PaperID='$paper_id'");
		if ($result) {
			$row = mysqli_fetch_array($result);
				echo "<tr>";
				$paper_id = $row['PaperID'];
				$paper_info = mysqli_fetch_array(mysqli_query($link, "SELECT ConferenceID , PaperPublishYear from papers where PaperID='$paper_id'"));
				if ($paper_info){
					echo "<table>";
					echo "<tr><td width = '120'><b> Authors: </b></td><td>";
					$author_info = mysqli_query($link, "SELECT A.AuthorID, AuthorName FROM paper_author_affiliation A LEFT JOIN authors B ON A.AuthorID = B.AuthorID WHERE PaperID = '$paper_id' ORDER BY AuthorSequence ASC");
						while ($author_row = mysqli_fetch_array($author_info)){
						$author_name = $author_row['AuthorName'];
						$author_another_id = $author_row['AuthorID'];
						$author_name2 = ucwords($author_name);
						$author_another_id2 = ucwords($author_another_id);
						echo "<a href=\"author.php?author_id=$author_another_id2\">$author_name2; </a>";
					}
					echo "</td></tr>";
					echo "<tr><td><b> Conference: </b></td><td>";
					
					$conference_id = $paper_info['ConferenceID'];
					$year= $paper_info['PaperPublishYear'];

				}

				
					$conference_row = mysqli_fetch_array(mysqli_query($link, "SELECT ConferenceName from conferences WHERE ConferenceID = '$conference_id'"));
					$conference_name = $conference_row['ConferenceName'];
					$conference_name2 = ucwords($conference_name);
					echo "<a href=\"conference.php?conference_id=$conference_id\">$conference_name2; </a>";
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

			echo "</table>";

		echo "</div>";



	
		#reference查找

		
		#增加查询reference结果为空的条件判断


		

		$result = mysqli_query($link, "SELECT ReferenceID from paper_reference2 where PaperID='$paper_id'");
		if ($result->num_rows) {
			echo "<div class='paperlis'>";	

			echo "<h1 style=\"font-family:Arial Black\">引用文章</h1>";
			while ($row = mysqli_fetch_array($result)) {
				$paper_id_ref = $row['ReferenceID'];
				$paper_info = mysqli_fetch_array(mysqli_query($link, "SELECT Title, ConferenceID from papers where PaperID='$paper_id_ref'"));
				if ($paper_info){
					$paper_title = $paper_info['Title'];
					$conf_id = $paper_info['ConferenceID'];
					$paper_title2 = ucwords($paper_title);
					echo "<a href=\"paper.php?paper_id=$paper_id_ref\"><h3>$paper_title2</h3></a>";
					echo "<table>";
					echo "<tr><td width = '120'><b> Authors: </b></td><td>";
					$author_info = mysqli_query($link, "SELECT A.AuthorID, AuthorName FROM paper_author_affiliation A LEFT JOIN authors B ON A.AuthorID = B.AuthorID WHERE PaperID = '$paper_id' ORDER BY AuthorSequence ASC");

					while ($author_row = mysqli_fetch_array($author_info)){
						$author_name = $author_row['AuthorName'];
						$author_another_id = $author_row['AuthorID'];
						$author_name2 = ucwords($author_name);
						$author_another_id2 = ucwords($author_another_id);
						echo "<a href=\"author.php?page=1&author_id=$author_another_id2\">$author_name2</a>";
						echo "; ";
					}
					echo "</td></tr>";
					echo "<tr><td><b> Conference: </b></td><td>";

					$conference_row = mysqli_fetch_array(mysqli_query($link, "SELECT ConferenceName from conferences WHERE ConferenceID = '$conf_id'"));
					$conference_name = $conference_row['ConferenceName'];
					$conference_name2 = ucwords($conference_name);
					echo "<a href=\"conference.php?page=1&conference_id=$conf_id\">$conference_name2</a>";
					echo "</td></tr>";
					echo "</table>";
				}
				echo "<hr>";
			}
			echo "</div>";

		}
		else {
				echo "Reference not found";
		}	
		

		
				

		$result = mysqli_query($link, "SELECT PaperID from paper_reference2 where ReferenceID='$paper_id'");
		if ($result->num_rows) {
			echo "<div class='paperlis'>";
			echo "<h1 style=\"font-family:Arial Black\">被引用文章</h1>";
			while ($row = mysqli_fetch_array($result)) {
				$paper_id_ref = $row['PaperID'];
				$paper_info = mysqli_fetch_array(mysqli_query($link, "SELECT Title, ConferenceID from papers where PaperID='$paper_id_ref'"));
				if ($paper_info){
					$paper_title = $paper_info['Title'];
					$conf_id = $paper_info['ConferenceID'];
					$paper_title2 = ucwords($paper_title);
					echo "<a href=\"paper.php?paper_id=$paper_id_ref\"><h3>$paper_title2</h3></a>";
					echo "<table>";
					echo "<tr><td width = '120'><b> Authors: </b></td><td>";
					$author_info = mysqli_query($link, "SELECT A.AuthorID, AuthorName FROM paper_author_affiliation A LEFT JOIN authors B ON A.AuthorID = B.AuthorID WHERE PaperID = '$paper_id' ORDER BY AuthorSequence ASC");

					while ($author_row = mysqli_fetch_array($author_info)){
						$author_name = $author_row['AuthorName'];
						$author_another_id = $author_row['AuthorID'];
						$author_name2 = ucwords($author_name);
						$author_another_id2 = ucwords($author_another_id);
						echo "<a href=\"author.php?page=1&author_id=$author_another_id2\">$author_name2</a>";
						echo "; ";
					}
					echo "</td></tr>";
					echo "<tr><td><b> Conference: </b></td><td>";

					$conference_row = mysqli_fetch_array(mysqli_query($link, "SELECT ConferenceName from conferences WHERE ConferenceID = '$conf_id'"));
					$conference_name = $conference_row['ConferenceName'];
					$conference_name2 = ucwords($conference_name);
					echo "<a href=\"conference.php?page=1&conference_id=$conf_id\">$conference_name2</a>";
					echo "</td></tr>";
					echo "</table>";
				}
				
				else {
				echo "Reference not found";
		}

				echo "<hr>";
			}
			echo "</div>";
		}
		
} else {
echo "Paper not found";}
		




			$paper_id = $_GET["paper_id"];
			$paper_ti=mysqli_fetch_array(mysqli_query($link, "SELECT Title from papers where PaperID='$paper_id'"));
			$paper_title3=$paper_ti['Title'];
				$ch = curl_init();
			$timeout = 5;
			//echo $paper_title3;
			$paper_title4=substr($paper_title3,3,-2);
			$query = urlencode(str_replace(' ', '+', $paper_title4));
			$url = "http://localhost:8983/solr/FINAL/select?q=PaperName%3A".$query."&wt=json";

			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$result = json_decode(curl_exec($ch), true);
			curl_close($ch);
		// 显示搜索结果的分区
			if ($result['response']['numFound']>0){
				echo "<div class='paperlis'>";
				echo "<h1 style=\"font-family:Arial Black\">相关文章内容</h1>";
				foreach ($result['response']['docs'] as $paper) {

					$paper_id = $paper['PaperID'];
					$papername2 = ucwords($paper['PaperName']);
					echo "<a href=\"paper.php?paper_id=$paper_id\"><h3>$papername2</h3></a>";
					echo "<table>";
					echo "<tr><td width = '120'><b> Authors: </b></td><td>";

					foreach ($paper['AuthorName'] as $idx => $author) {
						$author_id = substr($paper['AuthorID'][$idx],2,-3);
						$author2 = ucwords($author);
						echo "<a href=\"author.php?page=1&author_id=$author_id\">$author2</a>";
						echo "; ";
					}
					echo "</td></tr>";
					echo "<tr><td><b> Conference: </b></td><td>";
					$conference_id =$paper['ConferenceID'];
					$conference = $paper['ConferenceName'];
					echo "<a href=\"conference.php?page=1&conference_id=$conference_id\">$conference</a>";
					echo "; ";
					echo "</td></tr>";
					echo "</table>";
					echo "<hr>";
				}
				echo "</div>";
			}



		
		//通过作者推荐文章
		//
		//
		//
		echo "<div class='paperlis'>";
		echo "<h1 style=\"font-family:Arial Black\">相关作者的文章: </h1>";echo "<hr>";
		
		
		$relatepaper= mysqli_fetch_array(mysqli_query($link, "SELECT d.PaperID, Title from (SELECT PaperID From (SELECT AuthorID FROM paper_author_affiliation a where a.PaperID = '7DED5581') b inner join paper_author_affiliation c on b.AuthorID = c.AuthorID group by PaperID) d inner join papers on d.PaperID = papers.PaperID"));
		echo "</div>;"
		$paper_num=3;
		while($paper_num>0){
			
			
			
			$paper_num--;
		}
		

	?>



<?php

	function get_paper_name($link,$paper_id){
		$res = mysqli_query($link,"SELECT Title from papers where PaperID='$paper_id'");
		if ($res) {
				return ucwords(mysqli_fetch_array($res)['Title']);
			}
		else return NULL;
	}
	$paper_id = $_GET["paper_id"];
	$result = mysqli_query($link, "SELECT paperID,referenceID FROM paper_reference2 WHERE paperID = '$paper_id'");

	$links = [];
	$nodes = [array('category'=>0,'name'=> $paper_id,'value'=>20, 'label'=> get_paper_name($link,$paper_id))];
	$node_records = array($paper_id);


	$connect = mysqli_fetch_all($result);
	foreach ($connect as $connect_elem){
		$link_item = array('source'=>$connect_elem[1],  'target'=> $connect_elem[0] ,'name'=>'reference');
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
				$link_item = array('source'=>$connection_elem[1],  'target'=> $connection_elem[0],'name'=>'reference');
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

	echo "<div style='padding:20px;width:100%;height:100%;'> 
		  <div id='main' style='width: 1104px;height:464px;'></div></div>";

    $nodes = json_encode($nodes);
    $links = json_encode($links);

	?>

</div>

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