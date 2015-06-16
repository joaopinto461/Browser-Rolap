<?
include "bd/connection.php";
    function libxml_display_error($error)
    {
        $return = "<br/>\n";
        switch ($error->level)
        {
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
        if ($error->file)
        {
            $return .=    " in <b>$error->file</b>";
        }

        $return .= " on line <b>$error->line</b>\n";

        return $return;
    }

    function libxml_display_errors()
    {
        $errors = libxml_get_errors();
        foreach ($errors as $error)
        {
            print libxml_display_error($error);
        }
        libxml_clear_errors();
    }

	libxml_use_internal_errors(true);
	$doc = new DOMDocument;
	$doc->load('xml/metadataDW.xml');

	if (!$doc->schemaValidate('xml/metadataDW.xsd'))
    {
	    print '<b>DOMDocument::schemaValidate() ::Generated Errors!</b>';
	    libxml_display_errors();
	}

	if(isset($_GET['cubes']))
	{
        $xml_cubes = $doc -> getElementsByTagName('cube');
        $cubes = [];

        foreach ($xml_cubes as $xc)
        {
            $data_name = $xc -> getAttribute('name');
            $data_id = $xc -> getAttribute('id');
            $cubes[$data_id] = $data_name;
        }
		echo json_encode($cubes);
	}

	else if(isset($_POST['cube']))
	{
		$cube_selected_id = $_POST['cube'];
        $dom_cube = $doc->getElementById($cube_selected_id);
        $cube_name = $dom_cube->getAttribute('name');
        
        $dimension_ref_dom = $dom_cube->getElementsByTagName('cube_dimension');
        $dim_info = [];

        foreach ($dimension_ref_dom as $dim_ref_dom)
        {
            $ref_dim = $dim_ref_dom->getAttribute('dimension_ref'); // ID dimension
            $dom_dim = $doc->getElementById($ref_dim);
            $dimension_name = $dom_dim->getAttribute('display_name'); // Name dimension
            $dom_dim_hierarchies = $dom_dim->getElementsByTagName('hierarchy');

            foreach ($dom_dim_hierarchies as $dom_hierarchy)
            {
                $dom_hierarchies_levels = $dom_hierarchy->getElementsByTagName('hierarchy_level');
                $levels = [];

                foreach ($dom_hierarchies_levels as $hierarchy_level)
                {
                    $hierarchy_level_ref = $hierarchy_level->getAttribute('level_ref');// ID level
                    $properties = $doc->getElementById($hierarchy_level_ref)->getElementsByTagName('property');
                    $prop_names = [];
                    
                    foreach ($properties as $p)
                    {
                        $prop_names[] = $p -> getAttribute('display_name');
                    }
                    $levels[$hierarchy_level_ref] = $prop_names;
                }
            }
            
            $dim_info[$dom_dim->getAttribute('id')] = ["name_dimension" => $dom_dim->getAttribute('display_name'), "levels" => $levels];
        }
        //echo json_encode($dim_info);

        $measure_ref_dom = $dom_cube->getElementsByTagName('measure');
        $measure_info = [];

        foreach ($measure_ref_dom as $m)
        {
            $measure_info[$m->getAttribute('id')] = $m->getAttribute('display_name');
        }

        /* Array c dimensions e measures */
        $dimensions_measures = ["dimensions" => $dim_info, "measures" => $measure_info];
	}

    function extractXmlDataBd($doc){
        $db_connection = $doc->getElementsByTagName('db_connection')->item(0);

        $db_host = $db_connection->getAttribute('host');
        $db_username = $db_connection->getAttribute('username');
        $db_pass = $db_connection->getAttribute('password');
        $db_instance = $db_connection->getAttribute('instance');
        $db = ["host" => $db_host, "username" => $db_username, "pass" => $db_pass, "instance" => $db_instance];
        return $db;
    }

    function generateQuery ($level_id, $cubeid, $doc) {
        $dom_cube = $doc->getElementById($cubeid);
        $fact_table_id = $dom_cube->getAttribute('table_ref');


        $fact_table_name = $doc->getElementById($fact_table_id)->getAttribute('name'); // nome sql

        $element_level = $doc->getElementById($level_id);
        $table_level = $element_level->getAttribute('table_ref');
        $table_level_name = $doc->getElementById($table_level)->getAttribute('name'); // tabela sql p join

        $level_column_ref = $element_level->getElementsByTagName('property')[0]->getAttribute('column_ref');
        $level_group_by = $element_level -> getAttribute('group_by');
        $level_display = $element_level -> getAttribute('display_by');

        $group_by_name = $doc->getElementById($level_group_by)->getAttribute('name'); // nome do atributo sql p group by
        $display_by_name = $doc->getElementById($level_display)->getAttribute('name'); // nome atributo sql para display
        
        $fact_foreign_keys = $doc->getElementById($fact_table_id)->getElementsByTagName('key_ref');

        foreach ($fact_foreign_keys as $ffk)
        {
            $r = $ffk -> getAttribute('table_ref');
            
            if($r == $table_level)
            {
                $cr_s = $ffk -> getAttribute('column_ref_src'); // fact table column foreign key
                $cr = $ffk -> getAttribute('column_ref'); // primary key
                break;
            }      
        }

        $fk_name = $doc->getElementById($cr_s)->getAttribute('name');
        $pk_name = $doc->getElementById($cr)->getAttribute('name');

        $query_result = "select ".$display_by_name." from ".$fact_table_name." inner join ".
            $table_level_name." on ".$fact_table_name.".".$pk_name." = ".$table_level_name.".".$fk_name." group by ".$group_by_name.";";

        var_dump($query_result);
        return $query_result;
    }

    function getResultsByLevel($levelid, $cubeid) {
        $db_data = extractXmlDataBd($doc);
        $db = db($db_data);

        $query = generateQuery($levelid, $cubeid);
        $results = execQuery($query);
        return json_encode($results);
    }

    function getResultsByMeasure($measureid) {
        $db_data = extractXmlDataBd($doc);
        $db = db($db_data);
        $query = generateQuery($measureid);
        $results = execQuery($query);
        return json_encode($results);
    }

    function initializeDOM()
    {
        libxml_use_internal_errors(true);
        $doc = new DOMDocument;
        $doc->load('xml/metadataDW.xml');

        if (!$doc->schemaValidate('xml/metadataDW.xsd'))
        {
            print '<b>DOMDocument::schemaValidate() ::Generated Errors!</b>';
            libxml_display_errors();
        }

        return $doc;
    }
    $doc = initializeDOM();
    generateQuery("dimension_product_level_product_name", "cube_sales_1997", $doc);
?>