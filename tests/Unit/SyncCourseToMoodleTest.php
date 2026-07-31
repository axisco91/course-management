<?php

namespace Tests\Unit;

use App\Jobs\SyncCourseToMoodle;
use App\Models\Course;
use App\Models\TrainingAction;
use Illuminate\Support\Carbon;
use ReflectionMethod;
use Tests\TestCase;

class SyncCourseToMoodleTest extends TestCase
{
    public function testItBuildsTheNormalizedMoodleNames(): void
    {
        $course = $this->course('2026-07-16', '2026-07-26');

        $this->assertSame(
            '120/0017 - Básico PRL (16/07/2026 - 26/07/2026)',
            $this->invoke('fullname', $course)
        );
        $this->assertSame('120/0017', $this->invoke('shortname', $course));
    }

    public function testStudentAndManagerUseTheCourseDates(): void
    {
        $course = $this->course('2026-07-16', '2026-07-26');

        $student = $this->invoke('enrolmentDates', $course, 'student');
        $manager = $this->invoke('enrolmentDates', $course, 'manager');

        $this->assertSame($student, $manager);
        $this->assertSame('2026-07-16 00:00:00', $this->timestamp($student[0]));
        $this->assertSame('2026-07-26 23:59:00', $this->timestamp($student[1]));
    }

    public function testTeacherGetsOneNaturalMonthWithoutOverflow(): void
    {
        $course = $this->course('2027-01-01', '2027-01-31');

        [, $end] = $this->invoke('enrolmentDates', $course, 'editingteacher');

        $this->assertSame('2027-02-28 23:59:00', $this->timestamp($end));
    }

    public function testInspectorGetsFourNaturalYearsWithoutOverflow(): void
    {
        $course = $this->course('2096-02-01', '2096-02-29');

        [, $end] = $this->invoke('enrolmentDates', $course, 'inspectortotal');

        $this->assertSame('2100-02-28 23:59:00', $this->timestamp($end));
    }

    private function course(string $beginning, string $end): Course
    {
        $course = new Course([
            'name' => 'Nombre interno',
            'group' => '0017',
            'beginning' => $beginning,
            'end' => $end,
        ]);
        $course->setRelation('trainingAction', new TrainingAction([
            'formative_action' => '120',
            'name' => 'Básico PRL',
        ]));

        return $course;
    }

    private function invoke(string $method, ...$arguments)
    {
        $reflection = new ReflectionMethod(SyncCourseToMoodle::class, $method);
        $reflection->setAccessible(true);

        return $reflection->invoke(new SyncCourseToMoodle(1), ...$arguments);
    }

    private function timestamp(int $timestamp): string
    {
        return Carbon::createFromTimestamp($timestamp, config('moodle.timezone'))->format('Y-m-d H:i:s');
    }
}
