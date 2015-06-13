$(document).ready(function()
{	   
	fillDimensions();
	fillMeasures();

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
		        	fillActiveDimensions();		        	
		        }	        
		    }
		}
	);

	$('li').draggable
	(
		{
		    revert: true,
		    helper: "clone"		    
		}
	);
	
});

var active_measures = {};
var active_dimensions = {};

var dimensions = ["Product Dimension", "Time Dimension", "Store Dimension"];
var measures = ["Units Ordered", "Sales", "Cost"];	

function deleteElem(clicked_id)
{
	delete active_dimensions[clicked_id];
	fillActiveDimensions();
}

function fillDimensions()
{
	for(var x in dimensions)
	{
		var d = dimensions[x];
		$('.dimensions_list').append('<li class="dimension" id="' + d + '">' + '<i class="glyphicon glyphicon-list"></i> ' + d + '</li>');
	}
}

function fillActiveDimensions()
{
	$('.active_dimensions_list').html("");		        	
	for(var x in active_dimensions)
	{
		var ad = active_dimensions[x];			
		$('.active_dimensions_list').append('<li class="active_dimension" id="' + ad + '">' + '<i onClick="deleteElem(this.id)" class="active_dimension glyphicon glyphicon-remove" id="' + ad + '"></i> ' + ad + '</li>');
	}
}

function fillMeasures()
{
	for(var x in measures)
	{
		var m = measures[x];
		$('.measures_list').append('<li class="measure" id="' + m + '">' + '<i class="glyphicon glyphicon-tasks"></i> ' + m + '</li>');
	}
}

function fillActiveMeasures()
{
	
}