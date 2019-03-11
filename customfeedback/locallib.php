<?php
/**
 * Created by PhpStorm.
 * User: 1614669
 * Date: 2019/03/07
 * Time: 9:01 PM
 */
class assign_feedback_customfeedback extends assign_feedback_plugin{
        
    
    //Get name is abstract in the stock feedback_plugin,returns the plugin name
    public function get_name() {
        return get_string('pluginname', 'assignfeedback_customfeedback');
    }

    /**
    *adds a list of settings to the form, when building the settings page for assignment
    **/
    public function get_settings(MoodleQuickForm $mform) {
        $mform->addElement('assignfeedback_file_fileextensions', get_string('allowedfileextensions', 'assignfeedback_customfeedback'));
        $mform->setType('assignfeedback_customfeedback_fileextensions', PARAM_FILE);
    }


    /**
    *saves the submission page settings
    **/
    public function save_settings(stdClass $data) {
        $this->set_config('allowedfileextensions', $data->allowedfileextensions);
        return true;
    }
    
    //except it has the grade object to associate the settings with a single grade attempt
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
    
    /**
    *The is_feedback_modified function is called before feedback is saved.
    *if feedback has not been modified then the save() method is not called
    */
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
    
    //Saves the student's grade into a file,not important for sprint1
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

    //Display a summary of the feedback to both markers and students. 
    public function view_summary(stdClass $grade, & $showviewlink) {
        $count = $this->count_files($grade->id, ASSIGNFEEDBACK_FILE_FILEAREA);
        // show a view all link if the number of files is over this limit
        $showviewlink = $count > ASSIGNFEEDBACK_FILE_MAXSUMMARYFILES;

        if ($count <= ASSIGNFEEDBACK_FILE_MAXSUMMARYFILES) {
            return $this->assignment->render_area_files('assignfeedback_file', ASSIGNFEEDBACK_FILE_FILEAREA, $grade->id);//filename to be changed
        } else {
            return get_string('countfiles', 'assignfeedback_file', $count);//file name to changed
        }
    }
    
    //Displays the entire feedback to both markers and students
    public function view(stdClass $grade) {
        return $this->assignment->render_area_files('assignfeedback_file', ASSIGNFEEDBACK_FILE_FILEAREA, $grade->id);//file name 
    }


    public function can_upgrade($type, $version) {

        if (($type == 'upload' || $type == 'uploadsingle') && $version >= 2011112900) {
            return true;
        }
        return false;
    }


    //assignment 2.2 upgrade settings
    public function upgrade_settings(context $oldcontext, stdClass $oldassignment, & $log) {
        // first upgrade settings (nothing to do)
        return true;
    }

    /*This function is called once per assignment instance to upgrade the settings from the old assignment to the new mod_assign
    *Returns true as there are currently no settings to upgrade. 
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

 /**If a plugin has no data to show - it can return true from the is_empty function.
    *This prevents a table row being added to the feedback summary for this plugin. 
    *It is also used to check if a grader has tried to save feedback with no data. 
    */
    public function is_empty(stdClass $submission) {
        return $this->count_files($submission->id, ASSIGNSUBMISSION_FILE_FILEAREA) == 0;
    }

    /*
    *this allows the file areas to be browsed by the moodle file manager.
    */
    public function get_file_areas() {
        return array(ASSIGNFEEDBACK_FILE_FILEAREA=>$this->get_name());
    }


    //The delete_instance function is called when the plugin is deleted.
    public function delete_instance() {
        global $DB;
        // will throw exception on failure
        $DB->delete_records('assignfeedback_file', array('assignment'=>$this->assignment->get_instance()->id));

        return true;
    }


    // feedback_comments 
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




}
