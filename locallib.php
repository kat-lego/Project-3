<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//


// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

define('ASSIGNFEEDBACK_CUSTOMFEEDBACK_TESTCASE_FILEAREA', 'competition_testcases');

define('ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_PENDING', 0);
define('ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_JUDGING', 1);
define('ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_COMPILEERROR', 2);
define('ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_PRESENTATIONERROR', 3);
define('ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_ACCEPTED', 4);
define('ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_MIXED', 5);
define('ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_INCORRECT', 6);
define('ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_MARKERERROR', 7);
define('ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_TIMELIMIT', 8);
define('ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_ABORTED', 9);
define('ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_TIMEOUT', 10);
define('ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_MEMORYLIMIT', 11);
define('ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_RUNTIMEERROR', 12);
define('ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_FILEREMOVED', 13);


define('FASTEST_MODE', "Fastest Mode");
define('OPTIMODE', 'OptiMode');
define("CLASSIC_MODE", "Classic Mode");
define("TOURNAMENT_MODE", "Tournament Mode");
define("AI_MODE", "AI Mode");
//Respond Codes


require_once('HtmlElement.php');


class assign_feedback_customfeedback extends assign_feedback_plugin {

    /**
    * Gets the name of pluin
    * @return string with name of the plugin
    */
    public function get_name() {
        return get_string('pluginname', 'assignfeedback_customfeedback');
    }

    /**
    * Gets the list of modes supported by the plugin
    * @return Array of strings with names of the modes
    */
    public function get_modes(){
        return array("Fastest Mode", "OptiMode" , "Classic Mode", "Tournament Mode", "AI Mode");
    }

    /**
    * Gets the list of languages supported by the plugin
    * @return array of strings with the names of the languages
    */
    public function get_languages(){
        return  array('Java', 'Python', 'C++','PythonZip');
    }

    public function get_language_code($lang){
        $arrayName = array('Java' => 2, 'Python' => 4, 'C++' => 12, 'PythonZip' => 16);
        return $arrayName[$lang];
    }

    /**
    * 
    * @return array or string representing the ordering options for the leaderboards
    */
    public function get_order_options(){
        return array('Ascending' ,'Descending');
    }

    /**
    * Get the code for the mode, identified by its position on the get_language array 
    * @return integer for the code
    */
    public function get_mode_code($mode){
        return array_search($mode, $this->get_modes());
    }

    /**
    * Get a list of rerun options 
    * @return array of integers for rerun options.
    */
    public function get_rerun_options(){
        return array(1,2,4,8,16);
    }


    /**
    *
    * Gets a list of integers. Each integer is the number of questions an assignment might have
    * The integers range from 1 to the maxquestions configured in the settings of the plugin
    * @return array of integers from 1 to maxquestions
    */
    public function get_question_numbers(){
        $n = get_config('assignfeedback_customfeedback','maxquestions');
        $arr = array();
        for($i=1;$i<=$n;$i++){
            array_push($arr, $i);
        }
        return $arr;
    }

    /**
    * Gets list of integers. Each integer is the time limit a question might have
    *
    * The integers are in seconds
    * @return array of integers
    */
    function get_time_limits(){
        $limits=array(1,3,5,10,20,60,120);
        return $limits;
    }


    function get_memory_limits(){
        return  array(1,2,4,16,32,64,512,1024);
    }
    
    private function get_testcase_filearea($question_number){
        return ASSIGNFEEDBACK_CUSTOMFEEDBACK_TESTCASE_FILEAREA.$question_number;
    }

    private function get_callback_url($assign_id,$question_number){
        global $CFG;
        $cmid = $this->assignment->get_context()->instanceid;
        $url = $CFG->wwwroot . "/mod/assign/feedback/customfeedback/update_record.php?assign_id=$assign_id&question_id=$question_number&cmid=$cmid";
        return $url;
    }


