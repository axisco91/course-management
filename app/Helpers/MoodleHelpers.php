<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Throwable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MoodleHelpers
{
    private const CACHE_TTL_SECONDS = 600;

    private static function callMoodle(Client $client, $url, $token, string $function, array $query = []): ?array
    {
        $endpoint = rtrim((string) $url, '/').'/webservice/rest/server.php';

        try {
            $response = $client->request('GET', $endpoint, [
                'query' => array_merge([
                    'wstoken' => $token,
                    'wsfunction' => $function,
                    'moodlewsrestformat' => 'json',
                ], $query),
                'timeout' => 12,
                'connect_timeout' => 5,
            ]);
        } catch (RequestException $e) {
            Log::warning('Moodle request failed', [
                'wsfunction' => $function,
                'message' => $e->getMessage(),
            ]);
            return null;
        } catch (Throwable $e) {
            Log::warning('Unexpected Moodle request error', [
                'wsfunction' => $function,
                'message' => $e->getMessage(),
            ]);
            return null;
        }

        $data = json_decode((string) $response->getBody(), true);
        if (!is_array($data)) {
            Log::warning('Invalid Moodle response format', [
                'wsfunction' => $function,
            ]);
            return null;
        }

        if (isset($data['exception']) || isset($data['errorcode'])) {
            Log::warning('Moodle returned error payload', [
                'wsfunction' => $function,
                'errorcode' => $data['errorcode'] ?? null,
                'message' => $data['message'] ?? null,
            ]);
            return null;
        }

        return $data;
    }

    private static function cacheKey(string $prefix, $url, array $parts = []): string
    {
        return 'moodle:'.md5((string) $url).':'.$prefix.':'.md5(json_encode($parts));
    }

    private static function getCachedCourseContents(Client $client, $courseId, $url, $token): ?array
    {
        $cacheKey = self::cacheKey('course_contents', $url, [$courseId]);

        return Cache::remember($cacheKey, now()->addSeconds(self::CACHE_TTL_SECONDS), function () use ($client, $courseId, $url, $token) {
            return self::callMoodle($client, $url, $token, 'core_course_get_contents', [
                'courseid' => $courseId,
            ]);
        });
    }

    private static function getCachedUserByUsername(Client $client, $username, $url, $token): ?array
    {
        $cacheKey = self::cacheKey('user_by_username', $url, [$username]);

        return Cache::remember($cacheKey, now()->addSeconds(self::CACHE_TTL_SECONDS), function () use ($client, $username, $url, $token) {
            $users = self::callMoodle($client, $url, $token, 'core_user_get_users', [
                'criteria[0][key]' => 'username',
                'criteria[0][value]' => $username,
            ]);

            if (!is_array($users) || empty($users['users']) || !isset($users['users'][0]['id'])) {
                return null;
            }

            return $users['users'][0];
        });
    }

    private static function getCachedEnrolledUsers(Client $client, $courseId, $url, $token): ?array
    {
        $cacheKey = self::cacheKey('enrolled_users', $url, [$courseId]);

        return Cache::remember($cacheKey, now()->addSeconds(self::CACHE_TTL_SECONDS), function () use ($client, $courseId, $url, $token) {
            return self::callMoodle($client, $url, $token, 'core_enrol_get_enrolled_users', [
                'courseid' => $courseId,
            ]);
        });
    }

    private static function defaultStudentDetails($courseId): array
    {
        return [
            'courseId' => $courseId,
            'userId' => null,
            'finishedActivities' => 0,
            'evaluationFinalDone' => false,
            'lastAccess' => 'Never accessed',
            'unitsViewed' => 0,
            'totalTime' => GeneralHelpers::seconds_to_human_readable(0),
            'cuestionar' => false,
            'error' => null,
        ];
    }

    private static function normalizeModuleName(string $name): string
    {
        return strtolower(Str::ascii(trim($name)));
    }

    private static function isFinalEvaluation(string $moduleType, string $moduleName): bool
    {
        if (!in_array($moduleType, ['scorm', 'quiz'], true)) {
            return false;
        }

        $name = self::normalizeModuleName($moduleName);

        return str_contains($name, 'final')
            && ($moduleType === 'quiz'
                || str_contains($name, 'evaluacion')
                || str_contains($name, 'examen')
                || str_contains($name, 'cuestionario'));
    }

    private static function isSatisfactionSurvey(string $moduleName): bool
    {
        $name = self::normalizeModuleName($moduleName);

        return str_contains($name, 'satisfaccion')
            || str_contains($name, 'encuesta de satisfaccion');
    }

    private static function isEvaluationActivity(string $moduleType, string $moduleName): bool
    {
        if (self::isFinalEvaluation($moduleType, $moduleName) || self::isSatisfactionSurvey($moduleName)) {
            return false;
        }

        if ($moduleType !== 'scorm') {
            return false;
        }

        $name = self::normalizeModuleName($moduleName);

        return str_contains($name, 'autoevaluacion')
            || str_contains($name, 'evaluacion')
            || str_contains($name, 'examen')
            || str_contains($name, 'cuestionario');
    }

    private static function isContentUnit(string $moduleType, string $moduleName): bool
    {
        return $moduleType === 'scorm'
            && !self::isFinalEvaluation($moduleType, $moduleName)
            && !self::isSatisfactionSurvey($moduleName)
            && !self::isEvaluationActivity($moduleType, $moduleName);
    }

    /**
     * Get the course
     */
    public static function getCourseByShortname($shortname, $url, $token) {
        $client = new Client();
        $cacheKey = self::cacheKey('course_by_shortname', $url, [$shortname]);

        return Cache::remember($cacheKey, now()->addSeconds(self::CACHE_TTL_SECONDS), function () use ($client, $shortname, $url, $token) {
            $courses = self::callMoodle($client, $url, $token, 'core_course_get_courses');

            if (!is_array($courses)) {
                return null;
            }

            foreach ($courses as $course) {
                if (
                    is_array($course)
                    && isset($course['shortname'])
                    && $course['shortname'] === $shortname
                ) {
                    return $course;
                }
            }

            return null;
        });
    }

    public static function getActivityCount($courseId, $url, $token) {
        $result = [
            'assignmentCount' => 0,
            'normalScormCount' => 0,
            'finalEvaluationCount' => 0,
            'error' => null,
        ];

        $client = new Client();
        $courseContents = self::getCachedCourseContents($client, $courseId, $url, $token);

        if (!is_array($courseContents)) {
            $result['error'] = 'Unable to fetch course contents';
            return $result;
        }

        foreach ($courseContents as $section) {
            if (!isset($section['modules']) || !is_array($section['modules'])) {
                continue;
            }

            foreach ($section['modules'] as $module) {
                $moduleName = (string) ($module['name'] ?? '');
                $moduleType = (string) ($module['modname'] ?? '');

                if (self::isFinalEvaluation($moduleType, $moduleName)) {
                    $result['finalEvaluationCount']++;
                }

                if (self::isEvaluationActivity($moduleType, $moduleName)) {
                    $result['assignmentCount']++;
                }

                if (self::isContentUnit($moduleType, $moduleName)) {
                    $result['normalScormCount']++;
                }
            }
        }

        return $result;
    }

    public static function getStudentCourseDetails($courseId, $username, $url, $token) {
        $client = new Client();
        $result = self::defaultStudentDetails($courseId);
        $errors = [];

        $user = self::getCachedUserByUsername($client, $username, $url, $token);

        if (!is_array($user) || !isset($user['id'])) {
            $result['error'] = 'User not found in Moodle';
            return $result;
        }

        $userId = $user['id'];
        $result['userId'] = $userId;

        $courseContents = self::getCachedCourseContents($client, $courseId, $url, $token);

        $cmidToName = [];
        $cmidToType = [];
        if (is_array($courseContents)) {
            foreach ($courseContents as $section) {
                if (!isset($section['modules']) || !is_array($section['modules'])) {
                    continue;
                }

                foreach ($section['modules'] as $module) {
                    if (isset($module['id'])) {
                        $cmidToName[$module['id']] = (string) ($module['name'] ?? '');
                        $cmidToType[$module['id']] = (string) ($module['modname'] ?? '');
                    }
                }
            }
        } else {
            $errors[] = 'Unable to fetch course contents';
        }

        $completionData = self::callMoodle($client, $url, $token, 'core_completion_get_activities_completion_status', [
            'courseid' => $courseId,
            'userid' => $userId,
        ]);

        $finishedActivities = 0;
        $evaluationFinalDone = false;
        $normalScormCount = 0;
        $satisfactionEvaluationDone = false;

        if (is_array($completionData) && isset($completionData['statuses']) && is_array($completionData['statuses'])) {
            foreach ($completionData['statuses'] as $module) {
                $cmid = $module['cmid'] ?? null;
                $moduleName = $cmid ? ($cmidToName[$cmid] ?? '') : '';
                $moduleType = (string) ($module['modname'] ?? ($cmid ? ($cmidToType[$cmid] ?? '') : ''));
                $completed = (($module['state'] ?? 0) > 0);

                if ($completed && self::isEvaluationActivity($moduleType, $moduleName)) {
                    $finishedActivities++;
                }

                if ($completed && self::isFinalEvaluation($moduleType, $moduleName)) {
                    $evaluationFinalDone = true;
                }

                if ($completed && self::isContentUnit($moduleType, $moduleName)) {
                    $normalScormCount++;
                }

                if ($completed && self::isSatisfactionSurvey($moduleName)) {
                    $satisfactionEvaluationDone = true;
                }
            }
        } else {
            $errors[] = 'Unable to fetch completion status';
        }

        $enrolledUsers = self::getCachedEnrolledUsers($client, $courseId, $url, $token);

        if (is_array($enrolledUsers)) {
            $lastAccess = null;
            foreach ($enrolledUsers as $user) {
                if (($user['id'] ?? null) === $userId) {
                    $lastAccess = $user['lastaccess'] ?? null;
                    break;
                }
            }

            if ($lastAccess) {
                $result['lastAccess'] = Carbon::createFromTimestamp((int) $lastAccess, 'UTC')
                    ->setTimezone('Europe/Madrid')
                    ->format('Y-m-d H:i:s');
            }
        } else {
            $errors[] = 'Unable to fetch enrolled users';
        }

        $times = self::callMoodle($client, $url, $token, 'local_dedication_get_dedication', [
            'userid' => $userId,
            'courseid' => $courseId,
        ]);

        if (is_array($times) && isset($times['total_time']) && is_numeric($times['total_time'])) {
            $result['totalTime'] = GeneralHelpers::seconds_to_human_readable((int) $times['total_time']);
        } else {
            if (!is_array($times)) {
                $errors[] = 'Unable to fetch dedication time';
            } else {
                $errors[] = 'Invalid dedication time response';
            }
        }

        $result['finishedActivities'] = $finishedActivities;
        $result['evaluationFinalDone'] = $evaluationFinalDone;
        $result['unitsViewed'] = $normalScormCount;
        $result['cuestionar'] = $satisfactionEvaluationDone;

        if (!empty($errors)) {
            $result['error'] = implode(' | ', array_unique($errors));
            Log::warning('Partial Moodle data in getStudentCourseDetails', [
                'course_id' => $courseId,
                'username' => $username,
                'errors' => $result['error'],
            ]);
        }

        return $result;
    }
}
