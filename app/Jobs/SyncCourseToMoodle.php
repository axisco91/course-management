<?php

namespace App\Jobs;

use App\Models\Course;
use App\Models\MoodleCourseTemplate;
use App\Models\MoodleSyncRun;
use App\Services\MoodleProvisioningClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use RuntimeException;

class SyncCourseToMoodle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300;

    public function __construct(public int $courseId, public string $operation = 'sync')
    {
    }

    public function handle(MoodleProvisioningClient $client): void
    {
        $course = Course::with(['webPlatform', 'trainingAction', 'teacher', 'registrations.student'])->findOrFail($this->courseId);
        if ($course->moodle_mode === 'disabled') {
            $course->update(['moodle_sync_status' => 'disconnected', 'moodle_sync_error' => null]);
            return;
        }
        if (!$course->webPlatform) {
            throw new RuntimeException('El curso no tiene plataforma Moodle configurada.');
        }

        $run = MoodleSyncRun::create([
            'course_id' => $course->id,
            'operation' => $this->operation,
            'status' => 'processing',
            'stage' => 'preparing',
            'attempt' => $this->attempts(),
            'started_at' => Carbon::now(),
        ]);
        $course->update(['moodle_sync_status' => 'processing', 'moodle_sync_error' => null]);

        try {
            if ($course->required_moodle_usernames === null) {
                $course->required_moodle_usernames = array_values($course->webPlatform->required_moodle_usernames ?? []);
            }
            if ($course->required_moodle_roles === null) {
                $course->required_moodle_roles = $course->webPlatform->required_moodle_roles ?? [];
            }
            $course->save();

            $modernProvisioning = (int) ($course->moodle_provisioning_version ?? 1) >= 2;
            if ($modernProvisioning || ($course->required_moodle_roles ?? []) !== []) {
                $siteInfo = $client->siteInfo($course->webPlatform);
                $requiredVersion = $modernProvisioning ? '2.3.2' : '2.2.0';
                if (version_compare((string) ($siteInfo['connectorversion'] ?? '0'), $requiredVersion, '<')) {
                    throw new RuntimeException(
                        'Actualiza el plugin local_zonaavz de Moodle a la versión '.$requiredVersion.'.'
                    );
                }
            }

            if ($modernProvisioning && (!$course->beginning || !$course->end)) {
                throw new RuntimeException('El curso debe tener fecha de inicio y fin para sincronizarlo con Moodle.');
            }
            if ($modernProvisioning && $course->moodle_mode === 'automatic' && !$course->moodle_category_id) {
                throw new RuntimeException('El curso no tiene una categoría Moodle de destino.');
            }

            $template = null;
            if ($course->moodle_mode === 'automatic' && !$course->moodle_course_id) {
                $template = MoodleCourseTemplate::where('training_action_id', $course->training_action_id)
                    ->where('web_platform_id', $course->web_platform_id)->first();
                if (!$template) {
                    throw new RuntimeException('La acción formativa no tiene un curso base configurado para esta Moodle.');
                }
            }

            $updateCourseMetadata = !$modernProvisioning || $course->moodle_mode === 'automatic';
            [$studentStart, $studentEnd] = $modernProvisioning
                ? $this->enrolmentDates($course, 'student')
                : [null, null];
            [$teacherStart, $teacherEnd] = $modernProvisioning
                ? $this->enrolmentDates($course, 'editingteacher')
                : [null, null];

            $run->update(['stage' => 'provisioning']);
            $result = $client->provision($course->webPlatform, [
                'provisioning_version' => (int) ($course->moodle_provisioning_version ?? 1),
                'zonaavz_course_id' => $course->id,
                'existing_course_id' => $course->moodle_course_id,
                'source_course_id' => $template?->moodle_course_id,
                'update_course_metadata' => $updateCourseMetadata,
                'category_id' => $modernProvisioning && $course->moodle_mode === 'automatic'
                    ? (int) $course->moodle_category_id
                    : null,
                'fullname' => $modernProvisioning ? $this->fullname($course) : $course->name,
                'shortname' => $this->shortname($course),
                'idnumber' => 'zonaavz-course-'.$course->id,
                'startdate' => $course->beginning
                    ? Carbon::parse($course->beginning, $this->moodleTimezone())->startOfDay()->timestamp
                    : 0,
                'enddate' => $course->end
                    ? Carbon::parse($course->end, $this->moodleTimezone())->setTime(23, 59)->timestamp
                    : 0,
                'teacher' => $this->userPayload(
                    $course->teacher,
                    'editingteacher',
                    null,
                    $teacherStart,
                    $teacherEnd
                ),
                'students' => $course->registrations->map(fn ($registration) =>
                    $this->userPayload(
                        $registration->student,
                        'student',
                        $registration->status,
                        $studentStart,
                        $studentEnd
                    )
                )->filter()->values()->all(),
                'required_users' => collect($course->required_moodle_usernames ?? [])
                    ->map(function ($username) use ($course, $modernProvisioning) {
                        $role = $course->required_moodle_roles[$username] ?? null;
                        [$start, $end] = $modernProvisioning
                            ? $this->enrolmentDates($course, (string) $role)
                            : [null, null];

                        return [
                            'username' => $username,
                            'role' => $role,
                            'enrolstartdate' => $start,
                            'enrolenddate' => $end,
                        ];
                    })
                    ->values()
                    ->all(),
            ]);

            $course->update([
                'moodle_course_id' => $result['courseid'],
                'moodle_shortname' => $result['shortname'],
                'moodle_sync_status' => 'synced',
                'moodle_sync_error' => null,
                'moodle_synced_at' => Carbon::now(),
            ]);
            $run->update(['status' => 'synced', 'stage' => 'completed', 'finished_at' => Carbon::now()]);
        } catch (\Throwable $e) {
            $course->update(['moodle_sync_status' => 'error', 'moodle_sync_error' => $e->getMessage()]);
            $run->update(['status' => 'error', 'error_message' => $e->getMessage(), 'finished_at' => Carbon::now()]);
            throw $e;
        }
    }

    private function shortname(Course $course): string
    {
        $code = trim((string) ($course->trainingAction?->formative_action ?? ''));
        return trim($code.'/'.$course->group, '/');
    }

    private function fullname(Course $course): string
    {
        $name = trim((string) ($course->trainingAction?->name ?: $course->name));
        $beginning = Carbon::parse($course->beginning)->format('d/m/Y');
        $end = Carbon::parse($course->end)->format('d/m/Y');

        return $this->shortname($course).' - '.$name.' ('.$beginning.' - '.$end.')';
    }

    private function enrolmentDates(Course $course, string $role): array
    {
        $start = Carbon::parse($course->beginning, $this->moodleTimezone())->startOfDay();
        $end = Carbon::parse($course->end, $this->moodleTimezone())->setTime(23, 59);

        if ($role === 'editingteacher') {
            $end = $end->addMonthNoOverflow();
        } elseif ($role === 'inspectortotal') {
            $end = $end->addYearsNoOverflow(4);
        }

        return [$start->timestamp, $end->timestamp];
    }

    private function moodleTimezone(): string
    {
        return (string) config('moodle.timezone', 'Europe/Madrid');
    }

    private function userPayload(
        $user,
        string $role,
        $status = null,
        ?int $enrolStartDate = null,
        ?int $enrolEndDate = null
    ): ?array
    {
        if (!$user || !trim((string) $user->user)) {
            return null;
        }
        return [
            'username' => trim((string) $user->user),
            'firstname' => trim((string) $user->name),
            'lastname' => trim((string) $user->surname) ?: '-',
            'email' => trim((string) $user->email),
            'password' => (string) ($user->password ?: ''),
            'role' => $role,
            'suspended' => $status !== null && !in_array(strtolower((string) $status), ['', 'active', 'alta', '1'], true),
            'enrolstartdate' => $enrolStartDate,
            'enrolenddate' => $enrolEndDate,
        ];
    }
}
