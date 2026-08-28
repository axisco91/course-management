<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $companyTemplates = [
            'training_compliance_notice' => [
                'subject' => 'CUMPLIMIENTO DE FORMACIÓN - {{student_name}}',
                'body_html' => '<p>Hola, {{company_tutor_name}}:</p><p>Me pongo en contacto contigo en relación con la formación asociada al contrato de formación en alternancia del trabajador <strong>{{student_name}}</strong>.</p><p>Tras realizar las correspondientes revisiones de seguimiento, hemos podido comprobar que {{student_name}} está realizando la formación de manera adecuada y manteniendo un buen ritmo de progreso en la plataforma.</p><p>Queremos destacar especialmente su implicación y compromiso con la parte formativa del contrato. Está accediendo regularmente a la plataforma, avanzando de forma constante en los contenidos y mostrando una actitud responsable y preocupada por cumplir correctamente con las obligaciones formativas establecidas.</p><p>El seguimiento que estamos realizando refleja una evolución positiva y un buen aprovechamiento de la formación, manteniendo un ritmo adecuado para alcanzar los objetivos establecidos dentro de los plazos correspondientes.</p><p>Por nuestra parte, valoramos muy positivamente el trabajo que está realizando y su compromiso con la formación. Es importante que continúe manteniendo esta constancia y dedicación durante el resto del periodo formativo.</p><p>Agradecemos también vuestra colaboración y el seguimiento que se está realizando desde la empresa para facilitar que la formación se desarrolle correctamente.</p><p>Quedo a tu disposición para cualquier aclaración o consulta adicional.</p><p>Saludos cordiales,</p>',
            ],
            'training_noncompliance_notice' => [
                'subject' => 'AVISO POR INCUMPLIMIENTO DE FORMACIÓN - {{student_name}}',
                'body_html' => '<p>Hola, {{company_tutor_name}}:</p><p>Me pongo en contacto contigo en relación con la formación asociada al contrato de formación del trabajador <strong>{{student_name}}</strong>.</p><p>Tras realizar varias revisiones de seguimiento, hemos comprobado que la formación no se está realizando de manera adecuada. Aunque se le han enviado distintos avisos y recordatorios para que acceda a la plataforma y realice el contenido formativo, la situación continúa sin regularizarse.</p><p>Queremos recordarte la importancia que tiene el correcto seguimiento de la formación dentro de este tipo de contratos. La formación es un requisito obligatorio y esencial del contrato de formación en alternancia, por lo que debe realizarse de manera continuada y dentro de los plazos establecidos. Tanto la actividad laboral como la formativa forman parte del propio contrato y están sujetas a posibles revisiones o inspecciones por parte de la Administración.</p><p>Por ello, agradeceríamos vuestra colaboración para trasladar al trabajador la necesidad de regularizar esta situación cuanto antes y mantener un seguimiento diario y adecuado de la formación.</p><p>Quedo a tu disposición para cualquier aclaración o consulta adicional.</p><p>Saludos cordiales.</p>',
            ],
        ];

        foreach (['email_templates', 'web_platform_email_templates'] as $table) {
            foreach ($companyTemplates as $mailType => $values) {
                DB::table($table)->where('mail_type', $mailType)->update($values);
            }
        }
    }

    public function down(): void
    {
        // Los textos personalizados anteriores no se pueden reconstruir de forma segura.
    }
};
