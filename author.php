<!DOCTYPE html> 
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="css/style.css" rel="stylesheet" type="text/css" />
<title>Author Page</title>

</head>



<body>
 	<div class="header">
      <div class="container"> 
  	     <div class="logo">
			<h1><a href="index.php">Author Page</a></h1>
		 </div>
		 <div class="clearfix">
		 </div>
		</div>
	</div>

<div class="container">
	<?php
		$author_id = $_GET["author_id"];
		$link = mysqli_connect("localhost:3306", 'root', '', 'FINAL');
		$result = mysqli_query($link, "SELECT AuthorName from authors where AuthorID='$author_id'");
		if ($result) {
			$author_name = mysqli_fetch_array($result)['AuthorName'];
			$author_name2 = ucwords($author_name);
			echo "<h1 style=\"font-family:Arial Black\"> $author_name2</h1>";
			
		$result = mysqli_query($link, "SELECT Affiliations.AffiliationID, Affiliations.AffiliationName from (select AffiliationID, count(*) as cnt from paper_author_affiliation where AuthorID='$author_id' and AffiliationID is not null group by AffiliationID order by cnt desc) as tmp inner join Affiliations on tmp.AffiliationID = Affiliations.AffiliationID");

		if ($result->num_rows>0){
			echo "<p style=\"font-family:Arial;font-size:20px;bold;\">Affiliations: </p>";
			foreach ($result as $affline){
				$Affi_name = ucwords($affline['AffiliationName']);
				echo "<p style=\"font-size:15px;\">$Affi_name</p>";}
			}


		$result = mysqli_query($link, "SELECT PaperID from paper_author_affiliation where AuthorID='$author_id'");
		if ($result) {
			echo "<table border=\"0\" frame=\"hsides\"><tr><th>Title</th><th>Authors</th><th>Conference</th></tr>";
			while ($row = mysqli_fetch_array($result)) {
				echo "<tr>";
				$paper_id = $row['PaperID'];
				# 请增加对mysqli_query查询结果是否为空的判断
				$paper_info = mysqli_fetch_array(mysqli_query($link, "SELECT Title, ConferenceID from papers where PaperID='$paper_id'"));
				if ($paper_info){
					$paper_title = $paper_info['Title'];
					$conf_id = $paper_info['ConferenceID'];
					$paper_title2 = ucwords($paper_title);
					echo "<td>$paper_title2</td>";

					$author_info = mysqli_query($link, "SELECT A.AuthorID, AuthorName FROM paper_author_affiliation A LEFT JOIN authors B ON A.AuthorID = B.AuthorID WHERE PaperID = '$paper_id' ORDER BY AuthorSequence ASC");

					echo "<td>";
					while ($author_row = mysqli_fetch_array($author_info)){
						$author_name = $author_row['AuthorName'];
						$author_another_id = $author_row['AuthorID'];
						$author_name2 = ucwords($author_name);
						$author_another_id2 = ucwords($author_another_id);
						echo "<a href=\"author.php?author_id=$author_another_id2\">$author_name2; </a>";
					}
					echo "</td>";


					echo "<td>";
					$conference_row = mysqli_fetch_array(mysqli_query($link, "SELECT ConferenceName from conferences WHERE ConferenceID = '$conf_id'"));
					$conference_name = $conference_row['ConferenceName'];
					$conference_name2 = ucwords($conference_name);
					echo $conference_name2;
					echo "</td>";




				}
				# 请增加根据paper id在PaperAuthorAffiliations与Authors两个表中进行联合查询，找到根据AuthorSequenceNumber排序的作者列表，并且显示出来的部分


				# 请补充根据$conf_id查询conference name并显示的部分

				echo "</tr>";
			}
			echo "</table>";
		}














		} else {
			echo "Name not found";
		}








	?>
</div>
</body>

</html>