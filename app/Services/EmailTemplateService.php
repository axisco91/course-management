<?php

namespace App\Services;

use App\Mail\TemplateMessageMail;
use App\Models\EmailTemplate;
use InvalidArgumentException;

class EmailTemplateService
{
    public const TYPES = [
        'greeting',
        'quarter',
        'half',
        'three_quarters',
        'final',
        'course_end_reminder',
    ];

    public function catalog(?int $mainCompanyId): array
    {
        $saved = $mainCompanyId
            ? EmailTemplate::where('main_company_id', $mainCompanyId)->get()->keyBy('mail_type')
            : collect();

        return collect($this->defaults())->map(function (array $definition, string $type) use ($saved) {
            $override = $saved->get($type);

            return [
                'mail_type' => $type,
                'name' => $definition['name'],
                'subject' => $override?->subject ?? $definition['subject'],
                'body_html' => $override?->body_html ?? $definition['body_html'],
                'variables' => $definition['variables'],
                'customized' => (bool) $override,
            ];
        })->values()->all();
    }

    public function save(int $mainCompanyId, string $type, string $subject, string $bodyHtml): EmailTemplate
    {
        $this->definition($type);

        return EmailTemplate::updateOrCreate(
            ['main_company_id' => $mainCompanyId, 'mail_type' => $type],
            [
                'subject' => trim(strip_tags($subject)),
                'body_html' => $this->sanitizeHtml($bodyHtml),
            ]
        );
    }

    public function reset(int $mainCompanyId, string $type): void
    {
        $this->definition($type);
        EmailTemplate::where('main_company_id', $mainCompanyId)->where('mail_type', $type)->delete();
    }

    public function makeMailable(
        ?int $mainCompanyId,
        string $type,
        array $variables,
        string $senderName,
        ?string $subjectOverride = null,
        ?string $bodyHtmlOverride = null
    ): TemplateMessageMail {
        $definition = $this->definition($type);
        $template = $mainCompanyId
            ? EmailTemplate::where('main_company_id', $mainCompanyId)->where('mail_type', $type)->first()
            : null;

        $subjectTemplate = $subjectOverride !== null
            ? trim(strip_tags($subjectOverride))
            : ($template?->subject ?? $definition['subject']);
        $bodyTemplate = $bodyHtmlOverride !== null
            ? $this->sanitizeHtml($bodyHtmlOverride)
            : ($template?->body_html ?? $definition['body_html']);

        $renderedBody = $this->replaceVariables($bodyTemplate, $variables, true);
        if (($variables['milestone_timing'] ?? 'hoy') !== 'hoy') {
            $renderedBody = preg_replace('/\bhoy\b/ui', (string) $variables['milestone_timing'], $renderedBody)
                ?? $renderedBody;
        }

        return new TemplateMessageMail(
            $this->replaceVariables($subjectTemplate, $variables, false),
            $renderedBody,
            $senderName
        );
    }

    private function replaceVariables(string $template, array $variables, bool $escape): string
    {
        return preg_replace_callback('/\{\{\s*([a-z_]+)\s*\}\}/i', function (array $matches) use ($variables, $escape) {
            $value = (string) ($variables[$matches[1]] ?? '');

            return $escape ? e($value) : $value;
        }, $template) ?? $template;
    }

    private function sanitizeHtml(string $html): string
    {
        $html = preg_replace('#<(script|style|iframe|object|embed)[^>]*>.*?</\1>#is', '', $html) ?? $html;
        $html = strip_tags($html, '<p><br><strong><b><em><i><u><ul><ol><li><div><span>');

        return trim((string) preg_replace('/<([a-z][a-z0-9]*)(?:\s[^>]*)?>/i', '<$1>', $html));
    }

    private function definition(string $type): array
    {
        $defaults = $this->defaults();
        if (!isset($defaults[$type])) {
            throw new InvalidArgumentException('Tipo de plantilla no válido.');
        }

        return $defaults[$type];
    }

