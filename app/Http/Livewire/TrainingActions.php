<?php

namespace App\Http\Livewire;

use App\Models\ActionType;
use App\Models\Course;
use App\Models\Modality;
use App\Models\ProfessionalArea;
use App\Models\ProfessionalFamily;
use App\Models\Provider;
use App\Models\TrainingActionGroup;
use App\Models\TrainingActionLevel;
use App\Models\Tutoring;
use App\Models\WebPlatform;
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
    $tutorings, $web_platforms, $providers, $tab = 'info';
    public $search_formative_actions, $search_name, $search_course_name, $search_course_group, $courses;
    protected $listeners = [
        'changeState' => 'changeState'
    ];

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $search_formative_actions = '%'.$this->search_formative_actions.'%';
        $search_name = '%'.$this->search_name.'%';
        $trainingActions = TrainingAction::getTrainingActions($keyWord,$this->inactiveFilter, $search_formative_actions, $search_name);

        if ($this->selected_id){
            $search_course_name = '%'.$this->search_course_name.'%';
            $search_course_group = '%'.$this->search_course_group.'%';
            $this->courses = Course::getTrainingActionCourse($this->selected_id, $search_course_name, $search_course_group);
        }

        return view('livewire.training-actions.list', [
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
            $value = 'activated';
        } else {
            $trainingActions->update([
                'active' => 0
            ]);
            session()->flash('message', 'Acción formativa desactivado con exito.');
            $value = 'desactivated';
        }
        $this->dispatchBrowserEvent('status-update', ['value' => $value]);
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