    public function get_settings(MoodleQuickForm $mform) {
        
        //Tittle: Competitive Assignment Form
        $htmlstring = '<h2 id = "assignfeedback_customfeedback_tittle">'.get_string('pluginname','assignfeedback_customfeedback').' Form</h2> <hr>';
        $mform->addElement('html', $htmlstring);

        //choose assignment type
        $modes = $this->get_modes();
        $default_mode = array_search($this->get_config('mode'), $modes);
        $mform->addElement('select', 'assignfeedback_customfeedback_mode', get_string('assign_mode', 'assignfeedback_customfeedback'),$modes, null);
        $mform->addHelpButton('assignfeedback_customfeedback_mode','assign_mode','assignfeedback_customfeedback');
        $mform->setDefault('assignfeedback_customfeedback_mode', $default_mode);


        // Orderings.
        $Options = $this->get_order_options();
        $default_option = array_search($this->get_config('ordering'), $Options);
        $mform->addElement('select', 'assignfeedback_customfeedback_order', get_string('ordering', 'assignfeedback_customfeedback'),$Options, null);
        $mform->addHelpButton('assignfeedback_customfeedback_order','ordering','assignfeedback_customfeedback');
        $mform->setDefault('assignfeedback_customfeedback_order', $default_option);
        $mform->hideIf('assignfeedback_customfeedback_order', 'assignfeedback_customfeedback_mode', 'neq', array_search(OPTIMODE, $modes) ); 

        //number of reruns
        $rerunOptions = $this->get_rerun_options();
        $default_reruns = array_search($this->get_config('reruns'), $rerunOptions);
        $mform->addElement('select', 'assignfeedback_customfeedback_rerun', get_string('reruns', 'assignfeedback_customfeedback'), $rerunOptions, null);
        $mform->addHelpButton('assignfeedback_customfeedback_rerun', 'reruns', 'assignfeedback_customfeedback');
        $mform->setDefault('assignfeedback_customfeedback_rerun', $default_reruns);
        $mform->hideIf('assignfeedback_customfeedback_rerun', 'assignfeedback_customfeedback_mode', 'eq', array_search(TOURNAMENT_MODE, $modes));
        $mform->hideIf('assignfeedback_customfeedback_rerun', 'assignfeedback_customfeedback_mode', 'eq', array_search(CLASSIC_MODE, $modes));
        $mform->hideIf('assignfeedback_customfeedback_rerun', 'assignfeedback_customfeedback_mode', 'eq', array_search(AI_MODE, $modes));

        //default score
        $mform->addElement('text', 'assignfeedback_customfeedback_default_score', get_string('default_score', 'assignfeedback_customfeedback'), 'Numeric');
        $mform->addRule('assignfeedback_customfeedback_default_score', 'Numeric', 'numeric', null, 'client');
        if($this->get_config("default_score"))$mform->setDefault('assignfeedback_customfeedback_default_score', $this->get_config("default_score"));
        $mform->addHelpButton('assignfeedback_customfeedback_default_score','default_score','assignfeedback_customfeedback');
        $mform->hideIf('assignfeedback_customfeedback_default_score', 'assignfeedback_customfeedback_mode', 'eq', array_search(TOURNAMENT_MODE, $modes));
        $mform->hideIf('assignfeedback_customfeedback_default_score', 'assignfeedback_customfeedback_mode', 'eq', array_search(CLASSIC_MODE, $modes));
        $mform->hideIf('assignfeedback_customfeedback_default_score', 'assignfeedback_customfeedback_mode', 'eq', array_search(AI_MODE, $modes));


        //Unit
        // die(var_dump());
        $default_unit = "units";
        if($this->get_config('scoreunits') !== "units" && $this->get_config('scoreunits')!=0){
            $default_unit = $this->get_config('scoreunits');
        }
        $mform->addElement('text', 'assignfeedback_customfeedback_scoreunits', get_string('scoreunits', 'assignfeedback_customfeedback'), $rerunOptions, null);
        $mform->addHelpButton('assignfeedback_customfeedback_scoreunits', 'scoreunits', 'assignfeedback_customfeedback');
        $mform->setDefault('assignfeedback_customfeedback_scoreunits', $default_unit);
        $mform->hideIf('assignfeedback_customfeedback_scoreunits', 'assignfeedback_customfeedback_mode', 'neq', array_search(OPTIMODE, $modes) ); //only appear if Fastest Mode is selected- modes.indexof(FastestMode

        //choose language
        $languages = $this->get_languages();
        $default_lang = array_search($this->get_config('language'), $languages);
        $mform->addElement('select', 'assignfeedback_customfeedback_language', get_string('language', 'assignfeedback_customfeedback'), $languages, null);
        $mform->addHelpButton('assignfeedback_customfeedback_language', 'language', 'assignfeedback_customfeedback');
        $mform->setDefault('assignfeedback_customfeedback_language', $default_lang);

        //Number of questions
        $numQValues = $this->get_question_numbers();
        $default_numQValue = array_search($this->get_config('numQ'), $numQValues);
        $mform->addElement('select', 'assignfeedback_customfeedback_numQ', get_string('numQuestions', 'assignfeedback_customfeedback'), $numQValues, null);
        $mform->addHelpButton('assignfeedback_customfeedback_numQ', 'numQuestions', 'assignfeedback_customfeedback');
        $mform->setDefault('assignfeedback_customfeedback_numQ', $default_numQValue);

        //Question Sub-form
        $n = get_config('assignfeedback_customfeedback','maxquestions');
        for($i=0;$i<$n;$i++){
            $this->addQuestion($i,$mform);
        }

        $this->disable_form($mform,'assignfeedback_customfeedback_enabled','notchecked');
        $this->disable_form($mform,'assignfeedback_witsoj_enabled','checked');
    }


    public function addQuestion($i,MoodleQuickForm $mform){
        //Question numbering
        $id = $i+1;
        $Qtittle = array( );
        $mform->addGroup($Qtittle,  'assignfeedback_customfeedback_questionID'.$i, get_string('question','assignfeedback_customfeedback').' '.$id, array(' '), false);

        //Time limit for question i
        $time_limits = $this->get_time_limits();
        $default_time_limit = array_search($this->get_config('timelimit'.$i), $time_limits);
        $mform->addElement('select', 'assignfeedback_customfeedback_timelimitQ'.$i, get_string('timelimit', 'assignfeedback_customfeedback'), $time_limits, null);
        $mform->addHelpButton('assignfeedback_customfeedback_timelimitQ'.$i, 'timelimit', 'assignfeedback_customfeedback');
        $mform->setDefault('assignfeedback_customfeedback_timelimitQ'.$i, $default_time_limit);

        //memory limit for question i
        $memory_limits = $this->get_memory_limits();
        $default_memory_limit = array_search($this->get_config('memorylimit'.$i), $memory_limits);
        $mform->addElement('select', 'assignfeedback_customfeedback_memorylimitQ'.$i, get_string('memorylimit', 'assignfeedback_customfeedback'), $memory_limits, null);
        $mform->addHelpButton('assignfeedback_customfeedback_memorylimitQ'.$i, 'memorylimit', 'assignfeedback_customfeedback');
        $mform->setDefault('assignfeedback_customfeedback_memorylimitQ'.$i, $default_memory_limit);

        //file manager for question i
        $max_bytes = get_config('assignfeedback_customfeedback', 'maxbytes');
        $mform->addElement('filemanager', 'assignfeedback_customfeedback_testcasesQ'.$i, get_string('test_cases', 
                'assignfeedback_customfeedback'), null,
                array('subdirs' => 0, 'maxbytes' => $max_bytes, 'areamaxbytes' => $max_bytes*10000, 'maxfiles' => 50,
                    'accepted_types' => array('.*'), 'return_types'=> FILE_INTERNAL | FILE_EXTERNAL));
        $mform->addHelpButton('assignfeedback_customfeedback_testcasesQ'.$i, 'test_cases', 'assignfeedback_customfeedback');

        $draftitemid = file_get_submitted_draft_itemid('assignfeedback_customfeedback_testcasesQ'.$i);
        file_prepare_draft_area($draftitemid, $this->assignment->get_context()->id, 'assignfeedeback_customfeedback', 'attachment', null, array('subdirs' => 0, 'maxbytes' => $max_bytes, 'maxfiles' => 1));

        //hiding question elements
        $arr = array();
        for($x =0;$x<$i;$x++){
            array_push($arr,$x);
        }
        
        $mform->hideIf('assignfeedback_customfeedback_questionID'.$i, 'assignfeedback_customfeedback_numQ', 'in',$arr);
        $mform->hideIf('assignfeedback_customfeedback_timelimitQ'.$i, 'assignfeedback_customfeedback_numQ', 'in',$arr);
        $mform->hideIf('assignfeedback_customfeedback_memorylimitQ'.$i, 'assignfeedback_customfeedback_numQ', 'in',$arr);
        $mform->hideIf('assignfeedback_customfeedback_testcasesQ'.$i, 'assignfeedback_customfeedback_numQ', 'in',$arr);
            
        return true;
    }


