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

class TrainingActionsUpdate extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $name, $teacher_id, $action_type_id, $professional_family_id, $professional_area_id,
        $modality_id, $training_action_level_id, $training_action_group_id, $tutoring_id, $course_z, $course_avz, $active = 1,
        $in_catalog = 1, $face_to_face_hours, $teletraining_hours, $total_hours, $price, $objectives, $content, $user,
        $web_platform_id, $observations, $number_activities, $number_units, $provider_id, $password, $formative_action;
    public $action_types, $professional_families, $professional_areas, $modalities, $training_action_levels, $training_action_groups,
        $tutorings, $web_platforms, $providers, $route;

    public function render()
    {
        return view('livewire.training-actions.edit');
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
        $this->providers = Provider::select('providers.*')
            ->join('companies', 'companies.id', '=', 'providers.company_id')
            ->where('companies.inactive', 0)->get();

        $record = TrainingAction::findOrFail($id);

        $this->action($id);
        $this->selected_id = $id;
        $this->name = $record-> name;
        $this->action_type_id = $record-> action_type_id;
        $this->professional_family_id = $record-> professional_family_id;
        $this->professional_area_id = $record-> professional_area_id;
        $this->modality_id = $record-> modality_id;
        $this->training_action_level_id = $record-> training_action_level_id;
        $this->training_action_group_id = $record-> training_action_group_id;
        $this->tutoring_id = $record-> tutoring_id;
        $this->course_z = $record-> course_z;
        $this->course_avz = $record-> course_avz;
        $this->active = $record-> active;
        $this->in_catalog = $record-> in_catalog;
        $this->face_to_face_hours = $record-> face_to_face_hours;
        $this->teletraining_hours = $record-> teletraining_hours;
        $this->total_hours = $record-> total_hours;
        $this->price = $record-> price;
        $this->objectives = $record-> objectives;
        $this->content = $record-> content;
        $this->user = $record-> user;
        $this->password = $record-> pasword;
        $this->web_platform_id = $record-> web_platform_id;
        $this->observations = $record-> observations;
        $this->number_activities = $record-> number_activities;
        $this->number_units = $record-> number_units;
        $this->provider_id = $record-> provider_id;

        $this->route = url()->previous();
    }


    public function update()
    {
        $this->validate([
            'name' => 'required',
            'action_type_id' => 'required',
            'modality_id' => 'required|numeric|min:1',
            'training_action_level_id' => 'required',
            'tutoring_id' => 'required',
            'face_to_face_hours' => 'required',
            'teletraining_hours' => 'required',
            'price' => 'required'
        ]);

        $total_hours = $this-> face_to_face_hours + $this-> teletraining_hours;

        if ($this->selected_id) {
            $record = TrainingAction::find($this->selected_id);
            $record->update([
                'name' => $this-> name,
                'action_type_id' => $this-> action_type_id,
                'professional_family_id' => $this-> professional_family_id != -1 ? $this-> professional_family_id : null,
                'professional_area_id' => $this-> professional_area_id != -1 ? $this-> professional_area_id  : null,
                'modality_id' => $this-> modality_id,
                'training_action_level_id' => $this-> training_action_level_id,
                'training_action_group_id' => $this-> training_action_group_id != -1 ? $this-> training_action_group_id : null,
                'tutoring_id' => $this-> tutoring_id,
                'course_z' => $this-> course_z == true ? 1 : 0,
                'course_avz' => $this-> course_avz == true ? 1 : 0,
                'active' => $this-> active == true ? 1 : 0,
                'in_catalog' => $this-> in_catalog == true ? 1 : 0,
                'face_to_face_hours' => $this-> face_to_face_hours,
                'teletraining_hours' => $this-> teletraining_hours,
                'total_hours' => $total_hours,
                'price' => $this-> price,
                'objectives' => $this-> objectives,
                'content' => $this-> content,
                'user' => $this-> user,
                'password' => $this->password,
                'web_platform_id' => $this-> web_platform_id,
                'observations' => $this-> observations,
                'number_activities' => $this-> number_activities,
                'number_units' => $this-> number_units,
                'provider_id' => $this-> provider_id
            ]);
            session()->flash('message', 'Acción formativa actualizada con exito.');
            return $this->redirect($this->route);
        }
    }
    public function setTotalHours($face_to_face, $teletraining) {
        $this->total_hours = $face_to_face+$teletraining;
        $this->create_total_hours = $face_to_face+$teletraining;
    }

    public function action($id = null){
        if (!$id){
            $training = TrainingAction::orderBy('id', 'desc')->first();
            $id = $training['id']+1;
        }
        if ($id < 10) {
            $this->formative_action = '00'.$id;
        }
        else if ($id < 100) {
            $this->formative_action = '0'.$id;
        } else {
            $this->formative_action = $id;
        }
    }
}
