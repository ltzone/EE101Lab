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
			$url = "http://localhost:8983/solr/FINAL/select?q=keyword%3A".$query."&wt=json";

			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$result = json_decode(curl_exec($ch), true);
			curl_close($ch);
			echo "</div>";
			// 显示搜索结果的分区
			echo "<hr>";
if ($result['response']['numFound']>0){
			echo "<div class='paperlis'>";
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

			// 显示charts的分区
				echo "<div class='chartlis'>";


				echo "</div>";
			}
			echo "</div>";
		}
else {echo "No Search Results!";}

}

	?>
</div>
</body>
</html>