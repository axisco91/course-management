<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/externallib.php');
require_once($CFG->dirroot.'/course/externallib.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/user/lib.php');

class local_zonaavz_provisioning_external extends external_api
{
    public static function site_info_parameters() { return new external_function_parameters(array()); }

    public static function site_info()
    {
        global $CFG, $DB;
        self::validate_context(context_system::instance());
        require_capability('local/zonaavz:sendmail', context_system::instance());
        $mailversion = $DB->get_field('config_plugins', 'value', array('plugin' => 'local_mail', 'name' => 'version'));
        $plugininfo = core_plugin_manager::instance()->get_plugin_info('local_zonaavz');
        return array(
            'release' => (string) $CFG->release,
            'version' => (string) $CFG->version,
            'connectorversion' => $plugininfo ? (string) $plugininfo->release : '',
            'localmailversion' => $mailversion ? (string) $mailversion : '',
            'compatible' => version_compare($CFG->release, '3.9', '>=') && version_compare($CFG->release, '4.2', '<'),
        );
    }

    public static function site_info_returns()
    {
        return new external_single_structure(array(
            'release' => new external_value(PARAM_TEXT), 'version' => new external_value(PARAM_TEXT),
            'connectorversion' => new external_value(PARAM_TEXT), 'localmailversion' => new external_value(PARAM_TEXT),
            'compatible' => new external_value(PARAM_BOOL),
        ));
    }

    public static function list_courses_parameters() { return new external_function_parameters(array()); }

    public static function list_courses()
    {
        global $DB, $SITE;
        self::validate_context(context_system::instance());
        require_capability('local/zonaavz:sendmail', context_system::instance());
        $records = $DB->get_records_select('course', 'id <> :siteid', array('siteid' => $SITE->id), 'fullname ASC', 'id,fullname,shortname,idnumber,category,startdate,enddate,visible');
        $courses = array();
        foreach ($records as $course) {
            $courses[] = array('id' => (int) $course->id, 'fullname' => (string) $course->fullname,
                'shortname' => (string) $course->shortname, 'idnumber' => (string) $course->idnumber,
                'categoryid' => (int) $course->category, 'startdate' => (int) $course->startdate,
                'enddate' => (int) $course->enddate, 'visible' => (bool) $course->visible);
        }
        return array('courses' => $courses);
    }

    public static function list_courses_returns()
    {
        return new external_single_structure(array('courses' => new external_multiple_structure(new external_single_structure(array(
            'id' => new external_value(PARAM_INT), 'fullname' => new external_value(PARAM_TEXT),
            'shortname' => new external_value(PARAM_RAW), 'idnumber' => new external_value(PARAM_RAW),
            'categoryid' => new external_value(PARAM_INT), 'startdate' => new external_value(PARAM_INT),
            'enddate' => new external_value(PARAM_INT), 'visible' => new external_value(PARAM_BOOL),
        )))));
    }

    public static function provision_course_parameters()
    {
        return new external_function_parameters(array('payload' => new external_value(PARAM_RAW)));
    }

    public static function provision_course($payload)
    {
        global $DB;
        self::validate_context(context_system::instance());
        require_capability('local/zonaavz:sendmail', context_system::instance());
        if (strpos($payload, 'base64:') === 0) { $payload = base64_decode(substr($payload, 7), true); }
        $data = json_decode($payload, true);
        if (!is_array($data)) { throw new invalid_parameter_exception('Payload de aprovisionamiento no válido.'); }

        $course = !empty($data['existing_course_id']) ? $DB->get_record('course', array('id' => (int) $data['existing_course_id'])) : null;
        if (!$course && !empty($data['idnumber'])) { $course = $DB->get_record('course', array('idnumber' => $data['idnumber'])); }
        $creatednew = false;
        if (!$course) {
            $source = $DB->get_record('course', array('id' => (int) ($data['source_course_id'] ?? 0)), '*', MUST_EXIST);
            $options = array(
                array('name' => 'users', 'value' => 0), array('name' => 'role_assignments', 'value' => 0),
                array('name' => 'comments', 'value' => 0), array('name' => 'userscompletion', 'value' => 0),
                array('name' => 'logs', 'value' => 0), array('name' => 'grade_histories', 'value' => 0),
            );
            $created = core_course_external::duplicate_course($source->id, clean_param($data['fullname'], PARAM_TEXT),
                clean_param($data['shortname'], PARAM_RAW_TRIMMED), $source->category, 1, $options);
            $course = $DB->get_record('course', array('id' => (int) $created['id']), '*', MUST_EXIST);
            $creatednew = true;
        }

        $course->fullname = clean_param($data['fullname'], PARAM_TEXT);
        $course->shortname = clean_param($data['shortname'], PARAM_RAW_TRIMMED);
        $course->idnumber = clean_param($data['idnumber'], PARAM_RAW_TRIMMED);
        $course->startdate = (int) ($data['startdate'] ?? 0);
        $course->enddate = (int) ($data['enddate'] ?? 0);
        update_course($course);
        $keepusers = array();
        if (!empty($data['teacher'])) { $keepusers[] = self::sync_enrolment($course, $data['teacher']); }
        foreach (($data['students'] ?? array()) as $student) { $keepusers[] = self::sync_enrolment($course, $student); }
        foreach (($data['required_users'] ?? array()) as $requireduser) {
            $keepusers[] = self::sync_enrolment($course, $requireduser, false, true);
        }
        self::suspend_missing_enrolments($course, array_filter($keepusers));
        return array('courseid' => (int) $course->id, 'shortname' => (string) $course->shortname, 'created' => $creatednew);
    }

