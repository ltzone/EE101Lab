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
			<h1><a href="index1.php">Search The Papers</a></h1>
		 </div>
		 <div class="clearfix"></div>
		</div>
	</div>

	<div class="container">
		<br>
	    	<h2>Enter Your Keywords</h2>
			<form class="form-horizontal" action="search1.php">
			  <div class="form-group">
			    <label for="keyword" class="col-sm-2 control-label">Keywords</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="keyword" name="keyword">
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