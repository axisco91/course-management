<?php
require_once("$CFG->libdir/externallib.php");

class local_dedication_external extends external_api {

    // Define parameters for the web service
    public static function get_dedication_parameters() {
        return new external_function_parameters([
            'userid' => new external_value(PARAM_INT, 'User ID'),
            'courseid' => new external_value(PARAM_INT, 'Course ID'),
        ]);
    }

    // Main function logic
    public static function get_dedication($userid, $courseid) {
        global $DB;

        // Validate parameters
        $params = self::validate_parameters(self::get_dedication_parameters(), [
            'userid' => $userid,
            'courseid' => $courseid,
        ]);

        // Initialize variables
        $dedicationTime = 0;
        $scormTime = 0;

        // Fetch logs for course dedication
        $logs = $DB->get_records_sql("
            SELECT timecreated
            FROM {logstore_standard_log}
            WHERE userid = :userid AND courseid = :courseid
            ORDER BY timecreated ASC
        ", ['userid' => $params['userid'], 'courseid' => $params['courseid']]);

        // Calculate dedication time from logs
        if (!empty($logs)) {
            $previousTime = null;
            foreach ($logs as $log) {
                if ($previousTime !== null && ($log->timecreated - $previousTime) <= 3600) {
                    $dedicationTime += $log->timecreated - $previousTime;
                }
                $previousTime = $log->timecreated;
            }
        }

        // Fetch SCORM time
        $scormRecords = $DB->get_records_sql("
            SELECT track.id, track.value
            FROM {scorm_scoes_track} track
            JOIN {scorm} scorm ON scorm.id = track.scormid
            WHERE track.userid = :userid
              AND scorm.course = :courseid
              AND track.element = :element
        ", [
            'userid' => $params['userid'],
            'courseid' => $params['courseid'],
            'element' => 'cmi.core.total_time',
        ]);

        // Calculate SCORM time
        if (!empty($scormRecords)) {
            foreach ($scormRecords as $record) {
                $scormTime += self::scorm_time_to_seconds($record->value);
            }
        }

        // Return results
        return [
            'course_time' => (int) $dedicationTime,
            'scorm_time' => (int) $scormTime,
            'total_time' => (int) ($dedicationTime + $scormTime),
        ];
    }

    // Define the return structure
    public static function get_dedication_returns() {
        return new external_single_structure([
            'course_time' => new external_value(PARAM_INT, 'Time spent in course logs (seconds)'),
            'scorm_time' => new external_value(PARAM_INT, 'Time spent in SCORM activities (seconds)'),
            'total_time' => new external_value(PARAM_INT, 'Total time spent (seconds)'),
        ]);
    }

    // Helper function to convert SCORM time to seconds
    private static function scorm_time_to_seconds($time) {
        if (!preg_match('/^\d{1,2}:\d{2}:\d{2}$/', $time)) {
            // Invalid SCORM time format, return 0
            return 0;
        }
        list($hours, $minutes, $seconds) = explode(':', $time);
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }
}
