<?php
/*
* This class tries to minimise moodle dependancy as much as possible
*/


//moodle globals
$cfg =new stdClass();
$cfg->wwwroot = 'someWebserver';
$GLOBALS['CFG'] = $cfg;

//moodle classes
class assign_feedback_plugin{

}


//some moodle functions
function get_config($name,$variable){
	
	$array = array();
	$array['assignfeedback_customfeedback'] = array('maxquestions' => 10 );


	return $array[$name][$variable];
	
}

?>