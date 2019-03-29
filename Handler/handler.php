<?php


$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);        
$source = base64_decode($input['source']);     
$mode=$input["mode"];

if(!isset($input["mode"])){
	die("No mode selected");
}

/**
*do stuff based on the mode type
*output contains the grade and whether the output of the code is correct
*/
$output= array('grade' =>random_int(0, 100),'passed'=>TRUE);


echo json_encode($output);
?>

