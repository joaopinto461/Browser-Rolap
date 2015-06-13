$(document).ready(function()
{	   
	/* Receive measures from server in array */
	var measures = ["Units Ordered", "Sales", "Cost"];

	for(var x in measures)
	{
		var m = measures[x];
		$('.measures_list').append('<li class="measure" id="' + m + '">' + '<i class="glyphicon glyphicon-tasks"></i> ' + m + '</li>');
	}

	var dimensions = ["Product Dimension", "Time Dimension", "Store Dimension"];

	for(var x in dimensions)
	{
		var d = dimensions[x];
		$('.dimensions_list').append('<li class="dimension" id="' + d + '">' + '<i class="glyphicon glyphicon-list"></i> ' + d + '</li>');
	}

	$('.center_col').droppable
	(
	   {
	   		accept: 'li',
	   		hoverClass: "highlight",
		    drop: function(event, ui)
		    {
		        ui.draggable.data('dropped', true);		        

		        if(ui.draggable.attr('class').localeCompare("measure") > -1)
		        {
		        	var nm = ui.draggable.attr('id');
		        	active_measures[nm] = nm;		        	
		        }	
		        else if(ui.draggable.attr('class').localeCompare("dimension") > -1)
		        {
		        	var nd = ui.draggable.attr('id');
		        	active_dimensions[nd] = nd;
		        	$('.active_dimensions_list').html("");
		        	
		        	for(var x in active_dimensions)
					{
						var ad = active_dimensions[x];			
						$('.active_dimensions_list').append('<li class="active_dimension" id="' + ad + '">' + '<i class="glyphicon glyphicon-remove"></i> ' + ad + '</li>');
					}
		        }

		        // for(var b in active_measures)
		        // {
		        // 	console.log(active_measures[b]);
		        // }		        
		    }
		}
	);

	$('li').draggable
	(
		{
		    revert: true,
		    helper: "clone"		    
		}
	)	   
});

var active_measures = {};
var active_dimensions = {};



