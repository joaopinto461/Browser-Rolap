<?php
include "main.php";
switch ($_POST["action"]) {
	case 'level': 
		print getResults($_POST['json'], $_POST['cube_id']);
		break;
	case 'slice':		
		print dataToSlice($_POST['property']);
		break;
}
?>