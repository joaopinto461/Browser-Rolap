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
	    <h2 class="cube_title text-uppercase" id="<? print $cube_selected_id; ?>"><? print $cube_name;?></h2>
	    <div class="row">

		  <div class="col-md-3">

			  <div class="left_col">

			  	
			  	<div class="left_col_elem">
			  		<h5><i class="glyphicon glyphicon-check"></i> Active Levels</h5>
			  		<hr>
			  		<ul class="active_levels_list"></ul>
			  	</div>
			  	<div class="left_col_elem">
			  		<h5><i class="glyphicon glyphicon-check"></i> Active Measures</h5>
			  		<hr>
			  		<ul class="active_measures_list"></ul>
			  	</div>
			  	<div class="left_col_elem">
			  		<h5><i class="glyphicon glyphicon-check"></i> Active Slices</h5>
			  		<hr>
			  		<ul class="active_slices_list"></ul>
			  	</div>

			  </div>
		  </div>

		  <div class="col-md-6">

		 	<div class="modal fade" id="myModal">
				<div class="modal-dialog">
			    	<div class="modal-content">
			      		<div class="modal-header">
			        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			       			<h4 class="modal-title">Slice</h4>
			      		</div>
			      	<div class="modal-body">
			        	<!-- Slice things -->
			        	<p>Please choose the value to use in the slice:</p>
			        	<div class="dropdown">
						    <button class="btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown"><span class="modal_button_attribute">Adsd</span>
						    <span class="caret"></span></button>
						    <ul class="dropdown-menu" id="slice-dropdown" role="menu" aria-labelledby="menu1">
						      
						    </ul>
						</div>
			      	</div>
			      	<div class="modal-footer">
			        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			      	</div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

		  	<div class="center_col">
		  		  	<table id="table" class="table" style="width:100%; text-align:left;">
		              <thead id="table_head">
		              <tr></tr>
		              </thead>
		              <tbody>
		              </tbody>
            		</table>	
		  	</div>
		  </div>

		  <div class="col-md-3">
			<div class="right_col_elem" id="dimensions_square">
		  		<h5><i class="glyphicon glyphicon-list"></i> Dimensions</h5>
		  		<hr>		  		  	
	  			<ul class="dimensions_list sub">		
	  			<?php foreach ($dim_info as $dim_id => $dim_data) :?>
	  				<li class="dimension accordion" id="<? print $dim_id ?>">
	  					<h5><i class="glyphicon glyphicon-chevron-right"></i> <? print $dim_data["name_dimension"] ?> </h5>
	  					<ul class="sub" style="display: none;">	  					
	  					<?php foreach ($dim_data["levels"] as $level_id=>$level) :?>
	  						<?php foreach ($level as $p_id => $p_value):?>
	  							<li class="level" id="<? print $p_id ?>"><i class="glyphicon glyphicon-move"></i> <? print $p_value ?></li>
	  						<?php endforeach;?>
	  					<?php endforeach ?>
	  					</ul>
	  				</li>
	  			<?php endforeach ?>
	  			</ul>
		  	</div>
		  	
			<div class="right_col_elem">
		  		<h5><i class="glyphicon glyphicon-tasks"></i> Measures</h5>
		  		<hr>
		  		
		  		<ul class="measures_list sub">
		  			<?php foreach ($measure_info as $measure_id => $measure_values) :?>
			  			<li class="measure_name" id="<? print $measure_id ?>">
			  				<h5><i class="glyphicon glyphicon-chevron-right"></i> <? print $measure_values[0] ?></h5>
			  				<ul>
		  					<?php foreach (array_slice($measure_values, 1) as $m_id => $m_name):?>
  								<li class="measure" id="<? print $m_id ?>"><i class="glyphicon glyphicon-move"></i> <? print $m_name ?></li>
  							<?php endforeach;?>
			  				</ul>
			  			</li>
			  		<?php endforeach  ?>	  		
		  		</ul>
		  	</div>
		  </div>		  
		</div>
	</div>
	</div>
  </body>
</html>
