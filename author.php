
<!DOCTYPE html> 
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=gbk" />
    <meta charset="utf-8">
    <script src="js/echarts.js"></script>
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
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
		$author_ex_id = ucwords($author_id);
		$link = mysqli_connect("localhost:3306", 'root', '', 'FINAL');
		$result = mysqli_query($link, "SELECT AuthorName from authors where AuthorID='$author_id'");
if ($result) {
		// authorinfo分区，显示名字和机构
		echo "<div class = 'authorinfo'>";
		$author_name = mysqli_fetch_array($result)['AuthorName'];
		$author_name2 = ucwords($author_name);
		echo "<h1>$author_name2</h1>";
		
		$result = mysqli_query($link, "SELECT Affiliations.AffiliationID, Affiliations.AffiliationName from (select AffiliationID, count(*) as cnt from paper_author_affiliation where AuthorID='$author_id' and AffiliationID is not null group by AffiliationID order by cnt desc) as tmp inner join Affiliations on tmp.AffiliationID = Affiliations.AffiliationID");

		if ($result->num_rows>0){
		echo "<b>Affiliations: </b>";
		foreach ($result as $affline){
			$Affi_name = ucwords($affline['AffiliationName']);
			echo "$Affi_name;";}
		}
		echo "</div>";
		echo "<hr>";



	  	$result = mysqli_query($link, "SELECT count(PaperID) from paper_author_affiliation where AuthorID='$author_id' ");
	  	$row = $result->fetch_array();
	  	$num_results = $row[0];
	  	$page_num=$_GET['page'];
	  	if(!$page_num)$page_num=1;
	  	if($page_num<0)$page_num=1;
	  	$page_total=(integer)(($num_results+9)/10);
	  	
	  	if($page_num>$page_total)$page_num=$page_total;
		$result = mysqli_query($link, "SELECT PaperID from paper_author_affiliation where AuthorID='$author_id'limit ".(($page_num-1)*10).",10 ");
		// 显示搜索结果的分区
		if ($result) {
			echo "<div class='paperlis'>";	
			while ($row = mysqli_fetch_array($result)) {
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
				  	
				  	echo '<a href="./author.php?page=1&author_id='.($author_ex_id).'">first page&nbsp;&nbsp;&nbsp;</a>    ';
				  	echo '<a href="./author.php?page='.($page_num-1).'&author_id='.($author_ex_id).'"> previous page&nbsp;&nbsp;&nbsp;</a>';
				  	echo " "."$page_num".'/'."$page_total"."&nbsp;&nbsp;&nbsp; ";
				  	echo '<a href="./author.php?page='.($page_num+1).'&author_id='.($author_ex_id).'">next page&nbsp;&nbsp;&nbsp;</a>';
				  	echo '<a href="./author.php?page='.($page_total).'&author_id='.($author_ex_id).'">    last page&nbsp;&nbsp;&nbsp;</a>';
			  	}
			  	else 
			  	{
			  		echo 'first page&nbsp;&nbsp;&nbsp;    ';
			  		echo ' previous page&nbsp;&nbsp;&nbsp;';
				  	echo " "."$page_num".'/'."$page_total"." &nbsp;&nbsp;&nbsp;";
				  	echo '<a href="./author.php?page='.($page_num+1).'&author_id='.($author_ex_id).'">next page&nbsp;&nbsp;&nbsp;</a>';
					echo '<a href="./author.php?page='.($page_total).'&author_id='.($author_ex_id).'">    last page</a>';
			  	}
		   	}
		   	else
		   	{
		   		if($page_total==1)
		  		{
			  		echo 'first page&nbsp;&nbsp;&nbsp; ';
			  		echo ' previous page&nbsp;&nbsp;&nbsp;';
				  	echo " "."$page_num".'/'."$page_total"." &nbsp;&nbsp;&nbsp;";
				  	echo 'next page&nbsp;&nbsp;&nbsp;';
				  	echo ' last page';
		  		}
		  		else
		  		{
		  			echo '<a href="./author.php?page=1&author_id='.($author_ex_id).'">first page&nbsp;&nbsp;&nbsp;</a>     ';
		  			echo '<a href="./author.php?page='.($page_num-1).'&author_id='.($author_ex_id).'"> previous page&nbsp;&nbsp;&nbsp;</a>';
			  		echo " "."$page_num".'/'."$page_total"."&nbsp;&nbsp;&nbsp; ";
			  		echo 'next page&nbsp;&nbsp;&nbsp;';
			  	   echo ' last page';
		  		}
		   	}
		   	echo "</div>";	



		   	// 展示echarts的分区
		   	echo "<div class='chartlis'>";
			# 有关echarts会议的统计数据
			$result = mysqli_query($link,"SELECT count(*) AS ConferenceName, ConferenceName FROM (paper_author_affiliation C INNER JOIN (SELECT A.PaperID, B.ConferenceName FROM papers A INNER JOIN conferences B ON A.ConferenceID = B.ConferenceID) D ON D.PaperID = C.PaperID) WHERE C.AuthorID = '$author_id' GROUP BY ConferenceName");
			$conference_num = mysqli_fetch_all($result);

			# 整理数据
			$conference_names = array();
			$conference_counts = array();
			foreach ($conference_num as $conference_num_line){
				array_push($conference_counts,intval($conference_num_line[0]));
				array_push($conference_names,$conference_num_line[1]);
			}
			$conference_names = json_encode($conference_names);
			$conference_counts = json_encode($conference_counts);

			# 创建一个div元素，调用自己写的mycharts中的制图函数

			$author_name3 = json_decode($author_name2);
			echo "<div id=\"main\" style=\"width:350px;height:250px;\"></div>";
			echo "<script src=\"js/mycharts.js\"> conference_graph($conference_counts,$conference_names);
			//</script>";


			# 有关echarts会议的统计数据
			// $result = mysqli_query($link,"SELECT count(*) AS Yearcount, PaperPublishYear FROM (paper_author_affiliation C INNER JOIN papers D ON D.PaperID = C.PaperID) WHERE C.AuthorID = '".$' GROUP BY PaperPublishYear order by PaperPublishYear asc;
			echo "</div>";
		}
		} else {
			echo "Name not found";
		}

	?>

<form  action="author.php">
			  
			    
			    
			      <input type="integer"  id="page" name="page">

			    <br>
			  
			  <input name="author_id" type="hidden" id="author_id" value="<?php echo $author_id;?>" />
			  
			      <button type="submit" class="btn btn-default">jump to the page</button>
	

			</form>
</div>
</body>
</html>