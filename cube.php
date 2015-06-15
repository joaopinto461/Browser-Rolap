<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Data Warehousing</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/cube.css" rel="stylesheet">
    <link href="css/jquery.dynatable.css" rel="stylesheet">
    <script src="js/jQuery.js"></script>
    <script src="js/jQuery-ui.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/cubes.js"></script>
    <script src="js/jquery.dynatable.js"></script>
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
	 
	    </div>
	  </div>
	</nav>

	<div class="container-fluid">
	<div class="container_central">
	    
	    <div class="row">

		  <div class="col-md-3">

			  <div class="left_col">			 
			  	<div class="left_col_elem">
			  		<h5>Active Levels</h5>
			  		<hr>
			  		<ul class="active_levels_list"></ul>
			  	</div>
			  	<div class="left_col_elem">
			  		<h5>Active Measures</h5>
			  		<hr>
			  		<ul class="active_measures_list"></ul>
			  	</div>
			  </div>
		  </div>

		  <div class="col-md-6">

		  	<div class="center_col">
		  		<h3><? echo $cube_name;?></h3>
		  		  	<table id="table" class="table" style="width:100%; text-align:left;">
		              <thead>
		              <th>Nome</th>
		              <th>Idade</th>
		              </thead>
		              <tbody>
		              </tbody>
            		</table>	
		  	</div>
		  </div>

		  <div class="col-md-3">
			<div class="right_col_elem" id="dimensions_square">
		  		<h5>Dimensions</h5>
		  		<hr>		  		  	
	  			<ul class="dimensions_list sub">		
	  			<?php foreach ($dim_info as $dim_id => $dim_data) :?>
	  				<li class="dimension accordion" id="<? print $dim_id ?>">
	  					<h5><i class="glyphicon glyphicon-list"></i> <? print $dim_data["name_dimension"] ?> </h5>
	  					<ul class="sub" style="display: none;">	  					
	  					<?php foreach ($dim_data["levels"] as $level_id=>$level) :?>
	  						<?php foreach ($level as $l):?>
	  							<li class="level" id="<? print $level_id ?>"><i class="glyphicon glyphicon-move"></i> <? print $l ?></li>
	  						<?php endforeach;?>
	  					<?php endforeach ?>
	  					</ul>
	  				</li>
	  			<?php endforeach ?>
	  			</ul>
		  	</div>
		  	
			<div class="right_col_elem">
		  		<h5>Measures</h5>
		  		<hr>
		  		<ul class="measures_list">
			  		<?php foreach ($measure_info as $measure_id => $measure_data) :?>
			  			<li class="measure" id="<? print $measure_id ?>"><i class="glyphicon glyphicon-tasks"></i> <? print $measure_data ?></li>
			  		<?php endforeach ?>		  		
		  		</ul>
		  	</div>
		  </div>		  
		</div>
	</div>
	</div>
  </body>
</html>
