<?php
include "main.php";
switch ($_POST["action"]) {
	case 'level': 
		// print json_encode([["nome"=>'Alberto','idade' => 23],["nome"=>"João","idade"=>23]]);
		print getResultsByLevel($_POST['json'], $_POST['cube_id']);
		break;
	case 'slice':		
		print dataToSlice($_POST['property']);
		break;
}
?>