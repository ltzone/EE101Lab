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
	<h1>Search Results</h1>
	<?php
		$paper_title = $_GET["paper_title"];
		if ($paper_title) {
			$paper_title2 = ucwords($paper_title);
			echo "Search for Title: ".$paper_title2;
			$ch = curl_init();
			$timeout = 5;
			$query = urlencode(str_replace(' ', '+', $paper_title));
			$url = "http://localhost:8983/solr/FINAL/select?q=PaperName%3A".$query."&wt=json";

			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$result = json_decode(curl_exec($ch), true);
			curl_close($ch);
			echo "<table border=\"0\" frame=\"hsides\" ><tr><th>Title</th><th>Authors</th><th>Conference</th></tr>";


			foreach ($result['response']['docs'] as $paper) {
				echo "<tr>";
				echo "<td>";
				$papername2 = ucwords($paper['PaperName']);
				echo $papername2;
				echo "</td>";

				echo "<td>";
				foreach ($paper['AuthorName'] as $idx => $author) {
					$author_id = substr($paper['AuthorID'][$idx],2,-3);
					$author2 = ucwords($author);
					echo "<a href=\"author.php?author_id=$author_id\">$author2; </a>";
				}
				echo "</td>";

				echo "<td>";
				$conference = $paper['ConferenceName'];
				echo $conference;
				echo "</td>";
				echo "</tr>";
			}
			echo "</table><br><br>";
		}
		$conference_name = $_GET["conference_name"];
		if ($conference_name) {
			echo "Search for ConferenceName: ".$conference_name;
			$ch = curl_init();
			$timeout = 5;
			$query = urlencode(str_replace(' ', '+', $conference_name));
			$url = "http://localhost:8983/solr/FINAL/select?q=ConferenceName%3A".$query."&wt=json";

			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$result = json_decode(curl_exec($ch), true);
			curl_close($ch);
			echo "<table border=\"0\" frame=\"hsides\" ><tr><th>Title</th><th>Authors</th><th>Conference</th></tr>";

			foreach ($result['response']['docs'] as $paper) {
				echo "<tr>";
				echo "<td>";
				$papername2 = ucwords($paper['PaperName']);
				echo $papername2;
				echo "</td>";

				echo "<td>";
				foreach ($paper['AuthorName'] as $idx => $author) {
					$author_id = $paper['AuthorID'][$idx];
					$author2 = ucwords($author);
					echo "<a href=\"author.php?author_id=$author_id\">$author2; </a>";
				}
				echo "</td>";

				echo "<td>";
				$conference = $paper['ConferenceName'];
				echo $conference;
				echo "</td>";
				echo "</tr>";
			}
			echo "</table><br><br>";
		}


		$author_name = $_GET["author_name"];
		if ($author_name) {
			echo "Search for AuthorName: ".$author_name;
			$ch = curl_init();
			$timeout = 5;
			$query = urlencode(str_replace(' ', '+', $author_name));
			$url = "http://localhost:8983/solr/FINAL/select?q=AuthorName%3A".$query."&wt=json";

			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$result = json_decode(curl_exec($ch), true);
			curl_close($ch);
			echo "<table border=\"0\" frame=\"hsides\" ><tr><th>Title</th><th>Authors</th><th>Conference</th></tr>";
			foreach ($result['response']['docs'] as $paper) {
				echo "<tr>";
				echo "<td>";
				$papername2 = ucwords($paper['PaperName']);
				echo $papername2;
				echo "</td>";

				echo "<td>";
				foreach ($paper['AuthorName'] as $idx => $author) {
					$author_id = $paper['AuthorID'][$idx];
					$author2 = ucwords($author);
					echo "<a href=\"author.php?author_id=$author_id\">$author2; </a>";
				}
				echo "</td>";

				echo "<td>";
				$conference = $paper['ConferenceName'];
				echo $conference;
				echo "</td>";
				echo "</tr>";
			}
			echo "</table><br><br>";
		}
		# 请补充针对AuthorName以及ConferenceName的搜索
	?>
</div>
</body>

</html>