    private function defaults(): array
    {
        $common = ['student_name', 'formative_action', 'tutor_name', 'subject_code', 'course_end_date'];

        return [
            'greeting' => [
                'name' => 'Bienvenida',
                'subject' => 'BIENVENIDA DOCENTE AF {{subject_code}}',
                'variables' => array_merge($common, ['total_hours', 'course_start_date']),
                'body_html' => '<p>Estimado/a {{student_name}},</p><p>¡Bienvenido/a a la acción formativa <strong>{{formative_action}}</strong>!</p><p>Mi nombre es <strong>{{tutor_name}}</strong> y seré su tutor/a durante el desarrollo de esta acción en la modalidad de teleformación. Estaré a su disposición para que reciba el mayor apoyo y seguimiento posible.</p><p>Este curso tiene una duración de <strong>{{total_hours}} horas</strong>, con fecha de inicio de <strong>{{course_start_date}}</strong> y fecha de fin <strong>{{course_end_date}}</strong>. Estas fechas no serán prorrogables bajo ningún concepto.</p><p>Para una mejor asimilación de conocimientos y para su comodidad, le aconsejo realizar el curso de manera gradual, conectándose desde el primer día y con regularidad en el horario establecido en su contrato de formación.</p><p>Recuerde que debe conectarse el 100 % de las horas, visualizar el 100 % del temario y realizar el 100 % de las pruebas de evaluación alcanzando una puntuación mínima de 5. También aconsejamos aprovechar las actividades propuestas e interactuar en el foro.</p><p>Para cualquier duda puede contactar conmigo mediante la plataforma o los foros. El departamento de tutorías también está disponible en el teléfono 910 600 410.</p><p>Mucha suerte y ánimo con esta acción formativa. Espero que le guste.</p><p>{{tutor_name}}</p>',
            ],
            'quarter' => [
                'name' => 'Seguimiento 25 %',
                'subject' => 'SEGUIMIENTO 25% AF {{subject_code}}',
                'variables' => array_merge($common, ['milestone_timing']),
                'body_html' => '<p>Hola {{student_name}},</p><p>Te informamos de que {{milestone_timing}} el curso <strong>{{formative_action}}</strong> alcanza el 25 % de su duración prevista. Este es un buen momento para revisar tu progreso y comprobar que llevas al día las unidades y evaluaciones programadas.</p><p>Te recomendamos acceder a la plataforma y verificar si tienes algún módulo o evaluación pendiente, para que puedas continuar el curso con normalidad y aprovechar al máximo la formación.</p><p>Si tienes cualquier duda o necesitas ayuda, no dudes en ponerte en contacto conmigo.</p><p>Un saludo.<br>{{tutor_name}}</p>',
            ],
            'half' => [
                'name' => 'Seguimiento 50 %',
                'subject' => 'SEGUIMIENTO 50% AF {{subject_code}}',
                'variables' => array_merge($common, ['milestone_timing']),
                'body_html' => '<p>Estimado/a {{student_name}},</p><p>Tu curso <strong>{{formative_action}}</strong> alcanza {{milestone_timing}} el 50 % de su duración total.</p><p>Recuerda que para conseguir el apto debes visualizar la totalidad de las unidades, realizar todas las evaluaciones y obtener al menos una nota mínima de 5 en cada una de ellas.</p><p>Como sabes, estoy a tu disposición ante cualquier consulta.</p><p>Saludos.<br>{{tutor_name}}</p>',
            ],
            'three_quarters' => [
                'name' => 'Seguimiento 75 %',
                'subject' => 'SEGUIMIENTO 75% AF {{subject_code}}',
                'variables' => array_merge($common, ['milestone_timing', 'remaining_hours', 'remaining_units', 'remaining_activities', 'progress_message']),
                'body_html' => '<p>Estimado/a {{student_name}},</p><p>Tu curso <strong>{{formative_action}}</strong> alcanza {{milestone_timing}} el 75 % de su duración total.</p><p>{{progress_message}}</p><p>Como sabes, estoy a tu disposición ante cualquier consulta.</p><p>Saludos.<br>{{tutor_name}}</p>',
            ],
            'final' => [
                'name' => 'Fin de curso',
                'subject' => 'FIN AF {{subject_code}}',
                'variables' => array_merge($common, ['final_result', 'final_intro', 'final_detail']),
                'body_html' => '<p>Hola {{student_name}},</p><p>{{final_intro}}</p><p>{{final_detail}}</p><p>Si tienes cualquier duda o necesitas alguna aclaración, puedes ponerte en contacto conmigo.</p><p>Un saludo.<br>{{tutor_name}}</p>',
            ],
            'course_end_reminder' => [
                'name' => 'Último día',
                'subject' => 'ULTIMO DIA CURSO {{subject_code}}',
                'variables' => $common,
                'body_html' => '<p>Estimado/a {{student_name}},</p><p>Te recuerdo que hoy es el último día de la acción formativa <strong>{{formative_action}}</strong>.</p><p>La plataforma permanecerá disponible hasta las 23:59 horas de hoy. Si aún tienes alguna unidad por visualizar, evaluaciones por realizar o la evaluación final pendiente, es importante que lo completes antes de esa hora.</p><p>Una vez finalizado el plazo, el acceso al curso quedará cerrado y no será posible realizar ninguna actividad adicional.</p><p>Te recomiendo revisar tu progreso para asegurarte de que has completado todos los requisitos necesarios para superar la formación.</p><p>Para cualquier consulta de última hora, puedes contactar conmigo a través de la plataforma.</p><p>Recibe un cordial saludo.<br>{{tutor_name}}</p>',
            ],
        ];
    }
}
