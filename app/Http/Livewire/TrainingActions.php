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
    public $selected_id, $keyWord, $inactiveFilter, $name, $teacher_id, $action_type_id, $professional_family_id, $professional_area_id,
        $modality_id, $training_action_level_id, $training_action_group_id, $tutoring_id, $course_z, $course_avz, $active = 1,
        $in_catalog = 1, $face_to_face_hours, $teletraining_hours, $total_hours, $price, $objectives, $content, $user,
        $web_platform_id, $observations, $number_activities, $number_units, $provider_id, $password, $formative_action;
    public $updateMode = false;
    public $action_types, $professional_families, $professional_areas, $modalities, $training_action_levels, $training_action_groups,
    $tutorings, $web_platforms, $providers;
    public $search_formative_actions, $search_name;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $search_formative_actions = '%'.$this->search_formative_actions.'%';
        $search_name = '%'.$this->search_name.'%';
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
            ->leftjoin('providers', 'providers.id', '=', 'training_actions.provider_id');
        if ($this->inactiveFilter != 1) {
            $trainingActions = $trainingActions->where('active', 1);
        }
        $trainingActions = $trainingActions->where(function ($query) use ($keyWord){
            $query->orWhere('training_actions.name', 'LIKE', $keyWord)
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
                ->orWhere('providers.name', 'LIKE', $keyWord);
        })->where(function ($query) use ($search_formative_actions){
            $query->orWhere('formative_action', 'LIKE', $search_formative_actions);
        })->where(function ($query) use ($search_name){
            $query->orWhere('training_actions.name', 'LIKE', $search_name);
        })->orderby('id', 'asc')
            ->paginate(10);

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
        $this->providers = Provider::select('providers.*')
            ->join('companies', 'companies.id', '=', 'providers.company_id')
            ->where('companies.active', 1)->get();
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
        $this->formative_action = null;
    }

    public function changeState($id){
        $trainingActions = TrainingAction::find($id);
        if ($trainingActions->active == 0){
            $trainingActions->update([
                'active' => 1
            ]);
            session()->flash('message', 'Acción formativa activado con exito.');
        } else {
            $trainingActions->update([
                'active' => 0
            ]);
            session()->flash('message', 'Acción formativa desactivado con exito.');
        }
    }
    public function general($id)
    {
        $record = TrainingAction::findOrFail($id);

        $this->selected_id = $id;
        $this->formative_action = $record-> formative_action;
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
    }

    public function getInfo($id){
        $this->emit('getTrainingActionInfo', $id);
        $training_Action = TrainingAction::find($id);
        $this->formative_action = $training_Action->formative_action;
        $this->name = $training_Action->name;
        $this->selected_id = $id;
    }

    public function editAction($id){
        $this->emit('editTrainingAction', $id);
    }
}
