<?php
include "main.php";

if($_POST)
{
	switch ($_POST["action"]) 
	{
		case 'level': 
			print getResults($_POST['json'], $_POST['cube_id']);
			break;
		case 'slice':		
			print dataToSlice($_POST['property']);
			break;
	}
}
elseif($_GET)
{
	switch($_GET["action"])
	{
		case 'getCubes':
			print getCubes();
			break;
	}
}
?>