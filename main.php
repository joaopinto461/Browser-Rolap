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
function getCubes()
{
    $doc = initializeDOM();
    $xml_cubes = $doc -> getElementsByTagName('cube');
    $cubes = [];

    foreach ($xml_cubes as $xc)
    {
        $data_name = $xc -> getAttribute('name');
        $data_id = $xc -> getAttribute('id');
        $cubes[$data_id] = $data_name;
    }
    return json_encode($cubes);
}

if(isset($_POST['cube']))
{
    $doc = initializeDOM();
    $cube_selected_id = $_POST['cube'];
    $dom_cube = $doc->getElementById($cube_selected_id);
    $cube_name = $dom_cube->getAttribute('name');

    $facts = $dom_cube->getElementsByTagName('fact');
    $facts_array = array();
    $measure_ref_dom = $dom_cube->getElementsByTagName('measure');

    foreach ($facts as $key) 
    {
        $column_ref = $key->getAttribute('column_ref');
        $column_name = $doc->getElementById($column_ref)->getAttribute('name');
        
        $measure_info = array();
        $measure_info[] = $column_name;
        foreach ($measure_ref_dom as $m)
        {
            $measure_info[$m->getAttribute('id')] = $m->getAttribute('display_name');
        }
        $facts_array[$column_ref] = $measure_info;
    }
    $measure_info = $facts_array;
    $dims = getDimensions($cube_selected_id);
}

    function getDimensions($cube_id)
    {
        $doc = initializeDOM();

        $cube_dom = $doc->getElementById($cube_id);
        $cube_dimensions = $cube_dom->getElementsByTagName('cube_dimension');
        $dimension_array = array();

        foreach ($cube_dimensions as $key => $value) 
        {
            $array_tmp = array();
            $dimension_id = $value->getAttribute('dimension_ref');
            
            $hierarchies = $doc->getElementById($dimension_id)->getElementsByTagName('hierarchy');
            $dimension_name = $doc->getElementById($dimension_id)->getAttribute('display_name');
            // var_dump($hierarchies);
            $hierarchies_array = array();
            
            foreach ($hierarchies as $key => $value) 
            {
                $hierarchies_levels = $value->getElementsByTagName('hierarchy_level');
                // echo "OLE";
                $hierarchy_name = $value->getAttribute('display_name');
                $hierarchy_id = $value->getAttribute('id');

                $levels_array = array();
                $levels_array[] = $hierarchy_name;

                foreach ($hierarchies_levels as $key => $value) 
                {
                    $level_ref = $value->getAttribute('level_ref');

                    $propertys = $doc->getElementById($level_ref)->getElementsByTagName('property');
                    $property = array();
                    foreach ($propertys as $key => $value) 
                    {
                        $property_name = $value->getAttribute('display_name');
                        $property_id = $value->getAttribute('id');
                        $property[$property_id] = $property_name; 
                        break;
                    }
                    $levels_array[] = $property;
                    // $property;
                }
                $hierarchies_array[$hierarchy_id] = $levels_array;
            }
            $array_tmp[] = $dimension_name;
            $array_tmp[] = $hierarchies_array;
            $dimensions_array[$dimension_id] = $array_tmp;
            // echo "<br>";

        }
        return $dimensions_array;
    }

    function extractXmlDataBd($doc)
    {
        $db_connection = $doc->getElementsByTagName('db_connection')->item(0);

        $db_host = $db_connection->getAttribute('host');
        $db_username = $db_connection->getAttribute('username');
        $db_pass = $db_connection->getAttribute('password');
        $db_instance = $db_connection->getAttribute('instance');
        $db = ["host" => $db_host, "username" => $db_username, "pass" => $db_pass, "instance" => $db_instance];
        return $db;
    }

    function dataToSlice($property_id)
    {
        $doc = initializeDOM();
        $db_data = extractXmlDataBd($doc);
        $db = db($db_data);

        $property_elem = $doc->getElementById($property_id);
        $column_ref = $property_elem->getAttribute('column_ref');
        $column_name = $doc->getElementById($column_ref)->getAttribute('name');
        $table_ref = $property_elem->parentNode->getAttribute('table_ref');
        $table_name = $doc->getElementById($table_ref)->getAttribute('name');

        $query = "SELECT DISTINCT(".$column_name.") FROM ".$table_name.";";

        $results = execQuery($query, $db);

        return json_encode($results);
    }

    function generateQuery ($json, $cubeid, $doc) 
    {

        $dom_cube = $doc->getElementById($cubeid);
        $fact_table_id = $dom_cube->getAttribute('table_ref');

        $json = json_decode($json);
        $array_levels = array();
        $array_measures = array();
        $array_slices = array();
        $array_filters = array();

        foreach ($json as $key =>$bla) 
        {
            foreach ($bla as $k=> $value) 
            {
                if($key == 'levels')
                    $array_levels[] = $value;
                elseif ($key == 'measures')
                    $array_measures[$k] = $value; 
                elseif($key == 'slices')
                    $array_slices[$k] = $value;
                elseif($key == 'filters')
                    $array_filters[$k] = $value;
            }    
        }
        // if(count($array_slices) > 0)
            $array_diff = array_diff_assoc($array_slices, $array_levels);

        $arrays_FROM = array();

        foreach ($array_levels as $key => $value) {
           $element_level_property = $doc->getElementById($value);
           $level_parent_property = $element_level_property->parentNode;
           $level_id_property_parent = $level_parent_property->getAttribute('id');

           $arrays_FROM[] = generateArrayFromSectionQuery($level_id_property_parent, $cubeid, $doc);
       }

       $arrays_FROM = array_unique($arrays_FROM, SORT_REGULAR);
       $array_final = array();
       foreach ($arrays_FROM as $key) {
        foreach ($key as $k) {                
            $array_final[] = $k;
        }   
    }
    $arrays_FROM = array_unique($array_final, SORT_REGULAR);
    $from = generateFromSectionQuery($arrays_FROM);

    $select = generateSelectSectionQuery($array_levels, $array_measures, $doc);

    $group_by = generateGroupBy($array_levels, $doc, $array_diff);
    $final_query = $select.$from;

    $final_query = $final_query.$group_by;

    $having_query = "";
    if(count($array_slices) > 0)
    {
        $having_query = getHavingSectionQuery($array_slices, $doc);
        
        if(count($array_filters) > 0)
        {
            $where_query = getHavingwithFiltersSectionQuery($array_filters, $doc, $having_query);
            $final_query = $final_query.$where_query;
        }
        else
            $final_query = $final_query.$having_query;
    }
    else
    {
        if(count($array_filters) > 0)
        {
            $where_query = getHavingwithFiltersSectionQuery($array_filters, $doc, $having_query);
            $final_query = $final_query.$where_query;
        }
    }

        // echo "<br><br>";
        // echo $final_query;
    return $final_query;
}

