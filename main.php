<?
	// $myxml = simplexml_load_file('xml/teste.xml');
	// #$myxml = simplexml_load_file('xml/exemplo_DW.xml');
	
	// echo $myxml->key;
function libxml_display_error($error)
{
    $return = "<br/>\n";
    switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $return .= "<b>Warning $error->code</b>: ";
            break;
        case LIBXML_ERR_ERROR:
            $return .= "<b>Error $error->code</b>: ";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "<b>Fatal Error $error->code</b>: ";
            break;
    }
    $return .= trim($error->message);
    if ($error->file) {
        $return .=    " in <b>$error->file</b>";
    }
    $return .= " on line <b>$error->line</b>\n";

    return $return;
}

function libxml_display_errors() {
    $errors = libxml_get_errors();
    foreach ($errors as $error) {
        print libxml_display_error($error);
    }
    libxml_clear_errors();
}

	libxml_use_internal_errors(true);
	$doc = new DOMDocument;
	$doc->load('xml/metadata.xml');

	if (!$doc->schemaValidate('xml/metadata.xsd')) {
	    print '<b>DOMDocument::schemaValidate() ::Generated Errors!</b>';
	    libxml_display_errors();
	}
	echo $doc->getElementById('tab_account')->getAttribute('name');;


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