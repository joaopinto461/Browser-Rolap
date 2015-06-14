$(document).ready(function()
{	   
	fillDimensions();
	fillMeasures();
	fillLevels();

	$('.center_col').droppable
	(
	   {
	   		accept: '.level',
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
		        	active_levels[nd] = nd;
		        	fillActiveDimensions();		        	
		        }	        
		    }
		}
	);

	$('.level').draggable
	(
		{
		    revert: true,
		    helper: "clone"		    
		}
	);

	$('.sub').accordion();

});

var active_measures = {};
var active_levels = {};

var dimensions = ["Product Dimension", "Time Dimension", "Store Dimension"];
var measures = ["Units Ordered", "Sales", "Cost"];
var levels = ["Level 1", "Level 2"];

function deleteElem(clicked_id)
{
	delete active_levels[clicked_id];
	fillActiveDimensions();
}

function fillDimensions()
{
	for(var x in dimensions)
	{
		var d = dimensions[x];
		$('.dimensions_list').append('<li class="dimension accordion" id="' + d + '">' + '<h5><i class="glyphicon glyphicon-list"></i> ' + d + '</h5><ul class="sub" style="display: none;"></ul></li>');
	}
}

function fillLevels()
{
	for(var l in levels)
	{
		$('.dimension ul').append('<li class="level" + id="' + levels[l] + '">' + '<i class="glyphicon glyphicon-move"></i> ' + levels[l] +'</li>')
	}
}

function fillActiveDimensions()
{
	$('.active_levels_list').html("");		        	
	for(var x in active_levels)
	{
		var ad = active_levels[x];			
		$('.active_levels_list').append('<li class="active_level" id="' + ad + '">' + '<i onClick="deleteElem(this.id)" class="active_level glyphicon glyphicon-remove" id="' + ad + '"></i> ' + ad + '</li>');
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