function getHavingwithFiltersSectionQuery($array_filters, $doc, $having_query)
{
    $where_array = array();
    if($having_query != "")
        $query = $having_query;
    else
        $query = "";
    $i = 0;

    foreach ($array_filters as $key => $value) 
    {
        $tmp_array = array();
        $name_column = $doc->getElementById($key)->getAttribute('name');

        foreach ($value as $key => $value)
        {
            if($i++ == 1)
                $tmp_array[] = $name_column;
            $tmp_array[] = $value;
        }
        $where_array[] = $tmp_array;
    }
    $i = 0;
    $num_items = count($where_array);
    foreach ($where_array as $key => $value) 
    {
        $operation = $doc->getElementById($value[0])->getAttribute('operation');
        if($having_query != "")
            $query = $query." AND ".$operation."(".$value[1].") ".$value[2]." ".$value[3];
        elseif($having_query == "")
            $query = " HAVING ".$query.$operation."(".$value[1].") ".$value[2]." ".$value[3];
        if(++$i != $num_items)
                $query = $query." AND ";
    }
    return $query;
}

function getHavingSectionQuery($array_slices, $doc)
{  
    
    $query = " HAVING ";
    $operator = " IN ";
    $values = "";
    $num_items_array = count($array_slices);
    $t = 0;
    foreach ($array_slices as $key => $value) 
    {
        $i = 0;
        // $num_items = count($value);
        $property_dom = $doc->getElementById($key);
        $parent_table = $property_dom->parentNode->getAttribute('table_ref');
        $parent_table = $doc->getElementById($parent_table)->getAttribute('name');
        $table_column_id = $property_dom->getAttribute('column_ref');
        $real_column_name = $doc->getElementById($table_column_id)->getAttribute('name');

        $num_items = count($value);
        $values = "";
        foreach ($value as $key => $v) 
        {
            // echo $v." ";
            
            $values = $values." '".$v."'";

            if(++$i != $num_items)
                $values = $values.", ";
        }

        $query = $query.$parent_table.".".$real_column_name.$operator." (".$values.")";

        if(++$t != $num_items_array)
                $query = $query." AND ";
    }
    // echo "<br><br>";
    // echo $query;
    return $query;
}

