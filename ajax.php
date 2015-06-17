<?php
include "main.php";
switch ($_POST["action"]) {
	case 'level': 
		// print json_encode([["nome"=>'Alberto','idade' => 23],["nome"=>"João","idade"=>23]]);
		print getResultsByLevel($_POST['json'], $_POST['cube_id']);
		break;
	case 'measure':
		print json_encode([["nomeC"=>'Alberto','idadeC' => 23],["nomeC"=>"João","idadeC"=>23]]);
		// print getResultsByMeasure($_POST['measure_id']);
		break;
}
?>