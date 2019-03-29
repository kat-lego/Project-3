<?php
/**
 * Created by PhpStorm.
 * User: 1614669
 * Date: 2019/03/07
 * Time: 9:01 PM
 */
class assign_feedback_customfeedback extends assign_feedback_plugin{


    public function get_name() {
        return get_string('pluginname', 'assignfeedback_customfeedback');
    }

    
    public function get_modes(){
        //get all assignment modes/types
        return array("Classic Mode", "Fastest Mode", "Tournament Mode", "AI Mode");
    }

    public function get_languages(){
        return explode(',', get_config('assignfeedback_customfeedback', 'languages'));
    }

    public function get_question_numbers(){
        $n = get_config('assignfeedback_customfeedback','maxquestions');
        $arr = array();
        for($i=1;$i<=$n;$i++){
            array_push($arr, $i);
        }
        return $arr;
    }

    function get_time_limits(){
        return array(1,3,5,10,20,45);
    }

    function get_memory_limits(){
        return  array('1MB','2MB','4MB','16MB');
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


    public function save_settings(stdClass $data) {
        //$this->set_config('allowedfileextensions', $data->allowedfileextensions);
        $this->set_config('mode', $this->get_modes()[$data->assignfeedback_customfeedback_mode]);
        $this->set_config('language', $this->get_languages()[$data->assignfeedback_customfeedback_language]);
        $this->set_config('numQ', $this->get_question_numbers()[$data->assignfeedback_customfeedback_numQ]);

        $n = get_config('assignfeedback_customfeedback','maxquestions');
        for($i=0;$i<$n;$i++){
            $s = '$data->assignfeedback_customfeedback_timelimitQ'.$i;
            eval("\$v=\"$s\";");
            $this->set_config('timelimit'.$i, $this->get_time_limits()[$v]);

            $s = '$data->assignfeedback_customfeedback_memorylimitQ'.$i;
            eval("\$v=\"$s\";");
            $this->set_config('memorylimit'.$i, $this->get_memory_limits()[$v]);


        }
        return true;
    }

    public function get_form_elements_for_user($grade, MoodleQuickForm $mform, stdClass $data, $userid) {

        $fileoptions = $this->get_file_options();
        $gradeid = $grade ? $grade->id : 0;
        $elementname = 'files_' . $userid;

        $data = file_prepare_standard_filemanager($data,
                                                  $elementname,
                                                  $fileoptions,
                                                  $this->assignment->get_context(),
                                                  'assignfeedback_customfeedback',
                                                  ASSIGNFEEDBACK_FILE_FILEAREA,
                                                  $gradeid);
        $mform->addElement('filemanager', $elementname . '_filemanager', html_writer::tag('span', $this->get_name(),
            array('class' => 'accesshide')), null, $fileoptions);
        return true;
    }
    public function is_feedback_modified(stdClass $grade, stdClass $data) {
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
            ASSIGNFEEDBACK_FILE_FILEAREA,
            $grade->id);

        $filefeedback = $this->get_file_feedback($grade->id);
        if ($filefeedback) {
            $filefeedback->numfiles = $this->count_files($grade->id, ASSIGNFEEDBACK_FILE_FILEAREA);
            return $DB->update_record('assignfeedback_file', $filefeedback);
        } else {
            $filefeedback = new stdClass();
            $filefeedback->numfiles = $this->count_files($grade->id, ASSIGNFEEDBACK_FILE_FILEAREA);
            $filefeedback->grade = $grade->id;
            $filefeedback->assignment = $this->assignment->get_instance()->id;
            return $DB->insert_record('assignfeedback_file', $filefeedback) > 0;
        }
    }

    public function view_summary(stdClass $grade, & $showviewlink) {
        $count = $this->count_files($grade->id, ASSIGNFEEDBACK_FILE_FILEAREA);
        // show a view all link if the number of files is over this limit
        $showviewlink = $count > ASSIGNFEEDBACK_FILE_MAXSUMMARYFILES;

        if ($count <= ASSIGNFEEDBACK_FILE_MAXSUMMARYFILES) {
            return $this->assignment->render_area_files('assignfeedback_file', ASSIGNFEEDBACK_FILE_FILEAREA, $grade->id);
        } else {
            return get_string('countfiles', 'assignfeedback_file', $count);
        }
    }

    public function view(stdClass $grade) {
        return $this->assignment->render_area_files('assignfeedback_file', ASSIGNFEEDBACK_FILE_FILEAREA, $grade->id);
    }


    public function can_upgrade($type, $version) {

        if (($type == 'upload' || $type == 'uploadsingle') && $version >= 2011112900) {
            return true;
        }
        return false;
    }



    public function upgrade_settings(context $oldcontext, stdClass $oldassignment, & $log) {
        // first upgrade settings (nothing to do)
        return true;
    }

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



    public function is_empty(stdClass $submission) {
        return $this->count_files($submission->id, ASSIGNSUBMISSION_FILE_FILEAREA) == 0;
    }


    public function get_file_areas() {
        return array(ASSIGNFEEDBACK_FILE_FILEAREA=>$this->get_name());
    }



    public function delete_instance() {
        global $DB;
        // will throw exception on failure
        $DB->delete_records('assignfeedback_file', array('assignment'=>$this->assignment->get_instance()->id));

        return true;
    }



    public function format_for_gradebook(stdClass $grade) {
        return FORMAT_MOODLE;
    }



    public function text_for_gradebook(stdClass $grade) {
        return '';
    }

    /**
     * Override to indicate a plugin supports quickgrading
     *
     * @return boolean - True if the plugin supports quickgrading
     */
    public function supports_quickgrading() {
        return false;
    }

    /**
     * Get quickgrading form elements as html
     *
     * @param int $userid The user id in the table this quickgrading element relates to
     * @param mixed $grade grade or null - The grade data. May be null if there are no grades for this user (yet)
     * @return mixed - A html string containing the html form elements required for quickgrading or false to indicate this plugin does not support quickgrading
     */
    public function get_quickgrading_html($userid, $grade) {
        return false;
    }

    /**
     * Has the plugin quickgrading form element been modified in the current form submission?
     *
     * @param int $userid The user id in the table this quickgrading element relates to
     * @param stdClass $grade The grade
     * @return boolean - true if the quickgrading form element has been modified
     */
    public function is_quickgrading_modified($userid, $grade) {
        return false;
    }

    /**
     * Save quickgrading changes
     *
     * @param int $userid The user id in the table this quickgrading element relates to
     * @param stdClass $grade The grade
     * @return boolean - true if the grade changes were saved correctly
     */
    public function save_quickgrading_changes($userid, $grade) {
        return false;
    }


    /**
     * Run cron for this plugin
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
     *
     * @param string $gradingaction The action chosen from the grading actions menu
     * @return string The page containing the form
     */
    public function grading_action($gradingaction) {
        return '';
    }

    /**
     * Return a list of the batch grading operations supported by this plugin.
     *
     * @return array - An array of action and description strings.
     *                 The action will be passed to grading_batch_operation.
     */
    public function get_grading_batch_operations() {
        return array();
    }

    /**
     * Show a batch operations form
     *
     * @param string $action The action chosen from the batch operations menu
     * @param array $users The list of selected userids
     * @return string The page containing the form
     */
    public function grading_batch_operation($action, $users) {
        return '';
    }

    /*

    

            


    */

}