    function disable_form(MoodleQuickForm $mform, $dependent,$condition){
        $mform->disabledIf('assignfeedback_customfeedback_mode', $dependent,$condition );
        $mform->disabledIf('assignfeedback_customfeedback_rerun', $dependent, $condition);
        $mform->disabledIf('assignfeedback_customfeedback_language', $dependent, $condition);
        $mform->disabledIf('assignfeedback_customfeedback_numQ', $dependent, $condition);
        $mform->disabledIf('assignfeedback_customfeedback_order', $dependent, $condition);
        $mform->disabledIf('assignfeedback_customfeedback_scoreunits', $dependent, $condition);
        $mform->disabledIf('assignfeedback_customfeedback_default_score', $dependent, $condition);

        $n = get_config('assignfeedback_customfeedback','maxquestions');
        for($i=0;$i<$n;$i++){
            $mform->disabledIf('assignfeedback_customfeedback_timelimitQ'.$i, $dependent, $condition);
            $mform->disabledIf('assignfeedback_customfeedback_memorylimitQ'.$i, $dependent, $condition);
            $mform->disabledIf('assignfeedback_customfeedback_testcasesQ'.$i, $dependent, $condition);
        }
    }
 
    public function save_settings(stdClass $data) {
        global $DB;

        if($data->assignfeedback_witsoj_enabled){
            return false;
        }
        
        $assignData = array();
        $assignData['id'] = $this->assignment->get_instance()->id;
        $assignData['mode'] = $this->get_modes()[$data->assignfeedback_customfeedback_mode];
        $assignData['language'] = $this->get_languages()[$data->assignfeedback_customfeedback_language];
        $assignData['number_of_questions'] = $this->get_question_numbers()[$data->assignfeedback_customfeedback_numQ];

        $order = intval($data->assignfeedback_customfeedback_order);
        
        if($order){
            $assignData['ordering'] = ($order)?$order:0;
        }else{
            $assignData['ordering'] = 0;
        }
        //setting the default score
        $default_score = floatval($data->assignfeedback_customfeedback_default_score);
        if($default_score){
            $assignData['default_score'] = $default_score;
        }else{
             $assignData['default_score'] = ($order == 0)?100000:0;
        }

        $this->set_config('mode', $assignData['mode']);
        $this->set_config('language', $assignData['language']);
        $this->set_config('numQ', $assignData['number_of_questions']);
        $this->set_config('reruns', $this->get_rerun_options()[$data->assignfeedback_customfeedback_rerun]);
        $this->set_config('ordering', $this->get_order_options()[$data->assignfeedback_customfeedback_order]);
        $this->set_config('default_score',floatval($data->assignfeedback_customfeedback_default_score));
        $this->set_config('scoreunits', $data->assignfeedback_customfeedback_scoreunits);

        $isupdate = $DB->record_exists('customfeedback_assignment', ['id'=>$assignData['id']]);
        if($isupdate){
            $sql = "UPDATE {customfeedback_assignment} 
                    SET mode = :mode,
                        language = :language,
                        number_of_questions = :number_of_questions,
                        default_score = :default_score,
                        ordering = :ordering

                    WHERE id = :id
                    ";

            // die(var_dump($assignData));
            $DB->execute($sql, $assignData);
        }else{
            $sql = "INSERT INTO {customfeedback_assignment} VALUES(:id, :mode,:language ,:number_of_questions, :ordering, :default_score)";
            $DB->execute($sql, $assignData);
        }

        $n = $assignData['number_of_questions'];
        for($i=0;$i<$n;$i++){
            $s1 = '$data->assignfeedback_customfeedback_timelimitQ'.$i;
            $s2 = '$data->assignfeedback_customfeedback_memorylimitQ'.$i;
            $s3 = '$data->assignfeedback_customfeedback_testcasesQ'.$i;
            eval("\$v1=\"$s1\";");
            eval("\$v2=\"$s2\";");
            eval("\$v3=\"$s3\";");

            $questionData = array();
            $questionData['assign_id'] = $assignData['id'];
            $questionData['question_number'] = $i;
            $questionData['time_limit'] = $this->get_time_limits()[$v1];
            $questionData['memory_limit'] = $this->get_memory_limits()[$v2];
            

            $this->set_config('timelimit'.$i, $questionData['time_limit']);
            $this->set_config('memorylimit'.$i, $questionData['memory_limit']);


            if($isupdate){
                $sql = "UPDATE {customfeedback_question} 
                        SET memory_limit = :memory_limit,
                            time_limit = :time_limit
                        WHERE assign_id = :assign_id AND question_number = :question_number
                        ";
                $DB->execute($sql, $questionData);

            }else{
                $sql = "INSERT INTO {customfeedback_question} VALUES(:assign_id, :question_number,:memory_limit ,:time_limit)";
                $DB->execute($sql, $questionData);
            }

            //saving test case files for question i
            if (isset($v3)) {
            file_save_draft_area_files($v3, $this->assignment->get_context()->id,
                                       'assignfeedback_customfeedback',$this->get_testcase_filearea($i) , 0);
            }

        }
        $this->rejudge_submissions();
        return true;
    }


