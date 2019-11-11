<?php
 
function xmldb_assignfeedback_customfeedback_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();
 
    $result = TRUE;
 
    if ($oldversion < 2019121003) {

        // Define table customfeedback_group_subs to be created.
        $table = new xmldb_table('customfeedback_group_subs');

        // Adding fields to table customfeedback_group_subs.
        $table->add_field('question_number', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('assign_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('group_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('memory', XMLDB_TYPE_NUMBER, '10, 3', null, XMLDB_NOTNULL, null, '-1');
        $table->add_field('runtime', XMLDB_TYPE_NUMBER, '10, 3', null, XMLDB_NOTNULL, null, '-1');
        $table->add_field('status', XMLDB_TYPE_INTEGER, '5', null, XMLDB_NOTNULL, null, null);
        $table->add_field('contenthash', XMLDB_TYPE_CHAR, '40', null, null, null, null);
        $table->add_field('score', XMLDB_TYPE_NUMBER, '10, 3', null, null, null, '-1');

        // Adding keys to table customfeedback_group_subs.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('question_number', 'assign_id', 'group_id'));

        // Conditionally launch create table for customfeedback_group_subs.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Customfeedback savepoint reached.
        upgrade_plugin_savepoint(true, 2019121003, 'assignfeedback', 'customfeedback');
    }


    if ($oldversion < 2019121006) {

        // Define table customfeedback_cron_cmid to be created.
        $table = new xmldb_table('customfeedback_cron_cmid');

        // Adding fields to table customfeedback_cron_cmid.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('cmid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('active', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table customfeedback_cron_cmid.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for customfeedback_cron_cmid.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Customfeedback savepoint reached.
        upgrade_plugin_savepoint(true, 2019121006, 'assignfeedback', 'customfeedback');
    }



 
    return $result;
} 
?>