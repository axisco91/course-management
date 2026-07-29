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

    public static function list_categories_parameters() { return new external_function_parameters(array()); }

    public static function list_categories()
    {
        global $DB;
        self::validate_context(context_system::instance());
        require_capability('local/zonaavz:sendmail', context_system::instance());
        $records = $DB->get_records('course_categories', null, 'sortorder ASC', 'id,name,parent,idnumber,visible');
        $paths = array();
        $pathfor = function ($category) use (&$pathfor, &$paths, $records) {
            if (isset($paths[$category->id])) { return $paths[$category->id]; }
            $name = format_string($category->name, true, array('context' => context_coursecat::instance($category->id)));
            if (!$category->parent || !isset($records[$category->parent])) {
                return $paths[$category->id] = $name;
            }
            return $paths[$category->id] = $pathfor($records[$category->parent]).' / '.$name;
        };
        $categories = array();
        foreach ($records as $category) {
            $categories[] = array(
                'id' => (int) $category->id,
                'name' => (string) $category->name,
                'path' => $pathfor($category),
                'parent' => (int) $category->parent,
                'idnumber' => (string) $category->idnumber,
                'visible' => (bool) $category->visible,
            );
        }
        return array('categories' => $categories);
    }

    public static function list_categories_returns()
    {
        return new external_single_structure(array(
            'categories' => new external_multiple_structure(new external_single_structure(array(
                'id' => new external_value(PARAM_INT),
                'name' => new external_value(PARAM_TEXT),
                'path' => new external_value(PARAM_TEXT),
                'parent' => new external_value(PARAM_INT),
                'idnumber' => new external_value(PARAM_RAW),
                'visible' => new external_value(PARAM_BOOL),
            ))),
        ));
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
            $categoryid = (int) ($data['category_id'] ?? 0);
            $provisioningversion = (int) ($data['provisioning_version'] ?? 1);
            if (!$categoryid && $provisioningversion >= 2) {
                throw new invalid_parameter_exception('La categoría Moodle de destino es obligatoria.');
            }
            if (!$categoryid) { $categoryid = (int) $source->category; }
            $DB->get_record('course_categories', array('id' => $categoryid), '*', MUST_EXIST);
            $options = array(
                array('name' => 'users', 'value' => 0), array('name' => 'role_assignments', 'value' => 0),
                array('name' => 'comments', 'value' => 0), array('name' => 'userscompletion', 'value' => 0),
                array('name' => 'logs', 'value' => 0), array('name' => 'grade_histories', 'value' => 0),
            );
            $created = core_course_external::duplicate_course($source->id, clean_param($data['fullname'], PARAM_TEXT),
                clean_param($data['shortname'], PARAM_RAW_TRIMMED), $categoryid, 1, $options);
            $course = $DB->get_record('course', array('id' => (int) $created['id']), '*', MUST_EXIST);
            $creatednew = true;
        }

        $updatemetadata = !array_key_exists('update_course_metadata', $data) || !empty($data['update_course_metadata']);
        if ($updatemetadata) {
            $course->fullname = clean_param($data['fullname'], PARAM_TEXT);
            $course->shortname = clean_param($data['shortname'], PARAM_RAW_TRIMMED);
            $course->idnumber = clean_param($data['idnumber'], PARAM_RAW_TRIMMED);
            $course->startdate = (int) ($data['startdate'] ?? 0);
            $course->enddate = (int) ($data['enddate'] ?? 0);
            if (!empty($data['category_id'])) {
                $DB->get_record('course_categories', array('id' => (int) $data['category_id']), '*', MUST_EXIST);
                $course->category = (int) $data['category_id'];
            }
            update_course($course);
        }
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
        $emptyfallback = null;
        foreach (enrol_get_instances($course->id, false) as $candidate) {
            if ($candidate->enrol !== 'manual') { continue; }
            if ($candidate->name === 'ZonaAvz') { $instance = $candidate; break; }
            if (!$emptyfallback && !$DB->record_exists('user_enrolments', array('enrolid' => $candidate->id))) {
                $emptyfallback = $candidate;
            }
        }
        if (!$instance && $emptyfallback) {
            $DB->set_field('enrol', 'name', 'ZonaAvz', array('id' => $emptyfallback->id));
            $emptyfallback->name = 'ZonaAvz';
            $instance = $emptyfallback;
        }
        if (!$instance) {
            $instanceid = $plugin->add_instance($course, array(
                'name' => 'ZonaAvz',
                'status' => ENROL_INSTANCE_ENABLED,
            ));
            if (!$instanceid) { throw new moodle_exception('No se ha podido crear la matrícula manual del curso.'); }
            $instance = $DB->get_record('enrol', array('id' => $instanceid), '*', MUST_EXIST);
        }
        if ((int) $instance->status !== ENROL_INSTANCE_ENABLED) {
            $plugin->update_status($instance, ENROL_INSTANCE_ENABLED);
            $instance->status = ENROL_INSTANCE_ENABLED;
        }
        $status = !empty($data['suspended']) ? ENROL_USER_SUSPENDED : ENROL_USER_ACTIVE;
        $enrolstartdate = max(0, (int) ($data['enrolstartdate'] ?? 0));
        $enrolenddate = max(0, (int) ($data['enrolenddate'] ?? 0));
        if ($enrolenddate && $enrolstartdate && $enrolenddate < $enrolstartdate) {
            throw new invalid_parameter_exception('El fin de matrícula no puede ser anterior al inicio.');
        }
        $plugin->enrol_user(
            $instance,
            $user->id,
            $role ? $role->id : null,
            $enrolstartdate,
            $enrolenddate,
            $status
        );
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
