$(document).ready(function()
{	   
	$("#load").on("click", function()
	{
		$('#loadModal').modal('toggle');
	});

	$("#save").on("click", function()
	{
		$('#saveModal').modal('toggle');	
	});

	$('.selectpicker').selectpicker();
	$('.chartpicker').selectpicker();
	$('.filterpicker').selectpicker();
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
		        	active_measures[nm + "_" + ui.draggable.closest('.measure_name').attr('id')] = {measure_attr: ui.draggable.closest('.measure_name').attr('id'), aggregator: nm};
		        	fillActiveMeasures();	
		        }
		        
				updateActiveJSON();

	        	updateTableData();
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

	$('.sub').accordion({ collapsible: true, active: false, heightStyle: "content" });
	
	$( ".sub" ).accordion({
	    beforeActivate: function( event, ui )
	    {
	        $(ui.newHeader).find('i').toggleClass('glyphicon glyphicon-chevron-right glyphicon glyphicon-chevron-down');
	        $(ui.oldHeader).find('i').toggleClass('glyphicon glyphicon-chevron-right glyphicon glyphicon-chevron-down');
	    }
	});
	
	$.dynatableSetup(
	{
		features:
		{ 
			pushState: false, 
			search: false, 
			perPageSelect: false 
		}
	});

	$(".level").dblclick(function()
	{
		var property_id = this.id;
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
            			$('#slice-dropdown').append('<option id="' + property_id + '">' + obj[key] +'</option>');
        			}
    			} 

    			$('.selectpicker').selectpicker('refresh');   			
    		}
    	});

  		$('#sliceModal').modal('toggle');
	});

	$(".measure").dblclick(function()
	{
		measure_id = this.id;
		parent_id = $(this).data('parentid');

		$('#filterModal').modal('toggle');
	});


});

