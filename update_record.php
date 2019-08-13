<?php
require_once('../../../../config.php');
require_once($CFG->dirroot . '/mod/assign/locallib.php');
require_once($CFG->libdir . '/gradelib.php');
require_once("locallib.php");
$inputJSON = file_get_contents('php://input');  // Get input from the client
$params = json_decode($inputJSON, TRUE);
$assign_id = $_REQUEST["assign_id"];
$question_id=$_REQUEST["question_id"];
$auth = $params['customfeedback_token'];

if($auth != get_config('assignfeedback_customfeedback', 'secret')){	

	die('{"status" : "Bad Auth"}');

}

if(!isset($_REQUEST["assign_id"])or !isset($_REQUEST["question_id"])){
	die('{"status":"missing parameters"}');
}
//die(var_dump($assign_id));
$markerid = intval($params['markerid']);
$userid = intval($params['userid']);
$newgrade = floatval($params['grade']);
$status = intval($params['status']);
$time=floatval($params['time']);
$memory=floatval($params['memory']);
$score = floatval($params["score"]);

$oj_testcases=$params["oj_testcases"];
list ($course, $cm) = get_course_and_cm_from_cmid($assign_id, 'assign');
//die(var_dump("ds"));
$context = context_module::instance($cm->id);
$assign = new assign($context, $cm, $course);

$plugin = $assign->get_feedback_plugin_by_type("customfeedback");
if(!$plugin->is_enabled()){
	var_dump('{"status" : "Assignment does not use customfeedback"}');
	die('{"status" : "Assignment does not use customfeedback"}');//PLUGIN NOT ENABLED
}

$update=$plugin->update_record($question_id,$assign_id,$userid,$memory,$time,$status,$newgrade,$score,$oj_testcases);
if($update===true){
die('{"status" : "0"}');//SUCCESS
}
die('{"status":"-1"}');//FAILURE

?>