    public function data_preprocessing(&$defaultvalues) {
        
        $n = get_config('assignfeedback_customfeedback','maxquestions');
        for($i=0;$i<$n;++$i){
            $draftitemid = file_get_submitted_draft_itemid('assignfeedback_customfeedback_testcasesQ'.$i);
            file_prepare_draft_area($draftitemid, $this->assignment->get_context()->id, 'assignfeedback_customfeedback', $this->get_testcase_filearea($i),
                                    0, array('subdirs' => 0));
            $defaultvalues['assignfeedback_customfeedback_testcasesQ'.$i] = $draftitemid;
        }
        return;
    }

    /**
    * 
    */
    public function get_form_elements_for_user($grade, MoodleQuickForm $mform, stdClass $data, $userid) {
        //$fileoptions = $this->get_file_options();
        $gradeid = $grade ? $grade->id : 0;
        $mform->addElement('editor', 'assignfeedbackcomments_editor', $this->get_name(), null, null);
        $mform->addElement('static', 'assignfeedbackwitsoj_rejudge', $this->get_name(), '#', null);;
        return true;
    }

    /**
     *@codeCoverageIgnore
     */
    public function is_feedback_modified(stdClass $grade, stdClass $data) {
        return false;
        $commenttext = '';
        if ($grade) {
            $feedbackcomments = $this->get_feedback_comments($grade->id);
            if ($feedbackcomments) {
                $commenttext = $feedbackcomments->commenttext;
            }
        }

        if ($commenttext == $data->assignfeedbackcomments_editor['text']) {
            return false;
        } else {
            return true;
        }
    }

    /**
    *@codeCoverageIgnore
    */
    public function save(stdClass $grade, stdClass $data) {
        global $DB;

        $fileoptions = $this->get_file_options();

        $userid = $grade->userid;
        $elementname = 'files_' . $userid;

        $data = file_postupdate_standard_filemanager($data,
            $elementname,
            $fileoptions,
            $this->assignment->get_context(),
            'assignfeedback_file',
            ASSIGNFEEDBACK_COMPETITIVE_TESTCASES_FILEAREA,
            $grade->id);

        $filefeedback = $this->get_file_feedback($grade->id);
        if ($filefeedback) {
            // $filefeedback->numfiles = $this->count_files($grade->id, ASSIGNFEEDBACK_FILE_FILEAREA);
            return $DB->update_record('assignfeedback_file', $filefeedback);
        } else {
            $filefeedback = new stdClass();
            $filefeedback->numfiles = $this->count_files($grade->id, ASSIGNFEEDBACK_FILE_FILEAREA);
            $filefeedback->grade = $grade->id;
            $filefeedback->assignment = $this->assignment->get_instance()->id;
            return $DB->insert_record('assignfeedback_file', $filefeedback) > 0;
        }
    }


    public function set_initial_grade($userid){
        $grade = $this->assignment->get_user_grade($userid, true);
        $grade->grade = 0;
        $this->assignment->update_grade($grade, false);
    }


   /**
    *@codeCoverageIgnore
    */
    public function view_summary(stdClass $grade, & $showviewlink) {
         
        $n = $this->get_config('numQ');

        //TODO: add these stuff the language strings
        $h = ['Question', 'Verdict'];
        $ta = ['width'=>'100%', 'margin-bottom'=>'10%'];
        $ha = [];
        $table = HtmlElement::create_html_table($h,$ta,$ha);

        $qualifies = False;
        for($i = 0;$i<$n;$i++){
            $qn = $i+1;
            $verdict = $this->get_question_verdict($grade,$i);
            $rd = ["Question $qn",$verdict];
            HtmlElement::add_tabledata($table,$rd,[]);

        }


        $leaderboard = null;

        $leaderboard = $this->getLeaderBoardSnippet($grade->userid);

        $lbTittle = new HtmlElement('h1',null,True);
        $lbTittle->add_subelement(new HtmlElement(TEXT,'Leaderboard Snippet',false));

        $string = "";
        if($table!==null && $lbTittle!==null && $leaderboard!==null){
            $string = $table->str().$lbTittle->str().$leaderboard->str();
        }
        $courseid = $this->assignment->get_instance()->course;
        $assignid = $this->assignment->get_instance()->id;
        //TODO: update this link
        $site = get_config('assignfeedback_customfeedback','leaderboardsite');
        $link = '<a href="'.$site.'?assignid='.$assignid.'&courseid='.$courseid.'">View The Full Leaderboard Here</a>';
        

        return $string.$link;
    }

    public function getLeaderBoardSnippet($userid){
        global $DB;
        $lbheader = ['POS', 'USERNAME', 'TOTAL SCORE'];
        $lbattributes = ['width' => '100%', 'margin-bottom'=>'10%'];
        $leaderboard = HtmlElement::create_html_table($lbheader,$lbattributes,[]);

        $userdata = $this->getLeaderBoardData();
     
        $mode = $this->get_config('mode');

        $n = $this->get_config('numQ');
        foreach ($userdata as $uid => $user) {
            $total_score = 0;
            for($i=0;$i<$n;$i++){
                if($user->question_list[$i]->status == ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_ACCEPTED || $user->question_list[$i]->status == ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_PRESENTATIONERROR ){
                    $total_score+= $user->question_list[$i]->score;
                }else{
                    $total_score+= intval($this->get_config("default_score"));
                }
            }
             
            $user->total_score = $total_score;
        }

        if($order == 0){
            usort($userdata, function($a, $b) { return $a->total_score - $b->total_score; });
        }else{
            usort($userdata, function($a, $b) { return $b->total_score - $a->total_score; });
        }

        $unit = "points";
        if($mode == FASTEST_MODE){
            $unit = "ms";
        }elseif($mode == OPTIMODE){
            $unit = $this->get_config('scoreunits');
        }
        $unit=" ".$unit;

        $playerpos = 0;
        for($i=0; $i< count($userdata) ;$i++) {
            if($userid==$userdata[$i]->id){
                $playerpos = $i;
                break; 
            }
        }

        $start = $playerpos-2;
        $end = $playerpos+2;
        $len = count($userdata);
        if($playerpos<2){
            $start = 0;
        }

        if($len - $playerpos <= 2){
            $end = $len-1;
        }

        $pos = 0;
        $prev = $userdata[0]->total_score;
        for($i=0; $i< $len ;$i++) {
            $curr = $userdata[$i]->total_score;
            if($prev!=$curr){
                $pos++;
                $prev = $curr;
            }

            if($start<=$i and $i<=$end){
                $data = [$pos,$userdata[$i]->username, $userdata[$i]->total_score.$unit];
                $attributes = [];

                if($userdata[$i]->id == $userid){
                    $attributes['background-color'] = 'orange';
                }
                
                HtmlElement::add_tabledata($leaderboard,$data,$attributes);
            }

        }

        return $leaderboard;
    }