function processData(data)
{
	var info = clone(data);
	for(var x in info)
	{	
		var counter = 0;
		var key;
		for(var y in info[x])
		{	
			if(counter == 0)
			{
				info[x]['label'] = info[x][y].replace(/\"/g, "");
				delete info[x][y];
			}
			else if(counter == 1)
			{
				info[x]['y'] = parseFloat(info[x][y]);
				delete info[x][y];
			}

			counter++;
		}
	}

	return info;
}

var measure_id;
var parent_id;
var active_measures = {};
var active_levels = {};
var active_slices = {};
var active_filters = {};
var options = {};
var chart_type = 'column';

function toggleChart()
{
	$('#graphics').toggleClass('hidden');
	$('table').toggleClass('hidden');
	$('#chartTypeDiv').toggleClass('hidden');

	chart_type = $('.chartpicker option:selected').attr('name');

	updateTableData();

	if($('#chartButton').text() == 'See chart')
		$('#chartButton').text('See table');
	else
		$('#chartButton').text('See chart');
}

function loadInfo()
{
	loadedJSON = JSON.parse($('#loadText').val());
	active_levels = loadedJSON.levels;
	active_slices = loadedJSON.slices;
	active_measures = loadedJSON.measures;
	active_filters = loadedJSON.filters;
	fillActiveLevels();
	fillActiveSlices();
	fillActiveMeasures();
	fillActiveFilters();
	updateActiveJSON();
	updateTableData();
}

function saveInfo()
{
	updateActiveJSON();
	$('#saveText').val(active_json);
}

function clearData()
{
	active_measures = {};
	active_levels = {};
	active_slices = {};
	active_filters = {};
	fillActiveMeasures();
	fillActiveLevels();
	fillActiveSlices();
	fillActiveFilters();
	updateActiveJSON();
	$('.col-lg-12').html('<table id="table" class="table" style="text-align:left;"><thead id="table_head"><tr></tr></thead><tbody></tbody></table><div id="graphics" class="hidden"></div>');		
	$('.sub').accordion({ collapsible: true, active: false, heightStyle: "content" });
}

function applySlice()
{
	active_slices[$('.selectpicker option').attr('id')] = $('.selectpicker').val();
	fillActiveSlices();
	updateActiveJSON();
	updateTableData();
}

function applyFilter()
{
	var filterOperator = $('.filterpicker').val();
	var filterValue = $('#filtervalueinput').val();
	var mid = measure_id;
	var pid = parent_id;
	active_filters[parent_id] = {measure: measure_id, operator: filterOperator, value: filterValue};
	fillActiveFilters();
	updateActiveJSON();
	updateTableData();
}

function deleteMeasure(clicked_id)
{
	delete active_measures[clicked_id];
	updateActiveJSON();
	fillActiveMeasures();
	updateTableData();
}

function deleteSlice(clicked_id)
{
	delete active_slices[clicked_id];
	updateActiveJSON();
	fillActiveSlices();
	updateTableData();
}

function deleteLevel(clicked_id)
{
	delete active_levels[clicked_id];
	updateActiveJSON();
	fillActiveLevels();
	updateTableData();
}

function deleteFilter(clicked_id)
{
	delete active_filters[clicked_id];
	updateActiveJSON();
	fillActiveFilters();
	updateTableData();
}

function fillActiveSlices()
{
	$('.active_slices_list').html("");

	for(var s in active_slices)
	{
		var sid = s;
		var stext = active_slices[s]
		$('.active_slices_list').append('<li class="active_slice" id="' + sid + '">' + '<i onClick="deleteSlice(this.id)" class="active_slice glyphicon glyphicon-remove" id="' + sid + '"></i> ' + stext + '</li>');
	}
}

function fillActiveFilters()
{
	$('.active_filters_list').html("");

	for(var f in active_filters)
	{	
		var fid = f;
		var ftext = active_filters[f].operator + " " + active_filters[f].value;
		$('.active_filters_list').append('<li class="active_filter" id="' + fid + '">' + '<i onClick="deleteFilter(this.id)" class="active_filter glyphicon glyphicon-remove" id="' + fid + '"></i> ' + ftext + '</li>');
	}
}

function fillActiveLevels()
{
	$('.active_levels_list').html("");		        	
	for(var x in active_levels)
	{
		var adid = active_levels[x];
		var adtext = $('#' + active_levels[x]).text();
		$('.active_levels_list').append('<li class="active_level" id="' + adid + '">' + '<i onClick="deleteLevel(this.id)" class="active_level glyphicon glyphicon-remove" id="' + adid + '"></i> ' + adtext + '</li>');
	}
}

function fillActiveMeasures()
{
	$('.active_measures_list').html("");		        	
	for(var y in active_measures)
	{
		var amid = active_measures[y].measure_attr;
		var amtext = $('#' + active_measures[y].aggregator).text();
		$('.active_measures_list').append('<li class="active_measure" id="' + amid + '">' + '<i onClick="deleteMeasure(this.id)" class="active_measure glyphicon glyphicon-remove" id="' + y + '"></i> ' + amtext + '</li>');
	}
}

function addColumns(columns)
{
	$("#table_head>tr").html('');
	$("#table>tbody").html('');
	var pos = 0;
	for(var col in columns) 
	{
		slug = col;
		col = col.replace(/([A-Z])/g, ' $1').replace(/^./, function(str){return str.toUpperCase();})
		$("#table_head>tr").append('<th data-dynatable-column="'+slug+'">'+col+'</th>');
	}
}

function updateActiveJSON()
{
	active_json = JSON.stringify({levels: active_levels, measures: active_measures, slices: active_slices, filters: active_filters});
}

function updateTableData()
{	
	updateActiveJSON();
	
	if(active_json.length == 52)
	{
		// console.log("active_json was empty!");
		clearData();
	}
	else
	{
		// console.log("Sent AJAX request for following JSON:");
		// console.log(active_json);

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
			// console.log("Server's answer:");
			// console.log(data);

			if(data.length == 2)
			{
				$('.col-lg-12').html('<table id="table" class="table" style="text-align:left;"><thead id="table_head"><tr></tr></thead><tbody></tbody></table><div id="graphics" class="hidden"></div>');		
				alert("No data responds to active constraints.");
			}
			else
			{				
				var data_json = JSON.parse(data);
	
				options = 			
				{
					title:
					{
						text: "Results Chart"
					},
				    animationEnabled: true,
					data:
					[
						{
							type: chart_type, //change it to line, area, bar, pie, etc
							dataPoints: processData(data_json)
						}
					]
				};

				$("#graphics").CanvasJSChart(options);

				addColumns(data_json[0]);
	    		var t = $('#table').dynatable().data('dynatable');
	    		t.records.updateFromJson({records: data_json});
	    		t.domColumns.init();
	    		t.sortsHeaders.init();
	    		t.records.init();
	    		t.process();
			}
		}
		});
	}
}

function clone(obj){
    if(obj == null || typeof(obj) != 'object')
        return obj;

    var temp = new obj.constructor(); 
    for(var key in obj)
        temp[key] = clone(obj[key]);

    return temp;
}
