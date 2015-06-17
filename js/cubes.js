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

		        var type = ui.draggable.attr('class');		       		      
		        
		        if(type.indexOf('level') > -1)
		        {
		        	var nd = ui.draggable.attr('id');
	        		active_levels[nd] = nd;
	        		fillActiveLevels();	
		        }
		        else
		        {
		        	var nm = ui.draggable.attr('id');		      		        			        	
		        	active_measures[ui.draggable.closest('.measure_name').attr('id')] = nm;
		        	fillActiveMeasures();	
		        }
		        
	        	active_json = JSON.stringify({levels: active_levels, measures: active_measures});
	        	console.log(active_json);

	        	$.ajax(
	        	{
	        		method: "POST",
	        		async: false,
	        		url: "ajax.php",
	        		data:
	        		{
	        			"json": active_json,
	        			"action": "level",
	        			"cube_id": $('.cube_title').attr('id')
	        		},
	        		success: function(data)
	        		{
	        			var data_json = JSON.parse(data);
	        			addColumns(data_json[0]);
	        			console.log(data);
		        		$('#table').dynatable(
		        		{
			        		dataset:
			        		{
			        			records: data_json
			        		}
		        		});
		        		
	        		}
	        	});
		    }
		}
	);
	
	$('.level, .measure').draggable
	(
		{
		    revert: false,
		    helper: "clone"		    
		}
	);

	$('.sub').accordion({ collapsible: true, active: false });
	
	$( ".sub" ).accordion({
	    beforeActivate: function( event, ui )
	    {
	        $(ui.newHeader).find('i').toggleClass('glyphicon glyphicon-chevron-right glyphicon glyphicon-chevron-down');
	        $(ui.oldHeader).find('i').toggleClass('glyphicon glyphicon-chevron-right glyphicon glyphicon-chevron-down');
	    }
	});
	
	$.dynatableSetup({features: { pushState: false, search: false, perPageSelect: false }});

	$( ".level" ).dblclick(function()
	{
		$.ajax(
    	{
    		method: "POST",
    		async: false,
    		url: "ajax.php",
    		data:
    		{
    			"property": this.id,
    			"action": "slice",
    			"cube_id": $('.cube_title').attr('id')
    		},
    		success: function(data)
    		{    			
    			var slice_json = JSON.parse(data);
    			$('#slice-dropdown').html("");

    			for(var i=0; i < slice_json.length; i++)
    			{
        			var obj = slice_json[i];
        			
        			for(var key in obj)
        			{            			            			
            			$('#slice-dropdown').append('<li role="presentation"><a role="menuitem" tabindex="-1" href="#">' + obj[key] + '</a></li>');
        			}
    			}    			
    		}
    	});

  		$('#myModal .modal_button_attribute').html($('#' + this.id).text());
  		$('#myModal').modal('toggle');
	});

});

var active_measures = {};
var active_levels = {};

function deleteElem(clicked_id)
{
	delete active_levels[clicked_id];
	delete active_measures[clicked_id];
	active_json = JSON.stringify({levels: active_levels, measures: active_measures});
	fillActiveLevels();
	fillActiveMeasures();
}

function fillActiveLevels()
{
	$('.active_levels_list').html("");		        	
	for(var x in active_levels)
	{
		var adid = active_levels[x];
		var adtext = $('#' + active_levels[x]).text();
		$('.active_levels_list').append('<li class="active_level" id="' + adid + '">' + '<i onClick="deleteElem(this.id)" class="active_level glyphicon glyphicon-remove" id="' + adid + '"></i> ' + adtext + '</li>');
	}
}

function fillActiveMeasures()
{
	$('.active_measures_list').html("");		        	
	for(var y in active_measures)
	{
		var amid = y;
		var amtext = $('#' + active_measures[y]).text();
		$('.active_measures_list').append('<li class="active_measure" id="' + amid + '">' + '<i onClick="deleteElem(this.id)" class="active_measure glyphicon glyphicon-remove" id="' + amid + '"></i> ' + amtext + '</li>');
	}
}

function addColumns(columns)
{
	$("#table_head>tr").html('');
	for(var col in columns) 
	{
		col = col.replace(/([A-Z])/g, ' $1').replace(/^./, function(str){return str.toUpperCase();})
		$("#table_head>tr").append('<th id="id1">'+col+'</th>');
	}
}
