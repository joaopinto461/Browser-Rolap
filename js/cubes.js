$(document).ready(function()
{	   
	$('.center_col').droppable
	(
	   {
	   		accept: '.level, .measure',
	   		hoverClass: "highlight",
		    drop: function(event, ui)
		    {
		        ui.draggable.data('dropped', true);		        

		        if(ui.draggable.attr('class').localeCompare("measure") > -1)
		        {
		        	var nm = ui.draggable.text();
		        	active_measures[nm] = nm;	
		        	fillActiveMeasures();		        	        
		        }	
		        else if(ui.draggable.attr('class').localeCompare("dimension") > -1)
		        {
		        	var nd = ui.draggable.text();
		        	active_levels[nd] = nd;
		        	fillActiveDimensions();		        	
		        }	        
		    }
		}
	);

	$('.level, .measure').draggable
	(
		{
		    revert: true,
		    helper: "clone"		    
		}
	);

	$('.sub').accordion({ collapsible: true, active: false });

});

var active_measures = {};
var active_levels = {};

function deleteElem(clicked_id)
{
	delete active_levels[clicked_id];
	delete active_measures[clicked_id];
	fillActiveDimensions();
	fillActiveMeasures();
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

function fillActiveMeasures()
{
	$('.active_measures_list').html("");		        	
	for(var y in active_measures)
	{
		var am = active_measures[y];			
		$('.active_measures_list').append('<li class="active_measure" id="' + am + '">' + '<i onClick="deleteElem(this.id)" class="active_measure glyphicon glyphicon-remove" id="' + am + '"></i> ' + am + '</li>');
	}
}