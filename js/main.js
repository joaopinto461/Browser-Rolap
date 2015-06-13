$(document).ready(function(){

	
    $.get("main.php", { cubes : "" }, function(data, status)
    {
        //alert("Data: " + data + "\nStatus: " + status);
        var received = JSON.parse(data);

        
        /* Add text (cubes) to dropdown in home */
        var counter = 0;
		for(var x in received)
		{	
			var rc = received[x];
			$('.dropdown-menu').append('<li><a href="#" id="' + rc + '" onClick="reply_click(this.id)">' + rc + '</a></li>');
			
			if(counter < received.length-1)
			{
				$('.dropdown-menu').append('<li class="divider"></li>');
			}

			counter++;
		}

    });

});

function reply_click(clicked_id)
{
	$('input').attr({'value': clicked_id});
	$('form').submit();
}