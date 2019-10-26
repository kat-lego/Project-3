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

	public $configs = array();
	public $assignment;

	function get_config($name){

		$this->configs['mode'] = 0 ;
		$this->configs['language'] = 0;
		$this->configs['numQ'] = 0;
		$this->configs['reruns'] = 1;
		$this->configs['scoreunits'] = "units";
		$this->configs['ordering'] = 0;
		$this->configs['default_score'] = 1000;
		$this->configs['autograding_option'] = 1;
		$this->configs['autograding_cron'] = 1;
		// $configs['']

		for($i=0;$i<10;$i++){
			$this->configs['timelimit'.$i] = 0;
			$this->configs['memorylimit'.$i] = 0;
		}



		return $this->configs[$name];	
	}

	function set_config($name, $value){

		$configs[$name] = $value;
	}

	function get_feedback_comments($id){
		$comment = new stdClass();
		$comment->commenttext = 'comment';
		return $comment;
	}

}



class MoodleQuickForm {

	public $elements = array();
	public $disabled = array();


	public function addElement($type, $name, $string=null,$values= null,$attributes=null){
		$this->elements[$name] = array('type'=>$type);
		
	}

	public function addHelpButton(){

	}

	public function setDefault(){

	}

	public function addGroup(){

	}

	public function hideIf(){

	}

	public function disabledIf($form, $dependent,$condition ){
		$this->disabled[$form] = true;
	}

	public function addRule(){

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
	$string['leaderboardsite'] = 'Leaderboard Site';
	$string['leaderboardsite_help'] = 'Url for the site where the full leaderboard is hosted';

	//form lang strings
	$string['assign_mode'] = 'Assignment Mode';
	$string['assign_mode_help'] = 'The competition mode recognised by the backend marker';

	$string['language'] = 'Programming Language';
	$string['language_help'] = 'Programming Language recognised by the marking backend.';

	$string['numQuestions'] = 'Number of Questions';
	$string['numQuestions_help'] = 'The number of questions this assignment has.';

	$string['ordering'] = "Order";
	$string['ordering_help'] = "In which order the leaderboard will be in?";

	$string['reruns'] = 'Number of Reruns';
	$string['reruns_help'] = 'The number of times code for time based assignments is going to be ran in determining the score.';

	$string['scoreunits'] = 'Score Units';
	$string['scoreunits_help'] = 'The assessment point system';

	$string['question'] = 'Question';

	$string['timelimit'] = 'Time Limit';
	$string['timelimit_help'] = 'Maximum time (in seconds) the student submissions are allowed to run.';

	$string['memorylimit'] = 'Memory Limit';
	$string['memorylimit_help'] = 'Maximum memory the student submissions are allowed to use.';

	$string['test_cases'] = 'Test Cases';
	$string['test_cases_help'] = 'Upload the test case zip file here.';

	$string['default_score'] = 'Default Score';
	$string['default_score_help'] = 'Default score for an unsubmitted or incorrect solution to a question.';

	$string['autograding_option'] = 'Autograding Option';
	$string['autograding_option_help'] = 'Toggle autograding on or off';

	$string['autograding_cron'] = 'Autograding Cron';
	$string['autograding_cron_help'] = 'Specify how often autograding occurs intervals';

	$string['autograde_script'] = 'Autograding Script';
	$string['autograde_script_help'] = 'Python script for autograding';

	$string['rejudge_option'] = 'Rejudge Option';
	$string['rejudge_option_help'] = 'Toggle Rejudge on saving settings on and off';

	$string['jugde_nochange'] = 'Judge Unchanged Submissions';
	$string['jugde_nochange_help'] = 'Choose whether to judge Unchanged submissions or not';




	return $string[$name];
}


function file_get_submitted_draft_itemid(){

}

function file_prepare_draft_area(){

}


?>