function generateGroupBy($array_levels, $doc, $array_diff)
{
    $num_items = count($array_levels);
    $i = 0;
    $query = " GROUP BY ";
    foreach ($array_levels as $key => $value) 
    {
        $property_dom = $doc->getElementById($value);
        $table_name = $property_dom->parentNode->getAttribute('table_ref');
        $table_name = $doc->getElementById($table_name)->getAttribute('name');
        $column_ref = $property_dom->getAttribute('column_ref');
        $column_name = $doc->getElementById($column_ref)->getAttribute('name');

        $query = $query.$table_name.".".$column_name;
        if(++$i != $num_items)
            $query = $query.", ";
    }      
    $j = 0; 
    if(count($array_diff) > 0)
    {
        $query = $query.", ";
        
        foreach ($array_diff as $key => $value) 
        {
            $property_doc = $doc->getElementById($key);
            $column_name = $doc->getElementById($property_doc->getAttribute('column_ref'))->getAttribute('name');
            $parent_ref_table = $property_doc->parentNode->getAttribute('table_ref');
            $table_name = $doc->getElementById($parent_ref_table)->getAttribute('name');
            $query = $query.$table_name.".".$column_name;
            if(++$j != count($array_diff))
                $query = $query.", ";       
        }
    }
    return $query;
}

function generateSelectSectionQuery($array_levels, $array_measures, $doc)
{
    $select = "SELECT ";
    $num_items = count($array_levels);
    $i = 0;
    $num_items_measures = count($array_measures);

    foreach ($array_levels as $key => $value) 
    {
        $property_dom = $doc->getElementById($value);
        $table_ref = $property_dom->parentNode->getAttribute('table_ref');
        $table_name = $doc->getElementById($table_ref)->getAttribute('name');
        $column_ref_property = $property_dom->getAttribute('column_ref');
        $column_name = $doc->getElementById($column_ref_property)->getAttribute('name');

        $select = $select.$table_name.".".$column_name;
        if(++$i != $num_items)
            $select = $select.", ";
        elseif($num_items_measures > 0)
            $select = $select.", ";
    }       
    $i = 0;
    $num_items_measures = count($array_measures);
    foreach ($array_measures as $key => $value) 
    {
        $tmp_array = array();
        foreach ($value as $key => $v) {
            $tmp_array[] = $v;
        }
        $column_name = $doc->getElementById($tmp_array[0])->getAttribute('name');
        $operation = $doc->getElementById($tmp_array[1])->getAttribute('operation');
        $select = $select.$operation."(".$column_name.")";
        if(++$i != $num_items_measures)
            $select = $select.", ";
    }
    return $select;
}

function generateFromSectionQuery($path_to_fact_table)
{
    $from_query= " FROM ";
    $state = 0;
    foreach ($path_to_fact_table as $section_of_path) 
    {   
        if($state == 0)
        {
            $from_query = $from_query.$section_of_path[0]." INNER JOIN ".$section_of_path[2]." ON ".$section_of_path[0].".".$section_of_path[1]." = ".$section_of_path[2].".".$section_of_path[3];
            $state = 1;
        }
        else if($state == 1)
            $from_query = $from_query." INNER JOIN ".$section_of_path[2]." ON ".$section_of_path[0].".".$section_of_path[1]." = ".$section_of_path[2].".".$section_of_path[3];
    } 
    return $from_query;    
}

function getResults($json, $cubeid) 
{
    $doc = initializeDOM();
    $db_data = extractXmlDataBd($doc);
    $db = db($db_data);
    $query = generateQuery($json, $cubeid, $doc);
    $results = execQuery($query, $db);
        // echo json_encode($results);
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
function generateArrayFromSectionQuery($level_id, $cubeid, $doc)
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

    return $path_to_fact_table;
}

    // $json2 = '{"levels":{"dimension_product_level_product_property_product_name":"dimension_product_level_product_property_product_name","dimension_product_level_product_category_property_family":"dimension_product_level_product_category_property_family"},"measures":{"cube_sales_1997_measure_sum_table_sales_fact_1997_column_store_sales":{"measure_attr":"table_sales_fact_1997_column_store_sales","aggregator":"cube_sales_1997_measure_sum"}},"slices":{"dimension_product_level_product_category_property_family":["Food"],"dimension_product_level_product_property_product_name":["Akron City Map","American Beef Bologna","American Corned Beef","American Foot-Long Hot Dogs","American Low Fat Bologna"]},"filters":{"table_sales_fact_1997_column_store_sales":{"measure":"cube_sales_1997_measure_sum","operator":">","value":"400"}}}';
    // $json1 = '{"levels":{"dimension_time_level_date_property_month":"dimension_time_level_date_property_month"},"measures":{"table_sales_fact_1997_column_store_sales":"cube_sales_1997_measure_sum"},"slices":{},"filters":{"table_sales_fact_1997_column_store_sales":{"measure":"cube_sales_1997_measure_sum","operator":">","value":"50000"}}}';

     // getResults($json2, "cube_sales_1997");
?>