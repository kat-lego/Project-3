<?php
namespace assignfeedback_customfeedback\task;

require_once($CFG->dirroot . '/mod/assign/locallib.php');
require_once($CFG->libdir . '/gradelib.php');
require_once($CFG->libdir . '/accesslib.php');
require_once($CFG->dirroot . '/mod/assign/feedback/customfeedback/locallib.php');

/**
 * An example of a scheduled task.
 */
class auto_grading_cron extends \core\task\scheduled_task {
 
    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name() {
        return "Competitive Assignment Autograding";
    }
 
    /**
     * Execute the task.
     */
    public function execute() {
        \assign_feedback_customfeedback::execute();
    }


}