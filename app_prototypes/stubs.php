<?php
/*
* This class tries to minimise moodle dependancy as much as possible
*/
define('FILE_INTERNAL', 0);
define('FILE_EXTERNAL', 0);

//moodle globals
$cfg =new stdClass();
$cfg->wwwroot = 'someWebserver';
$GLOBALS['CFG'] = $cfg;

//moodle classes
class assign_feedback_plugin{

	function get_config($name){
	
		$configs = array();
		$configs['mode'] = 0 ;
		$configs['language'] = 0;
		$configs['numQ'] = 0;
		$configs['reruns'] = 1;
		$configs['scoreunits'] = "units";
		// $configs['']

		for($i=0;$i<10;$i++){
			$configs['timelimit'.$i] = 0;
			$configs['memorylimit'.$i] = 0;
		}



		return $configs[$name];	
	}



}



class MoodleQuickForm {

	public $elements = array();


	public function addElement(){

	}

	public function addHelpButton(){

	}

	public function setDefault(){

	}

	public function addGroup(){

	}

	public function hideIf(){

	}

	public function disabledIf(){
		
	}


}


//test stub for get config

function get_config($name,$variable){
	
	$configs = array();
	$configs['assignfeedback_customfeedback'] = array();
	$configs['assignfeedback_customfeedback']['maxquestions'] = 10;
	$configs['assignfeedback_customfeedback']['maxbytes'] = 10;

	return $configs[$name][$variable];	
}


/**
* Stub for get string
*/
function get_string($name, $variable){
	$string = array();
	$string['pluginname'] = 'Competitive Assignment';
	$string['enabled'] = 'Competitive Assignment Judge';
	$string['enabled_help'] = 'If enabled, the teacher will be able to provide awesome feedback ';

	$string['default'] = 'Enabled by default';
	$string['default_help'] = 'If set, this feedback method will be enabled by default for all new assignments.';
	$string['basepath'] = 'Test Case Path';
	$string['basepath_help'] = 'Path to test case folder.';
	$string['handler'] = 'Competitive Assignment Handler url address';
	$string['handler_help'] = 'Url where the handler is located';
	$string['prefix'] = 'Submission File Prefix';
	$string['prefix_help'] = 'The prefix all submission files have to follow, limited to 3 characters';
	$string['secret'] = 'Secret';
	$string['secret_help'] = 'Secret authentication token that markers should use.';
	$string['languages'] = 'Available Languages';
	$string['languages_help'] = 'Comma separated list of supported languages';
	$string['maxquestions'] = 'Maximum number of Questions';
	$string['maxquestions_help'] = 'Input the maximum number of questions a competitve assignment is allowed to have';
	$string['maximumtestcasesize'] = 'Maximum test case size';
	$string['maximumtestcasesize_help'] = 'Files uploaded by teachers as test cases may be up to this size.';
	$string['configmaxbytes'] = 'Maximum file size';

	$string['assign_mode'] = 'Assignment Mode';
	$string['assign_mode_help'] = 'The competition mode recognised by the backend marker';
	$string['language'] = 'Programming Language';
	$string['language_help'] = 'Programming Language recognised by the marking backend.';
	$string['numQuestions'] = 'Number of Questions';
	$string['numQuestions_help'] = 'The number of questions this assignment has.';
	$string['scoreunits'] = 'Score Units';
	$string['scoreunits_help'] = 'The assessment point system';
	$string['ordering'] = "Order";
	$string['ordering_help'] = "In which order the leaderboard will be in?";
	$string['reruns'] = 'Number of Reruns';
	$string['reruns_help'] = 'The number of times code for time based assignments is going to be ran in determining the score.';
	$string['question'] = 'Question';
	$string['timelimit'] = 'Time Limit';
	$string['timelimit_help'] = 'Maximum time (in seconds) the student submissions are allowed to run.';
	$string['memorylimit'] = 'Memory Limit';
	$string['memorylimit_help'] = 'Maximum memory the student submissions are allowed to use.';
	$string['test_cases'] = 'Test Cases';
	$string['test_cases_help'] = 'Upload the test case zip file here.';


	return $string[$name];
}


?>
