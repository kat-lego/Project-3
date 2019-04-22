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

require_once('html_element.php');


class assign_feedback_customfeedback extends assign_feedback_plugin {

    /**
    * Gets the name of pluin
    * 
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
        return array("Classic Mode", "Fastest Mode", "Tournament Mode", "AI Mode");
    }

    /**
    * Gets the list of languages supported by the plugin
    * @codeCoverageIgnore
    * @return array of strings with the names of the languages
    */
    public function get_languages(){
        return explode(',', get_config('assignfeedback_customfeedback', 'languages'));
    }

    /**
    *@codeCoverageIgnore
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
    $limits=array(1,3,5,10,20,60);
        return $limits;
    }

    /**
    * Gets the list of memory limits a question might have
    * @return array of strings
    */
    function get_memory_limits(){
        return  array(1,2,4,16,32,64,512,1024);
    }
    
    private function get_testcase_filearea($question_number){
        return ASSIGNFEEDBACK_CUSTOMFEEDBACK_TESTCASE_FILEAREA.$question_number;
    }

    /**
    * 
    * Allows this plugin to add a list of settings to the form when creating an assignment.
    *@codeCoverageIgnore
    */
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

    /**
    * Adds the settings for a question to the form
    *@codeCoverageIgnore
    */
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
                    'accepted_types' => array('.zip'), 'return_types'=> FILE_INTERNAL | FILE_EXTERNAL));
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

    /**
    *
    *@codeCoverageIgnore
    */
    function disable_form(MoodleQuickForm $mform, $dependent,$condition){
        $mform->disabledIf('assignfeedback_customfeedback_mode', $dependent,$condition );
        $mform->disabledIf('assignfeedback_customfeedback_language', $dependent, $condition);
        $mform->disabledIf('assignfeedback_customfeedback_numQ', $dependent, $condition);

        $n = get_config('assignfeedback_customfeedback','maxquestions');
        for($i=0;$i<$n;$i++){
            $mform->disabledIf('assignfeedback_customfeedback_timelimitQ'.$i, $dependent, $condition);
            $mform->disabledIf('assignfeedback_customfeedback_memorylimitQ'.$i, $dependent, $condition);
            $mform->disabledIf('assignfeedback_customfeedback_testcasesQ'.$i, $dependent, $condition);
        }
    }


    /**
    *@codeCoverageIgnore
    */
    public function save_settings(stdClass $data) {
        global $DB;

        if($data->assignfeedback_witsoj_enabled){
            return false;
        }
        
        $assignData = array();
        $assignData['id'] = $this->assignment->get_instance()->id;
        $assignData['course_id'] = 0; //TODO: figure out how to get this course id.
        $assignData['mode'] = $this->get_modes()[$data->assignfeedback_customfeedback_mode];
        $assignData['language'] = $this->get_languages()[$data->assignfeedback_customfeedback_language];
        $assignData['number_of_questions'] = $this->get_question_numbers()[$data->assignfeedback_customfeedback_numQ];

        $this->set_config('mode', $assignData['mode']);
        $this->set_config('language', $assignData['language']);
        $this->set_config('numQ', $assignData['number_of_questions']);

        $isupdate = $DB->record_exists('customfeedback_assignment', ['id'=>$assignData['id']]);
        if($isupdate){
            $sql = "UPDATE {customfeedback_assignment} 
                        SET mode = :mode,
                            language = :language,
                            number_of_questions = :number_of_questions
                        WHERE id = :id
                        ";
            $DB->execute($sql, $assignData);
        }else{
            $sql = "INSERT INTO {customfeedback_assignment} VALUES(:id, :course_id, :mode,:language ,:number_of_questions)";
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
    * @codeCoverageIgnore
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
            //$filefeedback->numfiles = $this->count_files($grade->id, ASSIGNFEEDBACK_FILE_FILEAREA);
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
        $table = "<table width=100%>
                    <tr>
                        <th> Question </th>
                        <th> Verdict </th>
                    </tr>";

        $qualifies = False;
        for($i = 0;$i<$n;$i++){
            $verdict = $this->get_question_verdict($grade,$i);
            $table.="
                <tr>
                    <td>Question $i</td>
                    <td>$verdict</td>
                </tr>
            ";

        }

        $table.="</table>";
        $qualifies = $this->minimum_requirements($grade);
        if($qualifies){
             //TODO: leader board snippet.
            $table.= "<h1>Leaderboard Goes Here</h1>";

        }else{
            $table.= "<h1>DID</h1>";
            //TODO: minimum requirements.
        }

        return $table;
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
                    return "Judging Request Pending";
                    break;
                case 1:
                    return "Judging In Progress";
                    break;
                case 2:
                    return "Compilation Error";
                    break;
                case 3:
                    return "Presentation Error";
                    break;
                case 4:
                    return "Accepted";
                    break;
                case 5:
                    return "Partially Correct";
                    break;
                case 6:
                    return "Incorrect";
                    break;
                case 7:
                    return "Marker Error";
                    break;
                case 8:
                    return "Timelimit Exceeded";
                    break;
                case 9:
                    return "Aborted";
                    break; 
                case 10:
                    return "Timeout";
                    break;
                case 11:
                    return "Memory Limit Exceeded";
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
    public function update_record($question_number,$assign_id,$user_id,$memory,$runtime,$status,$grade){
        global $DB;
        $params = array();
        $params['question_number'] = $question_number;
        $params['assign_id'] = $assign_id;
        $params['user_id'] = $user_id;
        $params['memory']=$memory;
        $params['status']=$status;
        $params['runtime']=$runtime;
        $params['grade']=$grade;
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
                        SET no_of_submittions=:attempts,memory=:memory,status=:status,runtime=:runtime,question_score =:grade
                        WHERE 
                        question_number=:question_number AND
                        assign_id =:assign_id AND
                        user_id = :user_id
                        ";   
                    return $DB->execute($sql, $params);//success     
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

    public function get_marker_data($userid, $pathnamehash = null, $i){
        global $DB;

        $data = array();
        $data["userid"]    = $userid;
        $userObj = $DB->get_record("user", array("id" => $userid));
        $data["firstname"] = $userObj->firstname;
        $data["lastname"]  = $userObj->lastname;
        $data["language"]  = $this->get_config("language");
        $data["cpu_limit"] = $this->get_config("timelimit".$i);
        $data["mem_limit"] = $this->get_config("memorylimit".$i);
        $data["pe_ratio"] = 0.0;

        $fs = get_file_storage();
        if ($files = $fs->get_area_files($this->assignment->get_context()->id, 'assignfeedback_witsoj', ASSIGNFEEDBACK_CUSTOMFEEDBACK_TESTCASE_FILEAREA, '0', 'sortorder', false)) {
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
        }else{
            error_log("E1"); //TODO get rid of this
            return null;
        }

        $fs = get_file_storage();
    $file = null;
    if($pathnamehash == null){
        // Get submission from database
        $arr = array('contextid'=>$this->assignment->get_context()->id,
                        'component'=>'assignsubmission_file',
                        'filearea'=>ASSIGNSUBMISSION_FILE_FILEAREA,
                        'userid'=>$userid);
        //var_dump($arr);
        //$rec = $DB->get_records_sql('SELECT pathnamehash FROM {files}'.
        //              ' WHERE contextid = :contextid AND component = :component'.
        //              ' AND filearea = :filearea AND userid = :userid AND NOT filename = "."',
        //              $arr);
        $rec = $DB->get_records_sql('SELECT pathnamehash FROM {assign_submission} s JOIN {files} f ON s.id = f.itemid '.
                        ' WHERE f.contextid = :contextid AND f.component = :component'.
                        ' AND f.filearea = :filearea AND s.userid = :userid AND NOT f.filename = "."',
                        $arr);
        if(count($rec) == 1){
            $pathnamehash = reset($rec)->pathnamehash;
        }else{
            error_log("COUNT: " . count($rec));
            error_log("E4"); // TODO Deal with not getting a file.
            var_dump($rec);
            $pathnamehash = null;
            return null;
        }
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
