<?php

defined('MOODLE_INTERNAL') || die();

function xmldb_local_zonaavz_upgrade($oldversion)
{
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2026072900) {
        $table = new xmldb_table('local_zonaavz_enrolments');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
        $table->add_field('enrolid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('course_fk', XMLDB_KEY_FOREIGN, array('courseid'), 'course', array('id'));
        $table->add_key('enrol_fk', XMLDB_KEY_FOREIGN, array('enrolid'), 'enrol', array('id'));
        $table->add_key('user_fk', XMLDB_KEY_FOREIGN, array('userid'), 'user', array('id'));
        $table->add_index('course_user_uix', XMLDB_INDEX_UNIQUE, array('courseid', 'userid'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2026072900, 'local', 'zonaavz');
    }

    return true;
}
