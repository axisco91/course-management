<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class MoodleHelpers
{

    /**
     * Get the course
     */
    public static function getCourseByShortname($shortname, $url, $token) {
        $client = new Client();

        try {
            $response = $client->request('GET', $url.'/webservice/rest/server.php', [
                'query' => [
                    'wstoken' => $token,
                    'wsfunction' => 'core_course_get_courses',
                    'moodlewsrestformat' => 'json',
                ]
            ]);

            $courses = json_decode($response->getBody(), true);

            foreach ($courses as $course) {
                if ($course['shortname'] === $shortname) {
                    return $course; // Curso encontrado
                }
            }

            return null; // Curso no encontrado

        } catch (RequestException $e) {
            // Manejo del error HTTP o de red
            return null;
        }
    }

    public static function getActivityCount($courseId, $url, $token) {
        $client = new Client();

        $response = $client->request('GET', $url.'/webservice/rest/server.php', [
            'query' => [
                'wstoken' => $token, // Replace with your token
                'wsfunction' => 'core_course_get_contents',
                'moodlewsrestformat' => 'json',
                'courseid' => $courseId, // The ID of the course
            ]
        ]);

        $courseContents = json_decode($response->getBody(), true);

        $assignmentCount = 0;
        $normalScormCount = 0;

        // Iterate through sections and count activities
        foreach ($courseContents as $section) {
            if (isset($section['modules'])) {
                foreach ($section['modules'] as $module) {

                    // Count assignments
                    if ($module['modname'] === 'assign') {
                        $assignmentCount++;
                    }

                    // Count normal SCORMs (exclude "extra" SCORMs based on a condition, e.g., name)
                    if ($module['modname'] === 'scorm') {
                        if (!str_contains($module['name'], 'Autoevaluación') &&
                            !str_contains($module['name'], 'Evaluación Final')) {
                            $normalScormCount++;
                        }
                    }
                }
            }
        }

        return [
            'assignmentCount' => $assignmentCount,
            'normalScormCount' => $normalScormCount,
        ];
    }

    public static function getStudentCourseDetails($courseId, $username, $url, $token) {
        $client = new Client();
        // Step 1: Fetch userid from username
        $responseUsers = $client->request('GET', $url.'/webservice/rest/server.php', [
            'query' => [
                'wstoken' => $token,
                'wsfunction' => 'core_user_get_users',
                'moodlewsrestformat' => 'json',
                'criteria[0][key]' => 'username',
                'criteria[0][value]' => $username,
            ]
        ]);

        $users = json_decode($responseUsers->getBody(), true);

        if (empty($users['users'])) {
            return ['error' => 'User not found'];
        }

        $userId = $users['users'][0]['id']; // Fetch the first matched user

        // Step 2: Fetch course contents (to map cmid to names)
        $responseContents = $client->request('GET', $url.'/webservice/rest/server.php', [
            'query' => [
                'wstoken' => $token,
                'wsfunction' => 'core_course_get_contents',
                'moodlewsrestformat' => 'json',
                'courseid' => $courseId,
            ]
        ]);

        $courseContents = json_decode($responseContents->getBody(), true);

        // Build a mapping of cmid to names
        $cmidToName = [];
        foreach ($courseContents as $section) {
            if (isset($section['modules'])) {
                foreach ($section['modules'] as $module) {
                    $cmidToName[$module['id']] = $module['name'];
                }
            }
        }

        // Step 3: Fetch activities completion status
        $responseCompletion = $client->request('GET', $url.'/webservice/rest/server.php', [
            'query' => [
                'wstoken' => $token,
                'wsfunction' => 'core_completion_get_activities_completion_status',
                'moodlewsrestformat' => 'json',
                'courseid' => $courseId,
                'userid' => $userId,
            ]
        ]);

        $completionData = json_decode($responseCompletion->getBody(), true);

        $finishedActivities = 0;
        $evaluationFinalDone = false;
        $normalScormCount = 0;

        foreach ($completionData['statuses'] as $module) {
            $cmid = $module['cmid'];
            $moduleName = $cmidToName[$cmid] ?? '';

            // Count assignments
            if ($module['modname'] === 'assign' && $module['state'] > 0) {
                $finishedActivities++;
            }

            // Check for SCORM named "Evaluación Final"
            if ($module['modname'] === 'scorm' && $module['state'] > 0 && $moduleName === 'Evaluación Final') {
                $evaluationFinalDone = true;
            }

            // Count normal SCORMs excluding "Autoevaluación" and "Evaluación Final"
            if ($module['modname'] === 'scorm' && $module['state'] > 0) {
                if (!str_contains($moduleName, 'Autoevaluación') && !str_contains($moduleName, 'Evaluación Final')) {
                    $normalScormCount++;
                }
            }
        }

        // Step 4: Fetch last access time for the course
        $responseEnrolledUsers = $client->request('GET', $url.'/webservice/rest/server.php', [
            'query' => [
                'wstoken' => $token,
                'wsfunction' => 'core_enrol_get_enrolled_users',
                'moodlewsrestformat' => 'json',
                'courseid' => $courseId,
            ]
        ]);

        $enrolledUsers = json_decode($responseEnrolledUsers->getBody(), true);

        $lastAccess = null;
        foreach ($enrolledUsers as $user) {
            if ($user['id'] === $userId) {
                $lastAccess = $user['lastaccess'];
                break;
            }
        }

        if ($lastAccess) {
            $lastAccessFormatted = Carbon::createFromTimestamp($lastAccess, 'UTC') // Parse timestamp in UTC
            ->setTimezone('Europe/Madrid') // Convert to Madrid timezone
            ->format('Y-m-d H:i:s'); // Format the date
        } else {
            $lastAccessFormatted = 'Never accessed';
        }

        $response = $client->request('GET', $url.'/webservice/rest/server.php', [
            'query' => [
                'wstoken' => $token, // Your token
                'wsfunction' => 'local_dedication_get_dedication', // Your custom service function name
                'moodlewsrestformat' => 'json', // Response format
                'userid' => $userId, // The user ID you want to check
                'courseid' => $courseId, // The course ID you want to check
            ]
        ]);
        $times = json_decode($response->getBody(), true);

        $responseCompletion = $client->request('GET', $url.'/webservice/rest/server.php', [
            'query' => [
                'wstoken' => $token, // Replace with your token
                'wsfunction' => 'core_completion_get_activities_completion_status',
                'moodlewsrestformat' => 'json',
                'courseid' => $courseId, // The course ID
                'userid' => $userId, // The user ID
            ]
        ]);

        $completionData = json_decode($responseCompletion->getBody(), true);

        $satisfactionEvaluationDone = false;
        foreach ($completionData['statuses'] as $activity) {
            // Check if the activity name matches and if it's completed
            \Log::error('Activity variable debug:', ['activity' => $activity]);

            if (isset($activity['name'])) {
                if ($activity['name'] === 'Encuesta de Satisfacción y Propuesta de Mejora' && $activity['state'] > 0) {
                    $satisfactionEvaluationDone = true;
                    break;
                }
            }
        }

        return [
            'courseId' => $courseId,
            'userId' => $userId,
            'finishedActivities' => $finishedActivities,
            'evaluationFinalDone' => $evaluationFinalDone,
            'lastAccess' => $lastAccessFormatted,
            'unitsViewed' => $normalScormCount,
            'totalTime' => GeneralHelpers::seconds_to_human_readable($times['total_time']),
            'cuestionar' => $satisfactionEvaluationDone
        ];
    }
}