    public function getLeaderBoardData(){
        global $DB;

        $players=$this->get_participants();

        $userdata = array();
        foreach ($players as $key => $value) {
            
            $userObj = $DB->get_record("user", array("id" => $key));
            $user = new stdClass();
            $user->id = $key;
            $user->username = $userObj->username;
            $user->question_list = $this->get_all_submissions($key);
            $userdata[$key] = $user;
        
        }

       return $userdata;
    }

    public function get_participants(){
        global $DB;
        $sql = "SELECT user_id FROM {customfeedback_submission} 
                WHERE 
                assign_id = :assign_id
                ";

        $params = array();
        $params['assign_id'] = $this->assignment->get_instance()->id;
        $records = $DB->get_records_sql($sql,$params, $sort='', $fields='*', $limitfrom=0, $limitnum=0);

        return $records;
    }

    public function get_all_submissions($userid){
        global $DB;
        $sql = "SELECT question_number, memory, runtime, no_of_submittions, status, score FROM {customfeedback_submission} 
                WHERE 
                assign_id = :assign_id AND
                user_id = :user_id
                ";

        $params = array();
        $params['assign_id'] = $this->assignment->get_instance()->id;
        $params['user_id'] = $userid;
        $records = $DB->get_records_sql($sql,$params, $sort='', $fields='*', $limitfrom=0, $limitnum=0);
        return $records;
    }

    function minimum_requirements(stdClass $grade){
        global $DB;
        $sql = "SELECT * FROM {customfeedback_submission} 
                WHERE 
                assign_id = :assign_id AND
                user_id = :user_id
                ";

        $params = array();
        $params['assign_id'] = $this->assignment->get_instance()->id;
        $params['user_id'] = $grade->userid;

        $records = $DB->get_records_sql($sql,$params, $sort='', $fields='*', $limitfrom=0, $limitnum=0);

        foreach ($records as $r) {
            if($r->status == ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_ACCEPTED){
                return True;
            }
        }

        return False;

    }

    /**
    * 
    */
    function get_question_verdict(stdClass $grade,$question){
        global $DB;

        $sql = "SELECT * FROM {customfeedback_submission} 
                WHERE 
                question_number=:question_number AND
                assign_id = :assign_id AND
                user_id = :user_id
                ";
        $params = array();
        $params['question_number'] = $question;
        $params['assign_id'] = $this->assignment->get_instance()->id;
        $params['user_id'] = $grade->userid;

        $records = $DB->get_records_sql($sql,$params, $sort='', $fields='*', $limitfrom=0, $limitnum=0);

        if(empty($records)){
            //add to language strings
            return "No Submission Made";
        }else{
            $record = $records[$question];
            switch ($record->status) {
                case 0:
                    return'<span style="color: orange;font-weight:bold">Judge request pending</span>';
                    break;
                case 1:
                    return '<span style="color: orange;font-weight:bold">Judge in progress</span>';
                    break;
                case 2:
                    return '<span style="color: red;font-weight:bold">Compilation Error</span>';
                    break;
                case 3:
                    return '<span style="color: orange;font-weight:bold">Presentation Error</span>';
                    break;
                case 4:
                    return '<span style="color: green;font-weight:bold">Accepted</span>';
                    break;
                case 5:
                    return '<span style="color: green;font-weight:bold">Partially Accepted</span>';
                    break;
                case 6:
                    return '<span style="color: red;font-weight:bold">Incorrect</span>';
                    break;
                case 7:
                    return '<span style="color: red;font-weight:bold">Marker Error</span>';
                    break;
                case 8:
                    return  '<span style="color: red;font-weight:bold">Time Limit Exceeded</span>';
                    break;
                case 9:
                    return '<span style="color: red;font-weight:bold">Aborted</span>';
                    break; 
                case 10:
                    return '<span style="color: red;font-weight:bold">Timeout</span>';
                    break;
                case 11:
                    return '<span style="color: red;font-weight:bold">Memory Limit Exceeded</span>';
                case 12:
                    return  '<span style="color: red;font-weight:bold">Run-time Error</span>';
                    break;
                case 13:
                    return '<span style="color: red;font-weight:bold">Submission File has been Removed</span>';
                default:
                    return "Ops A bug must have crawled through the cracks";
                    
            } 
        }

    }

   /**
    *@codeCoverageIgnore
    */
    public function view(stdClass $grade) {
        return $this->assignment->render_area_files('assignfeedback_file', ASSIGNFEEDBACK_FILE_FILEAREA, $grade->id);
    }

