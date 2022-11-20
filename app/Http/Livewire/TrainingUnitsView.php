<?php

namespace App\Http\Livewire;

use App\Models\ActionType;
use App\Models\CourseOrigin;
use App\Models\Modality;
use App\Models\ProfessionalArea;
use App\Models\ProfessionalFamily;
use App\Models\Provider;
use App\Models\TrainingActionGroup;
use App\Models\TrainingActionLevel;
use App\Models\TrainingUnit;
use App\Models\Tutoring;
use App\Models\WebPlatform;
use Livewire\Component;

class TrainingUnitsView extends Component
{
    public $selected_id, $name, $teacher_id, $action_type_id, $professional_family_id, $professional_area_id,
        $modality_id, $training_action_level_id, $training_action_group_id, $tutoring_id, $course_origin_id, $active = 1,
        $in_catalog = 1, $face_to_face_hours, $teletraining_hours, $total_hours, $price, $objectives, $content, $user,
        $web_platform_id, $observations, $number_activities, $number_units, $provider_id, $password, $formative_unit;
    public $action_types, $professional_families, $professional_areas, $modalities, $training_action_levels, $training_action_groups,
        $tutorings, $web_platforms, $providers, $course_origins;

    public function render()
    {
        return view('livewire.training-actions.view');
    }

    public function mount($id){
        $this->action_types = ActionType::all();
        $this->professional_families = ProfessionalFamily::all();
        $this->professional_areas = ProfessionalArea::all();
        $this->modalities = Modality::all();
        $this->training_action_levels = TrainingActionLevel::all();
        $this->training_action_groups = TrainingActionGroup::all();
        $this->tutorings = Tutoring::all();
        $this->web_platforms = WebPlatform::all();
        $this->course_origins = CourseOrigin::all();
        $this->providers = Provider::select('providers.*')
            ->join('companies', 'companies.id', '=', 'providers.company_id')
            ->where('companies.active', 1)->get();

        $record = TrainingAction::findOrFail($id);

        $this->selected_id = $id;
        $this->formative_unit = $record-> formative_unit;
        $this->name = $record-> name;
        $this->action_type_id = $record-> action_type_id;
        $this->professional_family_id = $record-> professional_family_id;
        $this->professional_area_id = $record-> professional_area_id;
        $this->modality_id = $record-> modality_id;
        $this->training_action_level_id = $record-> training_action_level_id;
        $this->training_action_group_id = $record-> training_action_group_id;
        $this->tutoring_id = $record-> tutoring_id;
        $this->course_origin_id = $record-> course_origin_id;
        $this->active = $record-> active;
        $this->in_catalog = $record-> in_catalog;
        $this->face_to_face_hours = $record-> face_to_face_hours;
        $this->teletraining_hours = $record-> teletraining_hours;
        $this->total_hours = $record-> total_hours;
        $this->price = $record-> price;
        $this->objectives = $record-> objectives;
        $this->content = $record-> content;
        $this->user = $record-> user;
        $this->password = $record-> password;
        $this->web_platform_id = $record-> web_platform_id;
        $this->observations = $record-> observations;
        $this->number_activities = $record-> number_activities;
        $this->number_units = $record-> number_units;
        $this->provider_id = $record-> provider_id;
    }
}
