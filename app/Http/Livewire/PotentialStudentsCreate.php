<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\LevelStudy;
use App\Models\PotentialStudent;
use App\Models\ProfessionalArea;
use App\Models\ProfessionalCategory;
use App\Models\ProfessionalFamily;
use App\Models\Province;
use App\Models\TrainingAction;
use Livewire\Component;

class PotentialStudentsCreate extends Component
{

    protected $paginationTheme = 'bootstrap';
    public $name,
        $surname,
        $dni,
        $telephone,
        $email,
        $company_name,
        $date_of_birth,
        $level_study_id,
        $disabled,
        $social_security_number,
        $professional_category_id,
        $direction,
        $post_code,
        $population_id,
        $province_id,
        $population,
        $training_action_id,
        $professional_family_id,
        $professional_area_id;
    public $updateMode = false;
    public $route;
    public $level_studies, $professional_categories, $provinces, $training_actions, $professional_families, $professional_areas;

    public function render()
    {
        return view('livewire.potential-students.create');
    }

    public function mount(){
        $this->companies = Company::where('active', 0)->get();
        $this->level_studies = LevelStudy::all();
        $this->professional_categories = ProfessionalCategory::all();
        $this->provinces = Province::all();
        $this->training_actions = TrainingAction::all();
        $this->professional_families = ProfessionalFamily::all();
        $this->professional_areas = ProfessionalArea::all();

        $this->route = url()->previous();
    }

    public function hydrate(){
        $this->emit('select2');
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    private function resetInput()
    {
        $this->name = null;
        $this->surname = null;
        $this->dni = null;
        $this->telephone = null;
        $this->email = null;
        $this->company_name = null;
        $this->date_of_birth = null;
        $this->level_study_id = null;
        $this->disabled = null;
        $this->social_security_number = null;
        $this->professional_category_id = null;
        $this->direction = null;
        $this->post_code = null;
        $this->population_id = null;
        $this->province_id = null;
        $this->population = null;
        $this->training_action_id = null;
        $this->professional_family_id = null;
        $this->professional_area_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'surname' => 'required',
            'dni' => 'required',
            'telephone' => 'required|numeric',
            'email' => 'required',
        ]);

        if ($this->dni){
            $dni = PotentialStudent::findDni($this->dni);
            if ($dni){
                $this->emit('alreadyExists', 'dni');
                return;
            }
        }

        $data = [
            'name' => $this-> name,
            'surname' => $this-> surname,
            'dni' => $this-> dni,
            'telephone' => $this-> telephone,
            'email' => $this-> email,
            'company_name' => $this-> company_name,
            'date_of_birth' => $this-> date_of_birth,
            'level_study_id' => $this-> level_study_id,
            'disabled' => $this-> disabled == true ? 1 : 0,
            'social_security_number' => $this-> social_security_number,
            'professional_category_id' => $this-> professional_category_id,
            'direction' => $this-> direction,
            'post_code' => $this-> post_code,
            'province_id' => $this-> province_id,
            'population' => $this-> population,
            'training_action_id' => $this->training_action_id,
            'professional_family_id' => $this->professional_family_id,
            'professional_area_id' => $this->professional_area_id
        ];

        $student = PotentialStudent::createPotentialStudent($data);

        $this->resetInput();
        session()->flash('message', 'Has creado la solicitud con exito.');
        return redirect($this->route);
    }
}
