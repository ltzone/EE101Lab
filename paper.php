<!DOCTYPE html> 
<html>
<head>
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

			echo "</table>";

		echo "</div>";
		
		//通过作者推荐文章
		//
		//
		//
		echo "<h1 style=\"font-family:Arial Black\">相关作者的文章: </h1>";echo "<hr>";
		
		
		$relatepaper= mysqli_fetch_array(mysqli_query($link, "SELECT d.PaperID, Title from (SELECT PaperID From (SELECT AuthorID FROM paper_author_affiliation a where a.PaperID = '7DED5581') b inner join paper_author_affiliation c on b.AuthorID = c.AuthorID group by PaperID) d inner join papers on d.PaperID = papers.PaperID"));
		
		
		//
		var_dump($relatepaper);

	
		#reference查找

		
		#增加查询reference结果为空的条件判断

		
		
		$result = mysqli_query($link, "SELECT ReferenceID from paper_reference2 where PaperID='$paper_id'");
		if ($result->num_rows) {$count = 0;
			echo "<div class='paperlis'>";	
			while ($row = mysqli_fetch_array($result)) {
			$count ++;
			}	echo "<h1 style=\"font-family:Arial Black\">被引用数: $count</h1>";echo "<hr>";
		}
		else {
				echo "Reference not found";
		}	
		
		
		
		
		echo "<h1 style=\"font-family:Arial Black\">引用文章</h1>";
				

		$result = mysqli_query($link, "SELECT PaperID from paper_reference2 where ReferenceID='$paper_id'");
		if ($result->num_rows) {
			echo "<div class='paperlis'>";	
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
		}
		
} else {
echo "没有引用";}








//通过相关标题推荐（solr）
			echo "<h1 style=\"font-family:Arial Black\">相关</h1>";

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
			echo "</div>";
		// 显示搜索结果的分区
			echo "<hr>";
if ($result['response']['numFound']>0){//有时会有index response not defined   bug
			echo "<div class='paperlis'>";
			foreach ($result['response']['docs'] as $paper) {//这里最好做一下分页,一次显示三条

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
}
		

	?>
</div>
</body>

</html>