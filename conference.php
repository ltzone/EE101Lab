<!DOCTYPE html> 
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="css/style.css" rel="stylesheet" type="text/css" />
<title>Conference Page</title>

</head>

<body>
 	<div class="header">
      <div class="container"> 
  	     <div class="logo">
			<h1><a href="index.php">Conference Page</a></h1>
		 </div>
		 <div class="clearfix">
		 </div>
		</div>
	</div>

<div class="container">
	<?php
		$conference_id = $_GET["conference_id"];
		$conference_ex_id = ucwords($conference_id);
		$link = mysqli_connect("localhost:3306", 'root', '', 'FINAL');
		$result = mysqli_query($link, "SELECT ConferenceName from conferences where ConferenceID='$conference_id'");
		if ($result) {
			// conferenceinfo分区，显示会议具体信息
			echo "<div class = 'conferneceinfo'>";
			$conference_name = mysqli_fetch_array($result)['ConferenceName'];
			$conference_name2 = ucwords($conference_name);
			echo "<h1> $conference_name2</h1>";
			echo "</div>";
			
		$page_num=$_GET['page'];
		if(!$page_num)$page_num=1;
		if($page_num<0)$page_num=1;
	  	
		$result = mysqli_query($link, "SELECT count(PaperID) from papers where ConferenceID='$conference_id' ");
		$row = $result->fetch_array();
		$num_results = $row[0];
		$page_total=(integer)(($num_results+9)/10);
		if($page_num>$page_total)$page_num=$page_total;
		

		$result = mysqli_query($link, "SELECT PaperID from papers where ConferenceID='$conference_id'limit ".(($page_num-1)*10).",10 ");
		// 显示搜索结果的分区	
		if ($result) {
			echo "<hr>";
			echo "<div class='paperlis'>";
			while ($row = mysqli_fetch_array($result)) {
				echo "<tr>";
				$paper_id = $row['PaperID'];
				$paper_info = mysqli_fetch_array(mysqli_query($link, "SELECT Title, ConferenceID from papers where PaperID='$paper_id'"));
				if ($paper_info){
					$paper_title = $paper_info['Title'];
					$conf_id = $paper_info['ConferenceID'];
					$paper_title2 = ucwords($paper_title);
					echo "<a href=\"paper.php?paper_id=$paper_id\"><h3>$paper_title2</h3></a>";
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

			// 翻页模块
			echo '<p>PageCount(10 messages per page):&nbsp;&nbsp;'.$page_total.'    </p>';
			echo '<p>Total messages:&nbsp;&nbsp;'.$num_results.'</p>';
			if($page_total>$page_num )
			{
			  	if($page_num>1)
			  	{
				  	echo '<a href="./conference.php?page=1&conference_id='.($conference_ex_id).'">first page&nbsp;&nbsp;&nbsp;</a>    ';
				  	echo '<a href="./conference.php?page='.($page_num-1).'&conference_id='.($conference_ex_id).'"> previous page&nbsp;&nbsp;&nbsp;</a>';
				  	echo " "."$page_num".'/'."$page_total"."&nbsp;&nbsp;&nbsp; ";
				  	echo '<a href="./conference.php?page='.($page_num+1).'&conference_id='.($conference_ex_id).'">next page&nbsp;&nbsp;&nbsp;</a>';
				  	echo '<a href="./conference.php?page='.($page_total).'&conference_id='.($conference_ex_id).'">    last page</a>';

			  	}
			  	else 
			  	{
			  		echo 'first page&nbsp;&nbsp;&nbsp;  ';
			  		echo ' previous page&nbsp;&nbsp;&nbsp;';
				  	echo " "."$page_num".'/'."$page_total"."&nbsp;&nbsp;&nbsp; ";
				  	echo '<a href="./conference.php?page='.($page_num+1).'&conference_id='.($conference_ex_id).'">next page&nbsp;&nbsp;&nbsp;</a>';
					echo '<a href="./conference.php?page='.($page_total).'&conference_id='.($conference_ex_id).'">    last page&nbsp;&nbsp;&nbsp;</a>';
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
					echo '<a href="./conference.php?page=1&conference_id='.($conference_ex_id).'">first page&nbsp;&nbsp;&nbsp;</a>     ';
					echo '<a href="./conference.php?page='.($page_num-1).'&conference_id='.($conference_ex_id).'"> previous page&nbsp;&nbsp;&nbsp;</a>';
		  		echo " "."$page_num".'/'."$page_total"."&nbsp;&nbsp;&nbsp; ";
		  		echo 'next page&nbsp;&nbsp;&nbsp;';
		  	    echo 'last page';
				}
			}
			echo "</div>";
		}

			// 展示echarts的分区
		   	echo "<div class='chartlis'>";
			echo "</div>";

		} else {
			echo "Name not found";
		}

	?>
	
</div>
</body>
</html>
<!DOCTYPE html>
<html>

<body>



<form  action="conference.php">
			  
			    <div style="text-align:center;">
			    
			      <input type="integer"  id="page" name="page">

			    <br>
			  
			  <input name="conference_id" type="hidden" id="conference_id" value="<?php echo $conference_id;?>" />
			  
			      <button type="submit" class="btn btn-default">jump to the page</button>
	</div>

			</form>
</body>
</html>