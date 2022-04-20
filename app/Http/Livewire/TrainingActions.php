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

class TrainingActions extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $teacher_id, $action_type_id, $professional_family_id, $professional_area_id,
        $modality_id, $training_action_level_id, $training_action_group_id, $tutoring_id, $course_z, $course_avz, $active = 1,
        $in_catalog = 1, $face_to_face_hours, $teletraining_hours, $total_hours, $price, $objectives, $content, $user,
        $web_platform_id, $observations, $number_activities, $number_units, $provider_id, $password;
    public $create_action_type_id, $create_professional_family_id, $create_professional_area_id, $create_modality_id, $create_training_action_level_id,
        $create_training_acion_group_id, $create_tutoring_id, $create_web_platform_id, $create_provider_id, $create_training_action_group_id;
    public $updateMode = false;
    public $action_types, $professional_families, $professional_areas, $modalities, $training_action_levels, $training_action_groups,
    $tutorings, $web_platforms, $providers;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $trainingActions = TrainingAction::
            select('training_actions.*',
            'action_types.name as action_type', 'professional_families.name as professional_family',
            'professional_areas.name as professional_area', 'modalities.name as modality',
            'training_action_levels.name as training_action_level', 'training_action_groups.name as training_action_group',
            'tutorings.name as tutoring', 'web_platforms.name as web_platform', 'providers.name as provider')
            ->leftjoin('action_types', 'action_types.id', '=', 'training_actions.action_type_id')
            ->leftjoin('professional_families', 'professional_families.id', '=', 'training_actions.professional_family_id')
            ->leftjoin('professional_areas', 'professional_areas.id', '=', 'training_actions.professional_area_id')
            ->leftjoin('modalities', 'modalities.id', '=', 'training_actions.modality_id')
            ->leftjoin('training_action_levels', 'training_action_levels.id', '=', 'training_actions.training_action_level_id')
            ->leftjoin('training_action_groups', 'training_action_groups.id', '=', 'training_actions.training_action_group_id')
            ->leftjoin('tutorings', 'tutorings.id', '=', 'training_actions.tutoring_id')
            ->leftjoin('web_platforms', 'web_platforms.id', '=', 'training_actions.web_platform_id')
            ->leftjoin('providers', 'providers.id', '=', 'training_actions.provider_id')
            ->orWhere('training_actions.name', 'LIKE', $keyWord)
            ->orWhere('action_types.name', 'LIKE', $keyWord)
            ->orWhere('professional_families.name', 'LIKE', $keyWord)
            ->orWhere('professional_areas.name', 'LIKE', $keyWord)
            ->orWhere('modalities.name', 'LIKE', $keyWord)
            ->orWhere('training_action_levels.name', 'LIKE', $keyWord)
            ->orWhere('training_action_groups.name', 'LIKE', $keyWord)
            ->orWhere('tutorings.name', 'LIKE', $keyWord)
            ->orWhere('course_z', 'LIKE', $keyWord)
            ->orWhere('course_avz', 'LIKE', $keyWord)
            ->orWhere('active', 'LIKE', $keyWord)
            ->orWhere('in_catalog', 'LIKE', $keyWord)
            ->orWhere('face_to_face_hours', 'LIKE', $keyWord)
            ->orWhere('teletraining_hours', 'LIKE', $keyWord)
            ->orWhere('total_hours', 'LIKE', $keyWord)
            ->orWhere('price', 'LIKE', $keyWord)
            ->orWhere('objectives', 'LIKE', $keyWord)
            ->orWhere('content', 'LIKE', $keyWord)
            ->orWhere('training_actions.user', 'LIKE', $keyWord)
            ->orWhere('web_platforms.name', 'LIKE', $keyWord)
            ->orWhere('training_actions.observations', 'LIKE', $keyWord)
            ->orWhere('number_activities', 'LIKE', $keyWord)
            ->orWhere('number_units', 'LIKE', $keyWord)
            ->orWhere('providers.name', 'LIKE', $keyWord)
            ->paginate(10);

        foreach ($trainingActions as $action) {
            if ($action['id'] < 10) {
                $action['name'] = '00'.$action['id'].' - '.$action['name'];
            }
            else if ($action['id'] < 100) {
                $action['name'] = '0'.$action['id'].' - '.$action['name'];
            } else {
                $action['name'] = $action['id'].' - '.$action['name'];
            }
        }

        return view('livewire.training-actions.view', [
            'trainingActions' => $trainingActions,
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
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
        $this->providers = Provider::all();
    }

    private function resetInput()
    {
		$this->name = null;
		$this->action_type_id = null;
		$this->professional_family_id = null;
		$this->professional_area_id = null;
		$this->modality_id = null;
		$this->training_action_level_id = null;
		$this->training_action_group_id = null;
		$this->tutoring_id = null;
		$this->course_z = null;
		$this->course_avz = null;
		$this->active = 1;
		$this->in_catalog = 1;
		$this->face_to_face_hours = null;
		$this->teletraining_hours = null;
		$this->total_hours = null;
		$this->price = null;
		$this->objectives = null;
		$this->content = null;
		$this->user = null;
		$this->web_platform_id = null;
		$this->observations = null;
		$this->number_activities = null;
		$this->number_units = null;
		$this->provider_id = null;
        $this->password = null;
        $this->create_action_type_id = null;
        $this->create_modality_id = null;
        $this->create_professional_area_id = null;
        $this->create_professional_family_id = null;
        $this->create_provider_id = null;
        $this->create_training_acion_group_id = null;
        $this->create_training_action_level_id = null;
        $this->create_tutoring_id = null;
        $this->create_web_platform_id = null;
    }

    public function store()
    {
        $this->validate([
		'name' => 'required',
		'create_action_type_id' => 'required',
		'create_professional_family_id' => 'required',
		'create_professional_area_id' => 'required',
		'create_modality_id' => 'required',
		'create_training_action_level_id' => 'required',
		'create_training_action_group_id' => 'required',
		'create_tutoring_id' => 'required',
		'face_to_face_hours' => 'required',
		'teletraining_hours' => 'required',
		'price' => 'required',
        ]);

        $total_hours = $this-> face_to_face_hours + $this-> teletraining_hours;

        TrainingAction::create([
			'name' => $this-> name,
			'action_type_id' => $this-> create_action_type_id,
			'professional_family_id' => $this-> create_professional_family_id,
			'professional_area_id' => $this-> create_professional_area_id,
			'modality_id' => $this-> create_modality_id,
			'training_action_level_id' => $this-> create_training_action_level_id,
			'training_action_group_id' => $this-> create_training_action_group_id,
			'tutoring_id' => $this-> create_tutoring_id,
			'course_z' => $this-> course_z,
			'course_avz' => $this-> course_avz,
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
			'web_platform_id' => $this-> create_web_platform_id,
			'observations' => $this-> observations,
			'number_activities' => $this-> number_activities,
			'number_units' => $this-> number_units,
			'provider_id' => $this-> create_provider_id
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Acción formativa creado con exito.');
    }

    public function edit($id)
    {
        $record = TrainingAction::findOrFail($id);

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

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'action_type_id' => 'required',
            'professional_family_id' => 'required',
            'professional_area_id' => 'required',
            'modality_id' => 'required|numeric|min:1',
            'training_action_level_id' => 'required',
            'training_action_group_id' => 'required',
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
                'professional_family_id' => $this-> professional_family_id,
                'professional_area_id' => $this-> professional_area_id,
                'modality_id' => $this-> modality_id,
                'training_action_level_id' => $this-> training_action_level_id,
                'training_action_group_id' => $this-> training_action_group_id,
                'tutoring_id' => $this-> tutoring_id,
                'course_z' => $this-> course_z,
                'course_avz' => $this-> course_avz,
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

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Acción formativa actualizada con exito.');
        }
    }
}
