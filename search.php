<!DOCTYPE html> 
<html>
<head>
<title>search page example</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="css/style.css" rel="stylesheet" type="text/css" />

<script src="https://cdn.bootcss.com/echarts/4.2.1-rc1/echarts-en.common.js"></script>

</head>

<body>
	 	<div class="header">	
	      <div class="container"> 
	  	     <div class="logo">
				<h1><a href="index.php">Result Page</a></h1>
			 </div>
			 <div class="clearfix">
			 </div>
			</div>
		</div>


<div class="container">
	<?php
		$keyword = $_GET["keyword"];
		$key=$keyword;
		if ($keyword) {
			// searchinfo分区，显示会议具体信息
			echo "<div class = 'searchinfo'>";
			echo "<h1>Search Results</h1>";
			//显示keyword，执行搜索
			$keyword2 = ucwords($keyword);
			echo "Keywords: ".$keyword2;
			$ch = curl_init();
			$timeout = 5;
			$query = urlencode(str_replace(' ', '+', $keyword));
			$url = "http://localhost:8983/solr/FINAL/select?q=keyword%3A".$query."&start=0&rows=100000&wt=json";
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$result = json_decode(curl_exec($ch), true);
			curl_close($ch);
			echo "</div>";


			echo "<hr>";
			$page_num=$_GET['page'];
			if(!$page_num)$page_num=1;
			if($page_num<0)$page_num=1;
			$num_results =$result['response']['numFound'];
			$page_total=(integer)(($num_results+9)/10);
			if($page_num>$page_total)$page_num=$page_total;
			if ($result['response']['numFound']>0){





			//收集echarts 统计数据
			//chart1: years, count_year
			//chart2: conferences, count_conference
			$years_data = array();
			$conferences_data = array();
			$all_paper = $result["response"]["docs"];
			foreach ($all_paper as $paper_data) {
				if(array_key_exists($paper_data["Year"], $years_data)){
					$years_data[$paper_data["Year"]]++;
				}else{
					$years_data[$paper_data["Year"]]=1;
				}
				if(array_key_exists($paper_data["ConferenceName"], $conferences_data)){
					$conferences_data[$paper_data["ConferenceName"]]++;
				}else{
					$conferences_data[$paper_data["ConferenceName"]]=1;
				}
			}

			ksort($years_data);
			$years = array();
			$count_year = array();
			foreach ($years_data as $key => $value) {
				array_push($years,$key);
				array_push($count_year,$value);
			}

			ksort($conferences_data);
			$conferences = array();
			$count_conference = array();
			foreach ($conferences_data as $key => $value) {
				array_push($conferences,$key);
				array_push($count_conference,$value);
			}




			// 显示信息的区域
			echo "<div class='paperlis'>";

			$num_results =$result['response']['numFound'];
			$page_total=(integer)(($num_results+9)/10);
			if($page_num>$page_total)$page_num=$page_total;
			if($page_num==$page_total)$l=$num_results;
			else $l=($page_num-1)*10+10;



			for ($i=($page_num-1)*10;$i<$l;$i++) {
				$paper=$result['response']['docs'][$i] ;
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




			// 翻页模块
			echo '<p>PageCount(10 messages per page):&nbsp;&nbsp;'.$page_total.'    </p>';
			echo '<p>Total messages:&nbsp;&nbsp;'.$num_results.'</p>';
			if($page_total>$page_num )
			{
			  	if($page_num>1)
			  	{
				  	echo '<a href="./search.php?page=1&keyword='.($key).'">first page&nbsp;&nbsp;&nbsp;</a>    ';
				  	echo '<a href="./search.php?page='.($page_num-1).'&keyword='.($key).'"> previous page&nbsp;&nbsp;&nbsp;</a>';
				  	echo " "."$page_num".'/'."$page_total"."&nbsp;&nbsp;&nbsp; ";
				  	echo '<a href="./search.php?page='.($page_num+1).'&keyword='.($key).'">next page&nbsp;&nbsp;&nbsp;</a>';
				  	echo '<a href="./search.php?page='.($page_total).'&keyword='.($key).'">    last page</a>';
			  	}
			  	else 
			  	{
			  		echo 'first page&nbsp;&nbsp;&nbsp;  ';
			  		echo ' previous page&nbsp;&nbsp;&nbsp;';
				  	echo " "."$page_num".'/'."$page_total"."&nbsp;&nbsp;&nbsp; ";
				  	echo '<a href="./search.php?page='.($page_num+1).'&keyword='.($key).'">next page&nbsp;&nbsp;&nbsp;</a>';
					echo '<a href="./search.php?page='.($page_total).'&keyword='.($key).'">    last page&nbsp;&nbsp;&nbsp;</a>';
			  	}
			}
			else
			{
				if($page_total==1)
				{
		  		echo 'first page&nbsp;&nbsp;&nbsp;    ';
		  		echo ' previous page&nbsp;&nbsp;&nbsp;';
			  	echo " "."$page_num".'/'."$page_total"." &nbsp;&nbsp;&nbsp;";
			  	echo 'next page&nbsp;&nbsp;&nbsp;';
			  	echo 'last page&nbsp;&nbsp;&nbsp;';
				}
				else
				{
					echo '<a href="./search.php?page=1&keyword='.($key).'">first page&nbsp;&nbsp;&nbsp;</a>     ';
					echo '<a href="./search.php?page='.($page_num-1).'&keyword='.($key).'"> previous page&nbsp;&nbsp;&nbsp;</a>';
		  		echo " "."$page_num".'/'."$page_total"."&nbsp;&nbsp;&nbsp; ";
		  		echo 'next page&nbsp;&nbsp;&nbsp;';
		  	    echo 'last page';
				}
			}
		   	echo "<form  action=\"search.php\"><div style=\"text-align:left;\">";
    		echo "<input type=\"integer\"  id=\"page\" name=\"page\">";
    		echo "<input name='keyword' type='hidden' id='keyword' value=$key>";
			echo "<button type=\"submit\" class=\"btn btn-default\">jump to the page</button></div></form>";
			echo "</div>";



			// 图表显示区
			echo "<div class='chartlis'>";
			echo "<div id=\"year_chart\" style=\"width: 350px;height:250px;\"></div>";
			echo "<div id=\"conference_chart\" style=\"width: 350px;height:250px;\"></div>";
			echo "</div>";



		}

else {echo "No Search Results!";}
}
	?>




	<script type="text/javascript">
        var myChart = echarts.init(document.getElementById('year_chart'));

        var years1 = eval(decodeURIComponent('<?php echo urlencode(json_encode($years));?>'));
        var count_year1 = eval(decodeURIComponent('<?php echo urlencode(json_encode($count_year));?>'));
 
		option = {
		    title: {
		        text: '论文发表年份趋势'
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
		        type: 'value'
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


    <script type="text/javascript">
        var myChart = echarts.init(document.getElementById('conference_chart'));

        var conferences1 = eval(decodeURIComponent('<?php echo urlencode(json_encode($conferences));?>'));
        var count_conference1 = eval(decodeURIComponent('<?php echo urlencode(json_encode($count_conference));?>'));
 
		option = {
		    title: {
		        text: '论文发表会议分布'
		    },
		    tooltip: {
		        trigger: 'axis'
		    },
		    legend: {
		        data:['number of papers']
		    },
		    xAxis: {
		        type: 'category',
		        data: conferences1
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
	
</div>





</body>
</html>