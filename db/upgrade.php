<?php
 
function xmldb_assignfeedback_customfeedback_upgrade($oldversion) {
    global $CFG;
 
    $result = TRUE;
 
// Insert PHP code from XMLDB Editor here
    if ($oldversion < 2019041402) {

        // Define field question_number to be added to customfeedback_submission.
        $table = new xmldb_table('customfeedback_submission');
        $field = new xmldb_field('question_number', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, null);

        // Conditionally launch add field question_number.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Customfeedback savepoint reached.
        upgrade_plugin_savepoint(true, 2019041402, 'assignfeedback', 'customfeedback');
    }

 
    return $result;
}
?>