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

        $element_level = $doc->getElementById($level_id);

        $table_level = $element_level->getAttribute('table_ref'); //id da tabela que o level referencia

        
        $level_column_ref = $element_level->getElementsByTagName('property')[0]->getAttribute('column_ref');
        $level_group_by = $element_level -> getAttribute('group_by');
        $level_display = $element_level -> getAttribute('display_by');

        $group_by_name = $doc->getElementById($level_group_by)->getAttribute('name'); // nome do atributo sql p group by
        $display_by_name = $doc->getElementById($level_display)->getAttribute('name'); // nome atributo sql para display

        $from_section = generateFromSectionQuery($level_id, $cubeid, $doc);
        $query_result = "SELECT ".$display_by_name.$from_section."  GROUP BY ".$group_by_name.";";
        //var_dump($query_result);
        return $query_result;
    }

    function getResultsByLevel($levelid, $cubeid) {
        $doc = initializeDOM();
        $db_data = extractXmlDataBd($doc);
        $db = db($db_data);
        $query = generateQuery($levelid, $cubeid, $doc);
        $results = execQuery($query, $db);
        return json_encode($results);
    }

    function getResultsByMeasure($measureid) {
        $db_data = extractXmlDataBd($doc);
        $db = db($db_data);
        $doc = $initializeDOM();
        //$query = generateQuery($measureid, $doc);
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

    function getForeignKeyFromTable($table_src, $table_ref, $doc)
    {
        $table_src_fks = $doc->getElementById($table_src)->getElementsByTagName('key_ref');
        $fks=[];

        foreach ($table_src_fks as $fk) {
            $table_ref_by_fk = $fk->getAttribute('table_ref');

            if($table_ref_by_fk == $table_ref)
            { 
                $fks[] = $doc->getElementById($fk->getAttribute('table_ref_src'))->getAttribute('name');
                $fks[] = $doc->getElementById($fk->getAttribute('column_ref_src'))->getAttribute('name');
                $fks[] = $doc->getElementById($fk->getAttribute('table_ref'))->getAttribute('name');
                $fks[] = $doc->getElementById($fk->getAttribute('column_ref'))->getAttribute('name'); 
            }
        }     
        return $fks;    
    }
    function generateFromSectionQuery($level_id, $cubeid, $doc)
    {
        $fact_table_name = $doc->getElementById($cubeid)->getAttribute('table_ref');
        $level_dom_element = $doc->getElementById($level_id);
        $table_refered_by_level = $level_dom_element->getAttribute('table_ref');
        $sections_from = [];

        $tmp_table_refered_by_level = $table_refered_by_level;
        while(true)
        { 
            $total_fks = getForeignKeyFromTable($fact_table_name, $tmp_table_refered_by_level, $doc);

            if($total_fks == null)
            {
                $upper_level = $level_dom_element->getAttribute('upper_level');
                $table_name_upper_level = $doc->getElementById($upper_level)->getAttribute('table_ref');
                $fks_inner_joins = getForeignKeyFromTable($table_name_upper_level, $tmp_table_refered_by_level, $doc);

                $sections_from[]= $fks_inner_joins;
                $tmp_table_refered_by_level = $table_name_upper_level;
            }
            else
            {
                $sections_from[] = $total_fks;
                break;
            }
        }
        for ($i = sizeof($sections_from) - 1; $i >= 0; $i--) {
            $tmp[] = $sections_from[$i];
        }

        $path_to_fact_table = $tmp; 
        $from_query= " FROM ";
        $state = 0;
        foreach ($path_to_fact_table as $section_of_path) 
        {   if($state == 0)
            {
                $from_query = $from_query.$section_of_path[0]." INNER JOIN ".$section_of_path[2]." ON ".$section_of_path[0].".".$section_of_path[1]." = ".$section_of_path[2].".".$section_of_path[3];
                $state = 1;
            }
            else if($state == 1)
                $from_query = $from_query." INNER JOIN ".$section_of_path[2]." ON ".$section_of_path[0].".".$section_of_path[1]." = ".$section_of_path[2].".".$section_of_path[3];
        } 
        return $from_query;    
    }
    //$doc = initializeDOM();
    //generateFromSectionQuery("dimension_product_level_product_subcategory", "cube_sales_1997", $doc);
    generateQuery("dimension_product_level_product_name", "cube_sales_1997", $doc);
?>