    //called once marker finishes marking
    public function update_record($question_number,$assign_id,$user_id,$memory,$runtime,$status,$grade,$score,$inputJson){
        global $DB;
        $params = array();
        $params['question_number'] = $question_number;
        $params['assign_id'] = $assign_id;
        $params['user_id'] = $user_id;
        $params['memory']=$memory;
        $params['status']=$status;
        $params['runtime']= $runtime;
        $params['grade']=$grade;
        $params['score']=$score;
        
        if($this->SubmissionExists($question_number,$assign_id,$user_id)){//submission update

            $sql = "SELECT * FROM {customfeedback_submission} 
                    WHERE 
                    question_number=:question_number AND
                    assign_id = :assign_id AND
                    user_id = :user_id
                    ";

            if($records = $DB->get_records_sql($sql,$params)){
                    $attempts=$records[0]->no_of_submittions+1;
                    $params['attempts']=$attempts;
                    $sql="UPDATE {customfeedback_submission} 
                        SET no_of_submittions=:attempts,memory=:memory,status=:status,runtime=:runtime,score=:score
                        WHERE 
                        question_number=:question_number AND
                        assign_id =:assign_id AND
                        user_id = :user_id
                        ";   
                       $DB->execute($sql, $params);  
                    return true;//success     
            }

        }
        else{//submission record doest exist
    
        }

    }

    public function SubmissionExists($question_number,$assign_id,$user_id){
        global $DB;
        $param= array('assign_id' =>intval($assign_id),'question_number'=>intval($question_number),'user_id'=>intval($user_id));
        return $DB->record_exists('customfeedback_submission', $param);
    }


    /**
    *@codeCoverageIgnore
    */  
    public function can_upgrade($type, $version) {

        if (($type == 'upload' || $type == 'uploadsingle') && $version >= 2011112900) {
            return true;
        }
        return false;
    }

    /**
    *@codeCoverageIgnore
    */
    public function upgrade_settings(context $oldcontext, stdClass $oldassignment, & $log) {
        // first upgrade settings (nothing to do)
        return true;
    }

    /**
    *@codeCoverageIgnore
    */  
    public function upgrade(context $oldcontext, stdClass $oldassignment, stdClass $oldsubmission, stdClass $grade, & $log) {
        global $DB;

        // now copy the area files
        $this->assignment->copy_area_files_for_upgrade($oldcontext->id,
            'mod_assignment',
            'response',
            $oldsubmission->id,
            // New file area
            $this->assignment->get_context()->id,
            'assignfeedback_file',
            ASSIGNFEEDBACK_FILE_FILEAREA,
            $grade->id);

        // now count them!
        $filefeedback = new stdClass();
        $filefeedback->numfiles = $this->count_files($grade->id, ASSIGNFEEDBACK_FILE_FILEAREA);
        $filefeedback->grade = $grade->id;
        $filefeedback->assignment = $this->assignment->get_instance()->id;
        if (!$DB->insert_record('assignfeedback_file', $filefeedback) > 0) {
            $log .= get_string('couldnotconvertgrade', 'mod_assign', $grade->userid);
            return false;
        }
        return true;
    }

    /**
    * Get source file for the submission for a question for a particular user
    * @param $userid - the id of the user who's submission we are trying to get
    * @param $question_number - the question number of the question relating to the submission file
    * @return array - indexed with 'content' and 'ext'. if there is no new submission, return false
    */
    private function new_question_submission($userid,$question_number){
        global $DB;
        $pathnamehash = null;
        $hashes = $this->get_submission_hashes($userid,$question_number);
        $pathnamehash = $hashes->pathnamehash;
        $contenthash = $hashes->contenthash;
        $submission = $this->get_submission_record($userid,$question_number);

        if($pathnamehash == null){
            
            if(isset($submission->contenthash)){
                //update the file removal
                $sql = "UPDATE {customfeedback_submission} 
                        SET status = :status,
                            contenthash = NULL
                        WHERE question_number = :question_number AND
                              assign_id = :assign_id AND
                              user_id = :user_id
                        ";
                $params = array();
                $params['status'] = ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_FILEREMOVED;
                $params['question_number'] = intval($question_number);
                $params['assign_id'] = intval($this->assignment->get_instance()->id);
                $params['user_id'] = intval($userid);


                $DB->execute($sql,$params);
            }

            return false;
        }
        

        if($submission == null){
            $this->create_submission_record($userid,$question_number,$contenthash);
        }else{

            $lasthash = $submission->contenthash;
            if($lasthash == $contenthash){
                return false; // it is not a re submission
            }else{
                //update submission status and lastpathnamehash

                //NEEDS ATTENTION
                $sql = "UPDATE {customfeedback_submission} 
                        SET status = :status,
                            contenthash = :contenthash
                        WHERE question_number = :question_number AND
                              assign_id = :assign_id AND
                              user_id = :user_id
                    ";
                $params = array();
                $params['question_number'] = intval($question_number);
                $params['assign_id'] = intval($this->assignment->get_instance()->id);
                $params['user_id'] = intval($userid);
                $params['status'] = ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_PENDING;
                $params['contenthash'] = $contenthash;


                $DB->execute($sql,$params);
            }
        }
        $fs = get_file_storage();
        $file = $fs->get_file_by_hash($pathnamehash);
        if (!$file or $file->is_directory()) {
            print("E2");
            return false;
        }
        
        $source = array();
        $source["content"] = base64_encode($file->get_content());
        $source["ext"] = pathinfo($file->get_filename(), PATHINFO_EXTENSION);
        return $source;
        
    }
    /** 
    * [Doc needs revision]
    * Get pathnamehash for the submission for a question for a particular user
    * @param $userid - the id of the user who's submission we are trying to get
    * @param $question_number - the question number of the question relating to the submission file
    * @return string, null if there is no pathnamehash
    */
    private function get_submission_hashes($userid, $question_number){
       global $DB;
        $q = intval($question_number);

        $arr = array('contextid'=>$this->assignment->get_context()->id,
                        'component'=>'assignsubmission_file',
                        'filearea'=>ASSIGNSUBMISSION_FILE_FILEAREA,
                        'userid'=>$userid);
        
        $prefix = get_config('assignfeedback_customfeedback','prefix').$q;
        
        if(count($prefix)>3){
            die("Please make sure the prefix set is less than or equals 3");
        }


        $sql = "SELECT contenthash,pathnamehash FROM {assign_submission} s JOIN {files} f ON s.id = f.itemid
                WHERE f.contextid = :contextid AND
                      f.component = :component AND
                      f.filearea = :filearea AND
                      s.userid = :userid AND
                      f.filename LIKE '$prefix%'
                ";
       
        $rec = $DB->get_records_sql($sql,$arr);
        

        if(count($rec) == 1){
            $hashes = reset($rec);
            return $hashes;
        }else if(count($rec) == 0){
            //var_dump($rec);
            $hashes = null;
            return null;
        }else{
            die("Huge Error in get_submission_hashes");
            return null;
        }
    }


