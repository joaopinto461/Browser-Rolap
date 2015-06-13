<?
	// $myxml = simplexml_load_file('xml/teste.xml');
	// #$myxml = simplexml_load_file('xml/exemplo_DW.xml');
	
	// echo $myxml->key;

	if(isset($_GET['cubes']))
	{
		$array = array("Cube 1", "Cube 2", "Cube 3");
		echo json_encode($array);
	}

	if(isset($_POST['cube']))
	{
		$cube = $_POST['cube'];
		//echo $cube;
	}
?>