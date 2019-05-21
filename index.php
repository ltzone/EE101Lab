<html>

<head>
<title>Home</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="css/style.css" rel="stylesheet" type="text/css" />

</head>

<body>


 	<div class="header">	
      <div class="container"> 
  	     <div class="logo">
			<h1><a href="index.php">Search The Papers</a></h1>
		 </div>
		 <div class="clearfix"></div>
		</div>
	</div>

	<div class="container">
		<br>
	    	<h2>Enter Your Keywords</h2>
			<form class="form-horizontal" action="search.php">
			  <div class="form-group">
			    <label for="paper_title" class="col-sm-2 control-label">Paper Title</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="paper_title" name="paper_title">
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="author_name" class="col-sm-2 control-label">Author's Name</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="author_name" name="author_name">
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="conference_name" class="col-sm-2 control-label">Conference's Name</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="conference_name" name="conference_name">
			    </div>
			  </div>
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" class="btn btn-default">Search</button>
			    </div>
			  </div>
			</form>
	</div>



</body>

</html>
