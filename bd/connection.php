<?php
include 'PDO.php';

function startDBconnection($db_data){
	$SQL = new MySQL($db_data);
	$DBH = $SQL->Manager(); 
	return $DBH;
}
?>
