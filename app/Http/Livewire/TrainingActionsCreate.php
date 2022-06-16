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

class TrainingActionsCreate extends Component
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
    public $route;

    public function render()
    {

        return view('livewire.training-actions.create');
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

        $id = null;
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
        $this->route = url()->previous();
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

    public function store()
    {
        $this->validate([
		'name' => 'required',
		'action_type_id' => 'required',
		'modality_id' => 'required',
		'training_action_level_id' => 'required',
		'tutoring_id' => 'required',
		'face_to_face_hours' => 'required',
		'teletraining_hours' => 'required',
		'price' => 'required',
        ]);

        $total_hours = $this-> face_to_face_hours + $this-> teletraining_hours;

        TrainingAction::create([
			'name' => $this-> name,
            'formative_action' => $this-> formative_action,
			'action_type_id' => $this-> action_type_id,
			'professional_family_id' => $this-> professional_family_id != -1 ? $this-> professional_family_id : null,
			'professional_area_id' => $this-> professional_area_id != -1 ? $this-> professional_area_id : null,
			'modality_id' => $this-> modality_id,
			'training_action_level_id' => $this-> training_action_level_id,
			'training_action_group_id' => $this-> training_action_group_id != -1 ? $this-> training_action_group_id :null,
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

        $this->resetInput();
		session()->flash('message', 'Acción formativa creado con exito.');
        return redirect($this->route);
    }

    public function setTotalHours($face_to_face, $teletraining) {
        $this->total_hours = $face_to_face+$teletraining;
    }
}