    private static function sync_enrolment($course, array $data, $createifmissing = true, $assignrole = true)
    {
        global $DB, $CFG;
        $username = clean_param($data['username'] ?? '', PARAM_USERNAME);
        if ($username === '') { return 0; }
        $user = $DB->get_record('user', array('username' => $username, 'mnethostid' => $CFG->mnet_localhost_id, 'deleted' => 0));
        if (!$user) {
            if (!$createifmissing) {
                throw new moodle_exception('Usuario obligatorio no encontrado en Moodle: '.$username.'.');
            }
            $user = (object) array('auth' => 'manual', 'confirmed' => 1, 'mnethostid' => $CFG->mnet_localhost_id,
                'username' => $username, 'firstname' => clean_param($data['firstname'] ?? '-', PARAM_NOTAGS),
                'lastname' => clean_param($data['lastname'] ?? '-', PARAM_NOTAGS),
                'email' => clean_param($data['email'] ?? '', PARAM_EMAIL),
                'password' => !empty($data['password']) ? $data['password'] : random_string(20));
            $user->id = user_create_user($user, true, false);
            $user = $DB->get_record('user', array('id' => $user->id), '*', MUST_EXIST);
        } else {
            $user->firstname = clean_param($data['firstname'] ?? $user->firstname, PARAM_NOTAGS);
            $user->lastname = clean_param($data['lastname'] ?? $user->lastname, PARAM_NOTAGS);
            if (!empty($data['email'])) { $user->email = clean_param($data['email'], PARAM_EMAIL); }
            user_update_user($user, false, false);
        }
        $role = null;
        if ($assignrole) {
            $roleshortname = clean_param($data['role'] ?? 'student', PARAM_ALPHANUMEXT);
            if ($roleshortname === '') { throw new moodle_exception('El usuario '.$username.' no tiene rol Moodle configurado.'); }
            $role = $DB->get_record('role', array('shortname' => $roleshortname));
            if (!$role) { $role = $DB->get_record('role', array('archetype' => $roleshortname), '*', MUST_EXIST); }
        }
        $plugin = enrol_get_plugin('manual');
        $instance = null;
        $fallbackinstance = null;
        foreach (enrol_get_instances($course->id, true) as $candidate) {
            if ($candidate->enrol !== 'manual') { continue; }
            if ($candidate->name === 'ZonaAvz') { $instance = $candidate; break; }
            if (!$fallbackinstance) { $fallbackinstance = $candidate; }
        }
        $instance = $instance ?: $fallbackinstance;
        if (!$instance) {
            $instanceid = $plugin->add_instance($course, array('name' => 'ZonaAvz'));
            if (!$instanceid) { throw new moodle_exception('No se ha podido crear la matrícula manual del curso.'); }
            $instance = $DB->get_record('enrol', array('id' => $instanceid), '*', MUST_EXIST);
        }
        $status = !empty($data['suspended']) ? ENROL_USER_SUSPENDED : ENROL_USER_ACTIVE;
        $plugin->enrol_user($instance, $user->id, $role ? $role->id : null, 0, 0, $status);
        return (int) $user->id;
    }

    private static function suspend_missing_enrolments($course, array $keepusers)
    {
        global $DB;
        $instance = $DB->get_record('enrol', array('courseid' => $course->id, 'enrol' => 'manual', 'name' => 'ZonaAvz'));
        if (!$instance) { return; }
        $plugin = enrol_get_plugin('manual');
        $enrolments = $DB->get_records('user_enrolments', array('enrolid' => $instance->id));
        foreach ($enrolments as $enrolment) {
            if (!in_array((int) $enrolment->userid, $keepusers, true)) {
                $plugin->update_user_enrol($instance, $enrolment->userid, ENROL_USER_SUSPENDED);
            }
        }
    }

    public static function provision_course_returns()
    {
        return new external_single_structure(array('courseid' => new external_value(PARAM_INT),
            'shortname' => new external_value(PARAM_RAW), 'created' => new external_value(PARAM_BOOL)));
    }
}
