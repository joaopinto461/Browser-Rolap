<?php
function db ($db_data) {
	$db = mysqli_connect($db_data['host'], $db_data['username'], $db_data['pass'], $db_data['instance']);
	if (mysqli_connect_errno())
		return NULL;
	return $db;
}

function execQuery ($query) {
	$db = db();
	if ($db == NULL)
		exit();
	$result = mysqli_query($db, $query);
	$result_to_json = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$result_to_json[] = $row;
	}
	mysqli_free_result($result);
	mysqli_close($db);
}	

?>
