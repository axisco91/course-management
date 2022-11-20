<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\LevelStudy;
use App\Models\PotentialStudent;
use App\Models\ProfessionalArea;
use App\Models\ProfessionalCategory;
use App\Models\ProfessionalFamily;
use App\Models\Province;
use App\Models\QuoteGroup;
use App\Models\Student;
use App\Models\TrainingAction;
use Livewire\Component;

class PotentialStudentsConvert extends Component
{

    protected $paginationTheme = 'bootstrap';
    public $name,
        $selected_id,
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
        $professional_area_id,
        $company_id,
        $user,
        $password,
        $c_quote,
        $quote_group_id,
        $annual_gross_salary,
        $annual_hours,
        $hourly_cost_worker_gross,
        $observation,
        $iban,
        $active;
    public $updateMode = false;
    public $route;
    public $level_studies, $companies, $professional_categories, $provinces, $training_actions, $professional_families, $professional_areas, $quote_groups;

    public function render()
    {
        return view('livewire.potential-students.convert');
    }

    public function mount($id){
        $this->companies = Company::where('active', 0)->get();
        $this->level_studies = LevelStudy::all();
        $this->professional_categories = ProfessionalCategory::all();
        $this->provinces = Province::all();
        $this->training_actions = TrainingAction::all();
        $this->professional_families = ProfessionalFamily::all();
        $this->professional_areas = ProfessionalArea::all();
        $record = PotentialStudent::find($id);
        $this->quote_groups = QuoteGroup::all();
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->surname = $record->surname;
        $this->dni = $record->dni;
        $this->telephone = $record->telephone;
        $this->email = $record->email;
        $this->company_name = $record->company_name;
        $this->date_of_birth = $record->date_of_birth;
        $this->level_study_id = $record->level_study_id;
        $this->disabled = $record->disabled;
        $this->social_security_number = $record->social_security_number;
        $this->professional_category_id = $record->professional_category_id;
        $this->direction = $record->direction;
        $this->post_code = $record->post_code;
        $this->population_id = $record->population_id;
        $this->province_id = $record->province_id;
        $this->population = $record->population;
        $this->training_action_id = $record->training_action_id;
        $this->professional_family_id = $record->professional_familiy_id;
        $this->professional_area_id = $record->professional_area_id;

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
        $this->company_id = null;
        $this->user = null;
        $this->password = null;
        $this->c_quote = null;
        $this->quote_group_id = null;
        $this->annual_gross_salary = null;
        $this->annual_hours = null;
        $this->hourly_cost_worker_gross = null;
        $this->observation = null;
        $this->iban = null;
        $this->active = null;
    }

    public function convert()
    {
        $this->validate([
            'name' => 'required',
            'surname' => 'required',
            'dni' => 'required',
            'telephone' => 'required|numeric',
            'email' => 'required',
            'user' => 'required',
            'level_study_id' => 'required',
            'password' => 'required',
            'company_id' => 'required'
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
            'professional_area_id' => $this->professional_area_id,
            'company_id' => $this->company_id,
            'user' => $this->user,
            'password' => $this->password,
            'c_quote' => $this->c_quote,
            'quote_group_id' => $this->quote_group_id,
            'annual_gross_salary' => $this->annual_gross_salary,
            'annual_hours' => $this->annual_hours,
            'hourly_cost_worker_gross' => $this->hourly_cost_worker_gross,
            'observation' => $this->observation,
            'iban' => $this->iban,
            'active' => $this->active
        ];
        PotentialStudent::convertPotentialStudent($this->selected_id);
        $student = Student::createStudent($data);

        $this->resetInput();
        session()->flash('message', 'Has creado la solicitud con exito.');
        return redirect($this->route);
    }
}
