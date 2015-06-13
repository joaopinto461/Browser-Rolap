<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Data Warehousing</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/cube.css" rel="stylesheet">
    <script src="js/jQuery.js"></script>
    <script src="js/jQuery-ui.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <meta charset='utf-8'>
    <?
		include 'main.php';    
	?>
  </head>

  <body>
	<nav class="navbar navbar-default">
	  <div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="#">Data Warehousing</a>
	    </div>

	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav">
	        <li><a href="home.php">Home</a></li>
	         <li class="active"><a href="#">Cube <span class="sr-only">(current)</span></a></li>
	      </ul>
	 
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>

	<div class="container_central">
	    <div class="row">
		  <div class="col-md-3">
			  <div class="left_col">			 
			  	<div class="left_col_elem">
			  		<h5>Left Stuff</h5>
			  	</div>
			  	<div class="left_col_elem">
			  		<h5>Left Stuff</h5>
			  	</div>
			  </div>
		  </div>
		  <div class="col-md-6">
		  	<div class="center_col">
		  		<table class="table">
		  			<th>Coluna 1</th><th>Coluna 2</th><th>Coluna 3</th>
  					<tr><td>Um</td><td>Dois</td><td>Três</td></tr>
  					<tr><td>Quatro</td><td>Cinco</td><td>Seis</td></tr>
				</table>	
		  	</div>
		  </div>
		  <div class="col-md-3">
		  	<div class="right_col_elem">
		  		<h5>Right Stuff</h5>
		  		<ul>
  					<li>Option</li>
  					<li>Option</li>
  					
				</ul>
		  	</div>
		  	<div class="right_col_elem">
		  		<h5>Right Stuff</h5>
			</div>
		  	<div class="right_col_elem">
		  		<h5>Right Stuff</h5>
			</div>
		  </div>
		</div>
	</div>

  </body>
</html>
