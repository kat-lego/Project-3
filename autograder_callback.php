<?php
require_once('../../../../config.php');
require_once($CFG->dirroot . '/mod/assign/locallib.php');
require_once($CFG->libdir . '/gradelib.php');
require_once("locallib.php");
$inputJSON = file_get_contents('php://input');  // Get input from the client
$params = json_decode($inputJSON, TRUE);
$cmid = $_REQUEST['cmid'];
$auth = $params['customfeedback_token'];
$data = json_decode(strval($params["grades"]));

if($auth != get_config('assignfeedback_customfeedback', 'secret')){	
	
	die('{"status" : "Bad Auth"}');

}


list ($course, $cm) = get_course_and_cm_from_cmid($cmid, 'assign');
// die(var_dump($cmid));

$context = context_module::instance($cm->id);
$assign = new assign($context, $cm, $course);

$plugin = $assign->get_feedback_plugin_by_type("customfeedback");
if(!$plugin->is_enabled()){
	var_dump('{"status" : "Assignment does not use customfeedback"}');
	die('{"status" : "Assignment does not use customfeedback"}');//PLUGIN NOT ENABLED
}

$update=$plugin->update_grades($data);
if($update===true){
die('{"status" : "0"}');//SUCCESS
}
die('{"status":"-1"}');//FAILURE

?>
