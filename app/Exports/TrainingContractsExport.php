<?php

namespace App\Exports;

use App\Models\TrainingAction;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TrainingContractsExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function __construct($formative_actions, $name, $professional_family_id,  $professional_area_id, $modality_id, $provider_id, $active){
        $this->formative_actions = $formative_actions;
        $this->name = $name;
        $this->professional_family_id = $professional_family_id;
        $this->professional_area_id = $professional_area_id;
        $this->modality_id = $modality_id;
        $this->provider_id = $provider_id;
        $this->active = $active;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $training_actions = TrainingAction::select('formative_action', 'training_actions.name', 'action_types.name as action_name',
        'professional_families.name as family_name', 'professional_areas.name as area_name', 'modalities.name as modality_name',
        'training_action_levels.name as level_name', 'training_action_groups.name as group_name', 'tutorings.name as tutoring_name',
            \DB::raw('(CASE
                        WHEN course_z = "0" THEN "No"
                        WHEN course_z = "1" THEN "Si"
                        END) AS course_z'),
            \DB::raw('(CASE
                        WHEN course_avz = "0" THEN "No"
                        WHEN course_avz = "1" THEN "Si"
                        END) AS course_avz'),
            \DB::raw('(CASE
                        WHEN in_catalog = "0" THEN "No"
                        WHEN in_catalog = "1" THEN "Si"
                        END) AS in_catalog'), 'face_to_face_hours', 'teletraining_hours', 'total_hours',
        'price', 'objectives', 'content', 'user', 'password', 'web_platforms.name as web_name', 'observations',
        'number_activities', 'number_units', 'providers.name as provider_name',
            \DB::raw('(CASE
                        WHEN training_actions.active = "0" THEN "Inactivo"
                        WHEN training_actions.active = "1" THEN "Activo"
                        END) AS active'))
        ->leftjoin('action_types', 'action_types.id', '=', 'training_actions.action_type_id')
        ->leftjoin('professional_families', 'professional_families.id', '=', 'training_actions.professional_family_id')
        ->leftjoin('professional_areas', 'professional_areas.id', '=', 'training_actions.professional_area_id')
        ->leftjoin('modalities', 'modalities.id', '=', 'training_actions.modality_id')
        ->leftjoin('training_action_levels', 'training_action_levels.id', '=', 'training_actions.training_action_level_id')
        ->leftjoin('training_action_groups', 'training_action_groups.id', '=', 'training_actions.training_action_group_id')
            ->leftjoin('tutorings', 'tutorings.id', '=', 'training_actions.tutoring_id')
        ->leftjoin('web_platforms', 'web_platforms.id', '=', 'training_actions.web_platform_id')
        ->leftjoin('providers', 'providers.id', '=', 'training_actions.provider_id');

        if ($this->professional_family_id){
            $training_actions = $training_actions->Where('training_actions.professional_family_id', $this->professional_family_id);
        }
        if ($this->professional_area_id){
            $training_actions = $training_actions->Where('training_actions.professional_area_id', $this->professional_area_id);
        }
        if ($this->modality_id){
            $training_actions = $training_actions->Where('training_actions.modality_id', $this->modality_id);
        }
        if ($this->provider_id){
            $training_actions = $training_actions->Where('training_actions.provider_id', $this->provider_id);
        }

        $training_actions = $training_actions->get();

        return collect($training_actions);
    }

    public function headings(): array
    {
        return [
            'Acción Formativa',
            'Nombre',
            'Tipo Acción',
            'Familia Professional',
            'Área Professional',
            'Modalidad',
            'Nivel',
            'Grupo',
            'Tutorización',
            'Curso z',
            'Curso Avz',
            'En Catalogo',
            'Horas Presenciales',
            'Horas Teleformación',
            'Horas totales',
            'Precio',
            'Objetivos',
            'Contenido',
            'Usuario',
            'Contraseña',
            'Plataforma',
            'Observaciones',
            'Número Actividades',
            'Número Unidades',
            'Proveedor',
            'Estado'
        ];
    }
}
