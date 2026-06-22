<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class CourseResource extends JsonResource
{
    private function displayName(): string
    {
        $trainingAction = $this->resource->relationLoaded('trainingAction') ? $this->trainingAction : null;
        $prefix = trim(implode(' / ', array_filter([
            $trainingAction?->formative_action,
            $this->group,
        ])));
        $name = $trainingAction?->name ?? $this->name ?? '';

        return trim($prefix ? $prefix.' '.$name : $name);
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,

            // Nombre guardado y nombre visible compuesto para tablas.
            'name' => $this->name,
            'display_name' => $this->displayName(),
            'group' => $this->group,

            // Relaciones
            'course_type' => $this->whenLoaded('courseType'),
            'teacher' => $this->whenLoaded('teacher'),
            'course_status' => $this->whenLoaded('courseStatus'),
            'training_action' => $this->whenLoaded('trainingAction'),

            // Fechas
            'beginning' => $this->beginning
                ? Carbon::parse($this->beginning)->format('d-m-Y')
                : null,

            'end' => $this->end
                ? Carbon::parse($this->end)->format('d-m-Y')
                : null,

            // Otros campos directos
            'total_hours' => $this->total_hours,
            'formation_center' => $this->formation_center,
            'delivery_center' => $this->delivery_center,

            // Contadores
            'registrations_count' => (int) $this->registrations_count,
        ];
    }
}
