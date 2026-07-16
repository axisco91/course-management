<?php

namespace Tests\Unit;

use App\Services\EmailTemplateService;
use Tests\TestCase;

class EmailTemplateServiceTest extends TestCase
{
    public function testItExposesAllDefaultTemplates(): void
    {
        $templates = app(EmailTemplateService::class)->catalog(null);

        $this->assertCount(6, $templates);
        $this->assertSame([
            'greeting',
            'quarter',
            'half',
            'three_quarters',
            'final',
            'course_end_reminder',
        ], array_column($templates, 'mail_type'));
    }

    public function testItRendersAllowedVariablesAndEscapesTheirValues(): void
    {
        $mailable = app(EmailTemplateService::class)->makeMailable(null, 'greeting', [
            'student_name' => '<Alumno>',
            'formative_action' => 'Curso de prueba',
            'tutor_name' => 'Tutor',
            'subject_code' => '738/0001',
            'total_hours' => '60',
            'course_start_date' => '15-07-2026',
            'course_end_date' => '30-09-2026',
        ], 'Tutor');

        $html = $mailable->render();

        $this->assertSame('BIENVENIDA DOCENTE AF 738/0001', $mailable->subject);
        $this->assertStringContainsString('&lt;Alumno&gt;', $html);
        $this->assertStringNotContainsString('{{student_name}}', $html);
    }

    public function testItAllowsASafeReviewedBodyOnlyForTheRequestedDelivery(): void
    {
        $mailable = app(EmailTemplateService::class)->makeMailable(
            null,
            'three_quarters',
            ['student_name' => 'María', 'subject_code' => '738/0001'],
            'Tutor',
            'REVISIÓN {{subject_code}}',
            '<p>Hola {{student_name}}</p><script>alert(1)</script>'
        );

        $html = $mailable->render();

        $this->assertSame('REVISIÓN 738/0001', $mailable->subject);
        $this->assertStringContainsString('Hola María', $html);
        $this->assertStringNotContainsString('script', $html);
        $this->assertStringNotContainsString('alert(1)', $html);
    }
}
