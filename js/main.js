$(document).ready(function(){

	alert("lol");
    $.get("http://main.php", function(data, status){
        alert("Data: " + data + "\nStatus: " + status);
    });






});