<?php
defined('MOODLE_INTERNAL') || die();
/**
 * Serves intro attachment files.
 *
 * @param mixed $course course or id of the course
 * @param mixed $cm course module or id of the course module
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options additional options affecting the file serving
 * @return bool false if file not found, does not return if found - just send the file
 */
function assignfeedback_customfeedback_pluginfile($course,
                $cm,
                context $context,
                $filearea,
                $args,
                $forcedownload,
                array $options=array()) {
    global $CFG;
    
    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    //require_login($course, false, $cm);
    //if (!has_capability('mod/assign:view', $context)) {
    //    return false;
    //}
    
    $auth = get_config('assignfeedback_customfeedback', 'secret');
    if(!isset($_POST["customfeedback_token"]) || $_POST["customfeedback_token"] !== $auth){
        die("No Auth");
    }

    require_once($CFG->dirroot . '/mod/assign/locallib.php');
    $assign = new assign($context, $cm, $course);

    if (strpos( $filearea, ASSIGNFEEDBACK_CUSTOMFEEDBACK_TESTCASE_FILEAREA) === false) {
        return false;
    }

    if (!$assign->show_intro()) {
        return false;
    }

    $itemid = (int)array_shift($args);
    if ($itemid != 0) {
        return false;
    }

    $relativepath = implode('/', $args);

    $fullpath = "/{$context->id}/assignfeedback_customfeedback/$filearea/$itemid/$relativepath";

    $fs = get_file_storage();
    if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
        return false;
    }
    send_stored_file($file, 0, 0, $forcedownload, $options);
}