    private function get_submission_record($userid,$question_number){
        global $DB;

        if(!$this->SubmissionExists($question_number,$this->assignment->get_instance()->id,$userid)){
            return null;
        }

        $sql = "SELECT * FROM {customfeedback_submission}
                WHERE
                question_number=:question_number AND
                assign_id = :assign_id AND
                user_id = :user_id
                ";
        $params = array();
        $params['question_number'] =intval($question_number);
        $params['assign_id'] = intval($this->assignment->get_instance()->id);
        $params['user_id'] = intval($userid);

        $records = $DB->get_records_sql($sql,$params, $sort='', $fields='*', $limitfrom=0, $limitnum=0);
        $records = reset($records);
        
        return $records;

    }

    /**
    * 
    */
    private function create_submission_record($userid,$question_number,$contenthash){
        global $DB;

        $sql = "INSERT INTO {customfeedback_submission} (question_number,assign_id,user_id,status,contenthash) VALUES(:question_number, :assign_id,:user_id ,:status, :contenthash)";
        $params = array();
        $params['question_number'] = $question_number;
        $params['assign_id'] = $this->assignment->get_instance()->id;
        $params['user_id'] = $userid;
        $params['status'] = ASSIGNFEEDBACK_CUSTOMFEEDBACK_STATUS_PENDING;
        $params['contenthash'] = $contenthash;

        $DB->execute($sql,$params);
    }

    /*
    * Sends out a judgement request to the marker whenever a student adds a submission
    */
    public function judge($userid){
    global $DB;
        $n = $this->get_config('numQ');
        for($i=0;$i<$n;$i++){
            if($source = $this->new_question_submission($userid, $i)){
                $mode = $this->get_config("mode");
                if($mode == FASTEST_MODE){
                    $data = $this->FastestModeMarkingData($userid,$i);
                }else if($mode == OPTIMODE){
                    $data = $this->OptiModeMarkingData($userid,$i);
                    // die(var_dump($data));
                }else{
                    die("Error in Judge Function");
                }


                if(!$data){
                    //some error
                    die("Its in the judge function");
                }

                //basic marking data
                $data["userid"]    = $userid;
                $userObj = $DB->get_record("user", array("id" => $userid));
                $data["firstname"] = $userObj->firstname;
                $data["lastname"]  = $userObj->lastname;
                $data["language"]  = $this->get_language_code($this->get_config('language'));
                $data["mode"] = $this->get_mode_code($this->get_config('mode'));
                $data["cpu_limit"] = $this->get_config("timelimit".$i);
                $data["mem_limit"] = $this->get_config("memorylimit".$i);
                $data["callback"]  = $this->get_callback_url($this->assignment->get_instance()->id, $i);
                $data['source'] = $source;
                // die(var_dump($data));
                $this->post_to_handler($data);

            }
        }
    }


    public function OptiModeMarkingData($userid,$question_number){
        global $DB;
        $data = array();
        $data["n"] = $this->get_config('reruns');


        $fs = get_file_storage();
        $testcase_filearea = $this->get_testcase_filearea($question_number);
        $context = $this->assignment->get_context()->id;
        $files = $fs->get_area_files($context, 'assignfeedback_customfeedback',$testcase_filearea , '0','sortorder', false);
        if ($files) {
            if(count($files)>2){
                die("Error in OptiModeMarkingData, too many files were found");
            }else if(count($files)<2){
                die("Error in OptiModeMarkingData, missing files");
            }
            
            foreach ($files as $key => $file) {
                if(strpos($file->get_filename(), "evaluator") !== false){
                    $evaluator = array();
                    $evaluator["content"] = base64_encode($file->get_content());
                    $evaluator["ext"] = pathinfo($file->get_filename(), PATHINFO_EXTENSION);
                    $data['evaluator'] = $evaluator;
                }elseif (strpos($file->get_filename(), 'testcase') !== false) {
                    
                    $fileurl = \moodle_url::make_pluginfile_url(
                            $file->get_contextid(), 
                            $file->get_component(), 
                            $file->get_filearea(), 
                            $file->get_itemid(), 
                            $file->get_filepath(), 
                            $file->get_filename());
                    $download_url = $fileurl->get_port() ? 
                                        $fileurl->get_scheme() . '://' . $fileurl->get_host() . $fileurl->get_path() . ':' . $fileurl->get_port()
                                        : $fileurl->get_scheme() . '://' . $fileurl->get_host() . $fileurl->get_path();
                    $testcase = array();
                    $testcase["url"] = $download_url;
                    $testcase["contenthash"] = $file->get_contenthash();
                    $testcase["pathnamehash"] = $file->get_pathnamehash();
                    
                    $data["testcase"] = $testcase;
                }

            }

            // $file = reset($files);
            // $testcase["ext"] = pathinfo($file->get_filename(), PATHINFO_EXTENSION);
            // $data["testcase"] = $testcase;
            // die(var_dump($data));
            return $data;
        }else{
            die("No testcase uploaded for question $question_number");
            error_log("E1"); //TODO get rid of this
            return null;
        }
    }

