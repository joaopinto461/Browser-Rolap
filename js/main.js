$(document).ready(function(){

	
    $.get("main.php", function(data, status){
        //alert("Data: " + data + "\nStatus: " + status);
        var received = JSON.parse(data);

        
        /* Add text (cubes) to dropdown in home */
        var counter = 0;
		for(var x in received)
		{	
			$('.dropdown-menu').append('<li><a href="#" id="action-' + counter + '">' + received[x] + '</a></li>');
			
			if(counter < received.length-1)
			{
				$('.dropdown-menu').append('<li class="divider"></li>');
			}

			counter++;
		}
	
	


		


    });






});