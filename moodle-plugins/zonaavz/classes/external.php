<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/externallib.php');
require_once($CFG->dirroot.'/local/mail/locallib.php');

class local_zonaavz_external extends external_api
{
    public static function send_mail_parameters()
    {
        return new external_function_parameters(array(
            'idempotencykey' => new external_value(PARAM_ALPHANUMEXT, 'Unique request key'),
            'courseshortname' => new external_value(PARAM_RAW_TRIMMED, 'Moodle course shortname'),
            'senderusername' => new external_value(PARAM_RAW_TRIMMED, 'Sender Moodle username'),
            'recipientusername' => new external_value(PARAM_RAW_TRIMMED, 'Recipient Moodle username'),
            'subject' => new external_value(PARAM_TEXT, 'Message subject'),
            'bodytext' => new external_value(PARAM_RAW, 'Plain-text message body', VALUE_DEFAULT, ''),
            'bodyhtml' => new external_value(PARAM_RAW, 'HTML message body', VALUE_DEFAULT, ''),
        ));
    }

    public static function send_mail($idempotencykey, $courseshortname, $senderusername, $recipientusername, $subject,
            $bodytext = '', $bodyhtml = '')
    {
        global $COURSE, $DB, $USER;

        $params = self::validate_parameters(self::send_mail_parameters(), array(
            'idempotencykey' => $idempotencykey,
            'courseshortname' => $courseshortname,
            'senderusername' => $senderusername,
            'recipientusername' => $recipientusername,
            'subject' => $subject,
            'bodytext' => $bodytext,
            'bodyhtml' => $bodyhtml,
        ));

        $systemcontext = context_system::instance();
        self::validate_context($systemcontext);
        require_capability('local/zonaavz:sendmail', $systemcontext);

        $existing = $DB->get_record('local_zonaavz_requests', array(
            'idempotencykey' => $params['idempotencykey'],
        ));
        if ($existing && $existing->messageid) {
            return array(
                'messageid' => (int) $existing->messageid,
                'duplicate' => true,
            );
        }

        $course = $DB->get_record('course', array(
            'shortname' => $params['courseshortname'],
        ), '*', MUST_EXIST);
        $coursecontext = context_course::instance($course->id);
        self::validate_context($coursecontext);
        require_capability('local/mail:usemail', $coursecontext);

        $sender = $DB->get_record('user', array(
            'username' => $params['senderusername'],
            'deleted' => 0,
            'suspended' => 0,
            'confirmed' => 1,
        ), '*', MUST_EXIST);

        if (!is_enrolled($coursecontext, $sender, '', true)) {
            throw new invalid_parameter_exception('El remitente no está matriculado en este curso.');
        }

        $isteacher = false;
        foreach (get_user_roles($coursecontext, $sender->id, true) as $role) {
            $archetype = isset($role->archetype) ? $role->archetype : '';
            $shortname = isset($role->shortname) ? $role->shortname : '';
            if (in_array($archetype, array('teacher', 'editingteacher'), true)
                    || in_array($shortname, array('teacher', 'editingteacher'), true)) {
                $isteacher = true;
                break;
            }
        }

        if (!$isteacher) {
            throw new invalid_parameter_exception('El remitente no tiene rol de profesor en este curso.');
        }

        if (!has_capability('local/mail:usemail', $coursecontext, $sender->id)) {
            throw new invalid_parameter_exception('El profesor no puede utilizar local_mail en este curso.');
        }

        $recipient = $DB->get_record('user', array(
            'username' => $params['recipientusername'],
            'deleted' => 0,
            'suspended' => 0,
            'confirmed' => 1,
        ), '*', MUST_EXIST);

        $previouscourse = $COURSE;
        $COURSE = $course;

        try {
            if (!local_mail_valid_recipient($recipient->id)) {
                throw new invalid_parameter_exception('El destinatario no puede recibir mensajes en este curso.');
            }

            $bodytext = $params['bodytext'];
            if (strpos($bodytext, 'base64:') === 0) {
                $decoded = base64_decode(substr($bodytext, 7), true);
                if ($decoded === false) {
                    throw new invalid_parameter_exception('El contenido codificado del mensaje no es válido.');
                }
                $bodytext = $decoded;
            }

            $bodyhtml = $params['bodyhtml'];
            if (strpos($bodyhtml, 'base64:') === 0) {
                $decoded = base64_decode(substr($bodyhtml, 7), true);
                if ($decoded === false) {
                    throw new invalid_parameter_exception('El contenido HTML codificado del mensaje no es válido.');
                }
                $bodyhtml = $decoded;
            }

            $content = trim($bodyhtml) !== ''
                ? $bodyhtml
                : nl2br(s($bodytext));

            $transaction = $DB->start_delegated_transaction();

            $request = new stdClass();
            $request->idempotencykey = $params['idempotencykey'];
            $request->messageid = 0;
            $request->timecreated = time();
            $requestid = $DB->insert_record('local_zonaavz_requests', $request);

            $message = local_mail_message::create($sender->id, $course->id);
            $message->save(trim($params['subject']), $content, FORMAT_HTML);
            $message->add_recipient('to', $recipient->id);
            $message->send();

            $DB->set_field('local_zonaavz_requests', 'messageid', $message->id(), array('id' => $requestid));
            $transaction->allow_commit();

            local_mail_send_notifications($message);

            return array(
                'messageid' => (int) $message->id(),
                'duplicate' => false,
            );
        } finally {
            $COURSE = $previouscourse;
        }
    }

    public static function send_mail_returns()
    {
        return new external_single_structure(array(
            'messageid' => new external_value(PARAM_INT, 'local_mail message ID'),
            'duplicate' => new external_value(PARAM_BOOL, 'Whether this request was already processed'),
        ));
    }
}
