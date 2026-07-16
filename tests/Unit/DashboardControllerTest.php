<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\DashboardController;
use App\Models\Company;
use App\Models\Course;
use App\Models\Registration;
use App\Models\Student;
use App\Models\Tracing;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class DashboardControllerTest extends TestCase
{
    public function testValidOverlappingContractTakesPriorityOverExcludedDuplicate(): void
    {
        $tracing = $this->makeTracing();
        $contracts = [
            '10|20' => [
                ['start' => '2026-07-01', 'end' => '2026-09-01', 'status' => 'NO FORMALIZADO'],
                ['start' => '2026-07-01', 'end' => '2026-09-01', 'status' => 'IMPARTICIÓN'],
            ],
        ];

        $this->assertFalse($this->shouldExclude($tracing, $contracts));
    }

    public function testTracingIsExcludedWhenAllOverlappingContractsAreExcluded(): void
    {
        $tracing = $this->makeTracing();
        $contracts = [
            '10|20' => [
                ['start' => '2026-07-01', 'end' => '2026-09-01', 'status' => 'BAJA'],
                ['start' => '2026-07-01', 'end' => '2026-09-01', 'status' => 'NO FORMALIZADO'],
            ],
        ];

        $this->assertTrue($this->shouldExclude($tracing, $contracts));
    }

    public function testExistingTracingIsNotRecreatedAsFallbackEvent(): void
    {
        $course = $this->makeCourse();
        $registration = $this->makeRegistration();
        $registration->tracing_id = 1904;
        $registration->setRelation('tracing', new Tracing(['id' => 1904]));
        $course->setRelation('registrations', new Collection([$registration]));

        $events = $this->buildFallbackEvents(new Collection([$course]));

        $this->assertSame([], $events);
    }

    public function testRegistrationWithoutTracingKeepsFallbackEvent(): void
    {
        $course = $this->makeCourse();
        $registration = $this->makeRegistration();
        $registration->tracing_id = null;
        $registration->setRelation('tracing', null);
        $course->setRelation('registrations', new Collection([$registration]));

        $events = $this->buildFallbackEvents(new Collection([$course]));

        $this->assertCount(1, $events);
        $this->assertNull($events[0]['meta']['tracingId']);
        $this->assertTrue($events[0]['meta']['tracing']['is_fallback']);
    }

    private function makeTracing(): Tracing
    {
        $tracing = new Tracing([
            'id' => 1904,
            'student_id' => 10,
            'company_id' => 20,
            'course_id' => 30,
            'training_contract_element_id' => null,
        ]);
        $tracing->setRelation('course', new Course([
            'id' => 30,
            'beginning' => '2026-07-01',
            'end' => '2026-07-24',
        ]));

        return $tracing;
    }

    private function makeCourse(): Course
    {
        $course = new Course([
            'id' => 30,
            'name' => 'Curso de prueba',
            'beginning' => '2026-07-01',
            'end' => '2026-07-24',
            'course_status_id' => 2,
        ]);
        $course->setRelation('courseType', null);

        return $course;
    }

    private function makeRegistration(): Registration
    {
        $registration = new Registration([
            'student_id' => 10,
            'company_id' => 20,
            'course_id' => 30,
        ]);
        $registration->setRelation('student', new Student([
            'id' => 10,
            'name' => 'FERNANDO',
            'surname' => 'ROMERO RUZ',
        ]));
        $registration->setRelation('company', new Company([
            'id' => 20,
            'name' => 'Empresa de prueba',
        ]));

        return $registration;
    }

    private function shouldExclude(Tracing $tracing, array $contracts): bool
    {
        $controller = new DashboardController();
        $method = new ReflectionMethod($controller, 'shouldExcludeTracingFromTrainingContractStatus');
        $method->setAccessible(true);

        return $method->invoke($controller, $tracing, $contracts);
    }

    private function buildFallbackEvents(Collection $courses): array
    {
        $controller = new DashboardController();
        $method = new ReflectionMethod($controller, 'buildCourseTracingFallbackEvents');
        $method->setAccessible(true);

        return $method->invoke($controller, $courses, '2026-07-01', '2026-07-01');
    }
}
