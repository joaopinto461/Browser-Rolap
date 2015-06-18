<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Data Warehousing</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/cube.css" rel="stylesheet">
    <link href="css/jquery.dynatable.css" rel="stylesheet">
    <link href="css/bootstrap-select.css" rel="stylesheet">
    <script src="js/jQuery.js"></script>
    <script src="js/jQuery-ui.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/cubes.js"></script>
    <script src="js/jquery.dynatable.js"></script>
    <script src="js/bootstrap-select.js"></script>
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
	         <li><a href="#" onClick="clearData()" id="clear">Clear session data</a></li>
	         <li><a href="#" onClick="saveInfo()" id="save">Save session</a></li>
	         <li><a href="#" id="load">Load data</a></li>
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
			  	<div class="left_col_elem">
			  		<h5><i class="glyphicon glyphicon-check"></i> Active Filters</h5>
			  		<hr>
			  		<ul class="active_filters_list"></ul>
			  	</div>
			  </div>
		  </div>

		  <div class="col-md-6">
		 	<div class="modal fade" id="sliceModal">
				<div class="modal-dialog">
			    	<div class="modal-content">
			      		<div class="modal-header">
			        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			       			<h4 class="modal-title">Slice</h4>
			      		</div>
			      	<div class="modal-body">
			        	<!-- Slice things -->
			        	<p>Please choose the value to use in the slice:</p>
			        	<div>						   						   
						    <select id="slice-dropdown" name="named[]" class="selectpicker" multiple>
								  
							</select>					    
						</div>
			      	</div>
			      	<div class="modal-footer">			        	
			        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			        	<button type="button" onClick="applySlice()" id="applySliceButton" class="btn btn-primary" data-dismiss="modal">Apply</button>
			      	</div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

			<div class="modal fade" id="filterModal">
				<div class="modal-dialog">
			    	<div class="modal-content">
			      		<div class="modal-header">
			        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			       			<h4 class="modal-title">Filter</h4>
			      		</div>
			      	<div class="modal-body">
			        	<!-- Filter things -->
			        	<p>Please choose the options to use in the filter:</p>
			        	<div class="input-group">
				        	<select class="filterpicker">
							    <option>=</option>
							    <option>></option>
							    <option><</option>
							    <option>>=</option>
							    <option><=</option>
							</select>
							<input type="text" id="filtervalueinput" class="form-control" placeholder="Insert value" aria-describedby="basic-addon1">
						</div>
			      	</div>
			      	<div class="modal-footer">			        	
			        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			        	<button type="button" onClick="applyFilter()" id="applyFilterButton" class="btn btn-primary" data-dismiss="modal">Apply</button>
			      	</div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

			<div class="modal fade" id="loadModal">
				<div class="modal-dialog">
			    	<div class="modal-content">
			      		<div class="modal-header">
			        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			       			<h4 class="modal-title">Load data</h4>
			      		</div>
			      	<div class="modal-body">
			        	<!-- Filter things -->
			        	<p>Please write/paste your saved data:</p>
						<form>
						  <div class="form-group">
							<textarea id="loadText" class="form-control" rows="3"></textarea>
						  </div>						  						 
						</form>
			      	</div>
			      	<div class="modal-footer">			        	
			        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			        	<button type="button" onClick="loadInfo()" id="loadInfoButton" class="btn btn-primary" data-dismiss="modal">Apply</button>
			      	</div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

			<div class="modal fade" id="saveModal">
				<div class="modal-dialog">
			    	<div class="modal-content">
			      		<div class="modal-header">
			        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			       			<h4 class="modal-title">Save session</h4>
			      		</div>
			      	<div class="modal-body">
			        	<!-- Filter things -->
			        	<p>If you want to continue this session later, please save the text below.</p>			        	
						<form>
						  <div class="form-group">
							<textarea id="saveText" class="form-control" rows="3" onclick="this.focus();this.select()" readonly="readonly"></textarea>
						  </div>						  						 
						</form>
			      	</div>
			      	<div class="modal-footer">			        	
			        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			      	</div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

		  	<div class="center_col row">
			  	<div class="col-lg-12" style="overflow:auto">		  
		  		  	<table id="table" class="table" style="text-align:left;">
		              <thead id="table_head">
		              <tr></tr>
		              </thead>
		              <tbody>
		              </tbody>
	        		</table>	
	        	</div>
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
  								<li class="measure" id="<? print $m_id ?>" data-parentid="<? print $measure_id ?>"><i class="glyphicon glyphicon-move"></i> <? print $m_name ?></li>
  							<?php endforeach ?>
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
