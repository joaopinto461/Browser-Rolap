<?php
include "main.php";
switch ($_POST["action"]) {
	case 'level': 
		print json_encode([['nome'=>'Alberto','idade' => 23],["nome"=>"João","idade"=>23]]);
		break;
	case 'measure': 
		print getResultsByMeasure($_POST['measure_id']);
		break;
}