<?php

require_once('../../../../config.php');
require_once($CFG->dirroot . '/mod/assign/locallib.php');
require_once($CFG->libdir . '/gradelib.php');

$inputJSON = file_get_contents('php://input');  // Get input from the client
$params = json_decode($inputJSON, TRUE);

$id = $_REQUEST["id"];

$auth = $params['customfeedback_token'];
if($auth != get_config('assignfeedback_customfeedback', 'secret')){
	die('{"status" : "Bad Auth"}');
}

$markerid = intval($params['markerid']);
$userid = intval($params['userid']);
$newgrade = floatval($params['grade']);
$status = intval($params['status']);
$time=floatval($params['time']);
$memory=floatval($params['memory']);
echo $memory;
//$oj_testcases = $params['oj_testcases'];
//$oj_feedback = $params['oj_feedback'];


/*list ($course, $cm) = get_course_and_cm_from_cmid($id, 'assign');


$context = context_module::instance($cm->id);
$assign = new assign($context, $cm, $course);

$plugin = $assign->get_feedback_plugin_by_type("customfeedback");
if(!$plugin->is_enabled()){
	die('{"status" : "Assignment does not use customfeedback"}');
}
*/
die('{"status" : "0"}');
