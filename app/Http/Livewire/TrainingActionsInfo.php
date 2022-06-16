<?php

namespace App\Http\Livewire;

use App\Models\ActionType;
use App\Models\Modality;
use App\Models\ProfessionalArea;
use App\Models\ProfessionalFamily;
use App\Models\Provider;
use App\Models\Teacher;
use App\Models\TrainingActionGroup;
use App\Models\TrainingActionLevel;
use App\Models\Tutoring;
use App\Models\WebPlatform;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TrainingAction;

class TrainingActionsInfo extends Component
{
    protected $listeners = ['getTrainingActionInfo'];
    public $selected_id, $name, $teacher_id, $action_type_id, $professional_family_id, $professional_area_id,
        $modality_id, $training_action_level_id, $training_action_group_id, $tutoring_id, $course_z, $course_avz, $active = 1,
        $in_catalog = 1, $face_to_face_hours, $teletraining_hours, $total_hours, $price, $objectives, $content, $user,
        $web_platform_id, $observations, $number_activities, $number_units, $provider_id, $password, $formative_action;
    public $action_types, $professional_families, $professional_areas, $modalities, $training_action_levels, $training_action_groups,
        $tutorings, $web_platforms, $providers, $route;

    public function render()
    {
        if ($this->selected_id){
            $training_Action = TrainingAction::find($this->selected_id);
            $this->formative_action = $training_Action-> formative_action;
            $this->name = $training_Action->name;
            $this->action_type_id = $training_Action-> action_type_id;
            $this->professional_family_id = $training_Action-> professional_family_id;
            $this->professional_area_id = $training_Action-> professional_area_id;
            $this->modality_id = $training_Action-> modality_id;
            $this->training_action_level_id = $training_Action-> training_action_level_id;
            $this->training_action_group_id = $training_Action-> training_action_group_id;
            $this->tutoring_id = $training_Action-> tutoring_id;
            $this->course_z = $training_Action-> course_z;
            $this->course_avz = $training_Action-> course_avz;
            $this->active = $training_Action-> active;
            $this->in_catalog = $training_Action-> in_catalog;
            $this->face_to_face_hours = $training_Action-> face_to_face_hours;
            $this->teletraining_hours = $training_Action-> teletraining_hours;
            $this->total_hours = $training_Action-> total_hours;
            $this->price = $training_Action-> price;
            $this->objectives = $training_Action-> objectives;
            $this->content = $training_Action-> content;
            $this->user = $training_Action-> user;
            $this->password = $training_Action-> password;
            $this->web_platform_id = $training_Action-> web_platform_id;
            $this->observations = $training_Action-> observations;
            $this->number_activities = $training_Action-> number_activities;
            $this->number_units = $training_Action-> number_units;
            $this->provider_id = $training_Action-> provider_id;
        }

        return view('livewire.training-actions.info');
    }

    public function mount(){
        $this->action_types = ActionType::all();
        $this->professional_families = ProfessionalFamily::all();
        $this->professional_areas = ProfessionalArea::all();
        $this->modalities = Modality::all();
        $this->training_action_levels = TrainingActionLevel::all();
        $this->training_action_groups = TrainingActionGroup::all();
        $this->tutorings = Tutoring::all();
        $this->web_platforms = WebPlatform::all();
        $this->providers = Provider::select('providers.*')
            ->join('companies', 'companies.id', '=', 'providers.company_id')
            ->where('companies.active', 1)->get();
    }

    public function getTrainingActionInfo($id){
        $this->selected_id = $id;
    }
}
