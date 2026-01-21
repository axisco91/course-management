<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfitabilityResource extends JsonResource
{
    public function toArray($request)
    {
        $course = $this->course;
        $ta = $course?->trainingAction;

        $courseLabel = trim(sprintf(
            '%s / %s %s',
            $ta?->formative_action ?? '',
            $course?->group ?? '',
            $ta?->name ?? ''
        ));

        $year = $course?->beginning ? (int) date('Y', strtotime($course->beginning)) : null;

        return [
            'id' => $this->id,

            // ✅ cabecera (tabla principal)
            'course_label'  => $courseLabel,
            'company_name'  => $this->company?->name ?? null,
            'year'          => $year,

            // por si lo necesitas
            'course_id'   => $this->course_id,
            'company_id'  => $this->company_id,
            'student_id'  => $this->student_id,

            // ✅ desplegable (detalle alumno)
            'student_name'   => $this->student?->name ?? null,
            'student_surname'=> $this->student?->surname ?? null,
            'student_label'  => trim(($this->student?->name ?? '').' '.($this->student?->surname ?? '')),

            // ✅ importes (ajusta nombres a tus columnas reales)
            'price'                  => (float) ($this->price ?? 0),
            'license'                => (float) ($this->license ?? 0),
            'teacher'                => (float) ($this->teacher ?? 0),
            'management'             => (float) ($this->management ?? 0),
            'nebrija_title'           => (float) ($this->nebrija_title ?? 0),
            'discount'               => (float) ($this->discount ?? 0),
            'collaborator_commission'=> (float) ($this->collaborator_commission ?? 0),
            'advisor_commission'     => (float) ($this->advisor_commission ?? 0),
            'total'                  => (float) ($this->total ?? 0),
            'benefit'                => (float) ($this->benefit ?? 0),
        ];
    }
}
