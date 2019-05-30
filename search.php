<!DOCTYPE html> 
<html>
<head>
<title>search page example</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="css/style.css" rel="stylesheet" type="text/css" />

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
			$url = "http://localhost:8983/solr/FINAL/select?q=keyword%3A".$query."&start=0&rows=10000&wt=json";

			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$result = json_decode(curl_exec($ch), true);
			curl_close($ch);
			echo "</div>";
			// 显示搜索结果的分区
			echo "<hr>";

			$page_num=$_GET['page'];
		if(!$page_num)$page_num=1;
		if($page_num<0)$page_num=1;
		$num_results =$result['response']['numFound'];
		$page_total=(integer)(($num_results+9)/10);
		if($page_num>$page_total)$page_num=$page_total;
if ($result['response']['numFound']>0){
	$num_results =$result['response']['numFound'];
		$page_total=(integer)(($num_results+9)/10);
		if($page_num>$page_total)$page_num=$page_total;
			echo "<div class='paperlis'>";
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
// 翻页模块
			
			// 显示charts的分区
				echo "<div class='chartlis'>";


				echo "</div>";
			}echo '<p>PageCount(10 messages per page):&nbsp;&nbsp;'.$page_total.'    </p>';
			echo '<p>Total messages:&nbsp;&nbsp;'.$num_results.'</p>';
			if($page_total>$page_num )
			{
			  	if($page_num>1)
			  	{
				  	echo '<a href="./search.php?page=1&keyword='.($key).'">first page&nbsp;&nbsp;&nbsp;</a>    ';
				  	echo '<a href="./search.php?page='.($page_num-1).'&keyword='.($key).'"> previous page&nbsp;&nbsp;&nbsp;</a>';
				  	echo " "."$page_num".'/'."$page_total"."&nbsp;&nbsp;&nbsp; ";
				  	echo '<a href="./search.php?page='.($page_num+1).'&keyword='.($key).'">next page&nbsp;&nbsp;&nbsp;</a>';
				  	echo '<a href="./search.php?page='.($page_total).'&keyword='.($key).'">    last page</a>';

			  	}
			  	else 
			  	{
			  		echo 'first page&nbsp;&nbsp;&nbsp;  ';
			  		echo ' previous page&nbsp;&nbsp;&nbsp;';
				  	echo " "."$page_num".'/'."$page_total"."&nbsp;&nbsp;&nbsp; ";
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
			  	echo " "."$page_num".'/'."$page_total"." &nbsp;&nbsp;&nbsp;";
			  	echo 'next page&nbsp;&nbsp;&nbsp;';
			  	echo 'last page&nbsp;&nbsp;&nbsp;';

				}
				else
				{
					echo '<a href="./search.php?page=1&keyword='.($key).'">first page&nbsp;&nbsp;&nbsp;</a>     ';
					echo '<a href="./search.php?page='.($page_num-1).'&keyword='.($key).'"> previous page&nbsp;&nbsp;&nbsp;</a>';
		  		echo " "."$page_num".'/'."$page_total"."&nbsp;&nbsp;&nbsp; ";
		  		echo 'next page&nbsp;&nbsp;&nbsp;';
		  	    echo 'last page';
				}
			}
			echo "</div>";
		}

else {echo "No Search Results!";}

}

	?>
	
</div>
<form  action="search.php">
			  
			    <div style="text-align:center;">
			    
			      <input type="integer"  id="page" name="page">

			    <br>
			  
			  <input name="keyword" type="hidden" id="keywordd" value="<?php echo $key;?>" />
			  
			      <button type="submit" class="btn btn-default">jump to the page</button>
	</div>

			</form>
</body>
</html>