<?php
/**
 * Created by PhpStorm.
 * User: 1614669
 * Date: 2019/03/07
 * Time: 9:01 PM
 */
class assign_feedback_file extends assign_feedback_plugin{
    public function get_name() {
        return get_string('file', 'assignfeedback_customfeedback');
    }


    public function get_settings(MoodleQuickForm $mform) {
        $mform->addElement('assignfeedback_file_fileextensions', get_string('allowedfileextensions', 'assignfeedback_file'));
        $mform->setType('assignfeedback_file_fileextensions', PARAM_FILE);
    }


    public function save_settings(stdClass $data) {
        $this->set_config('allowedfileextensions', $data->allowedfileextensions);
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
                                                  'assignfeedback_file',
                                                  ASSIGNFEEDBACK_FILE_FILEAREA,
                                                  $gradeid);
        $mform->addElement('filemanager', $elementname . '_filemanager', html_writer::tag('span', $this->get_name(),
            array('class' => 'accesshide')), null, $fileoptions);

        return true;
    }

}