    public function FastestModeMarkingData($userid,$question_number){
        global $DB;
        $data = array();
        $data["n"] = $this->get_config('reruns');

        $fs = get_file_storage();
        $testcase_filearea = $this->get_testcase_filearea($question_number);
        $context = $this->assignment->get_context()->id;
        $files = $fs->get_area_files($context, 'assignfeedback_customfeedback',$testcase_filearea , '0','sortorder', false);
        if ($files) {

            $file = reset($files);
            $testcase = array();
            $fileurl = \moodle_url::make_pluginfile_url(
                    $file->get_contextid(), 
                    $file->get_component(), 
                    $file->get_filearea(), 
                    $file->get_itemid(), 
                    $file->get_filepath(), 
                    $file->get_filename());
            $download_url = $fileurl->get_port() ? 
                                $fileurl->get_scheme() . '://' . $fileurl->get_host() . $fileurl->get_path() . ':' . $fileurl->get_port()
                                : $fileurl->get_scheme() . '://' . $fileurl->get_host() . $fileurl->get_path();
            $testcase["url"] = $download_url;
            $testcase["contenthash"] = $file->get_contenthash();
            $testcase["pathnamehash"] = $file->get_pathnamehash();
            $data["testcase"] = $testcase;

            return $data;
        }else{
            die("No testcase uploaded for question $question_number");
            error_log("E1"); //TODO get rid of this
            return null;
        }
    }

    public function post_to_handler($data){
        // Setup cURL
        // die(var_dump($data));
        error_log("Posting:" . $data["userid"]);
        $data['customfeedback_token'] = get_config('assignfeedback_customfeedback', 'secret');
        $data['markerid'] = 0;
        
        $handler_url =  get_config('assignfeedback_customfeedback','handler');

        $ch = curl_init($handler_url);
        curl_setopt_array($ch, array(
            CURLOPT_POST => count($data),
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
            //'Content-Type: application/json'
            'Content-Type: text/plain'
            ),
            CURLOPT_POSTFIELDS => json_encode($data)
        ));

       // die(var_dump($data));

        // Send the request
        $response = curl_exec($ch);
         //die(var_dump($response));
        
        //TODO: Handle Responses

        if($response === FALSE){
            error_log("Curl error");
            die("Curl Error: " . curl_error($ch));
        }

        // Decode the response
        $responseData = json_decode($response, TRUE);

        return $responseData;
    
    }

    public function rejudge_submissions(){
        global $DB;
        $sql="
            UPDATE {customfeedback_submission} 
            SET contenthash='cleared'
            WHERE 
            assign_id =:assign_id
            ";
        $params = array();
        $params['assign_id'] = $this->assignment->get_instance()->id;
        $DB->execute($sql, $params);

        $participants = $this->get_participants();
        foreach ($participants as $key => $value) {
            $this->judge($key);
        }
    }


    /**
    *@codeCoverageIgnore
    */
    public function is_empty(stdClass $submission) {
        return $this->view($submission) == '';
    }

    /**
    *@codeCoverageIgnore
    */
    public function get_file_areas() {
        return array(ASSIGNFEEDBACK_FILE_FILEAREA=>$this->get_name());
    }


    /**
    *@codeCoverageIgnore
    */
    public function delete_instance() {
        global $DB;
        // will throw exception on failure
        $DB->delete_records('assignfeedback_file', array('assignment'=>$this->assignment->get_instance()->id));

        return true;
    }


    /**
    *@codeCoverageIgnore
    */
    public function format_for_gradebook(stdClass $grade) {
        return FORMAT_MOODLE;
    }


    /**
    *@codeCoverageIgnore
    */
    public function text_for_gradebook(stdClass $grade) {
        return '';
    }
    /**
     * Override to indicate a plugin supports quickgrading
     *@codeCoverageIgnore
     * @return boolean - True if the plugin supports quickgrading
     */
    public function supports_quickgrading() {
        return false;
    }

    /**
     * Get quickgrading form elements as html
     *@codeCoverageIgnore
     * @param int $userid The user id in the table this quickgrading element relates to
     * @param mixed $grade grade or null - The grade data. May be null if there are no grades for this user (yet)
     * @return mixed - A html string containing the html form elements required for quickgrading or false to indicate this plugin does not support quickgrading
     */
    public function get_quickgrading_html($userid, $grade) {
        return false;
    }

    /**
     * Has the plugin quickgrading form element been modified in the current form submission?
     *@codeCoverageIgnore
     * @param int $userid The user id in the table this quickgrading element relates to
     * @param stdClass $grade The grade
     * @return boolean - true if the quickgrading form element has been modified
     */
    public function is_quickgrading_modified($userid, $grade) {
        return false;
    }

    /**
     * Save quickgrading changes
     *@codeCoverageIgnore
     * @param int $userid The user id in the table this quickgrading element relates to
     * @param stdClass $grade The grade
     * @return boolean - true if the grade changes were saved correctly
     */
    public function save_quickgrading_changes($userid, $grade) {
        return false;
    }


    /**
     * Run cron for this plugin
     *@codeCoverageIgnore
     */
    public static function cron() {

    }

    /**
     * Return a list of the grading actions supported by this plugin.
     *
     * A grading action is a page that is not specific to a user but to the whole assignment.
     * @return array - An array of action and description strings.
     *                 The action will be passed to grading_action.
     */
    public function get_grading_actions() {
        return array();
    }

    /**
     * Show a grading action form
     *@codeCoverageIgnore
     * @param string $gradingaction The action chosen from the grading actions menu
     * @return string The page containing the form
     */
    public function grading_action($gradingaction) {
        return '';
    }
    /**
     * Return a list of the batch grading operations supported by this plugin.
     *@codeCoverageIgnore
     * @return array - An array of action and description strings.
     *                 The action will be passed to grading_batch_operation.
     */
    public function get_grading_batch_operations() {
        return array();
    }

    /**
     * Show a batch operations form
     *@codeCoverageIgnore
     * @param string $action The action chosen from the batch operations menu
     * @param array $users The list of selected userids
     * @return string The page containing the form
     */
    public function grading_batch_operation($action, $users) {
        return '';
    }


}
