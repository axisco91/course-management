<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\LevelStudy;
use App\Models\ProfessionalCategory;
use App\Models\Province;
use App\Models\QuoteGroup;
use Livewire\Component;
use App\Models\Student;

class StudentsCreate extends Component
{

    protected $paginationTheme = 'bootstrap', $listeners = ['calculateHourlyCost'];
    public $name, $surname, $dni, $telephone, $email, $company_id, $user, $date_of_birth, $level_study_id, $disabled, $social_security_number, $c_quote, $quote_group_id, $professional_category_id, $annual_gross_salary, $annual_hours, $hourly_cost_worker_gross, $direction, $post_code, $population_id, $province_id, $population, $observation, $iban, $password, $inactive;
    public $companies, $level_studies, $professional_categories, $provinces, $quote_groups;

    public function render()
    {
        return view('livewire.students.create');
    }

    public function mount(){
        $this->companies = Company::where('active', 1)->get();
        $this->level_studies = LevelStudy::all();
        $this->professional_categories = ProfessionalCategory::all();
        $this->provinces = Province::all();
        $this->quote_groups = QuoteGroup::all();

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
        $this->company_id = null;
        $this->user = null;
        $this->date_of_birth = null;
        $this->level_study_id = null;
        $this->disabled = null;
        $this->social_security_number = null;
        $this->c_quote = null;
        $this->quote_group = null;
        $this->professional_category_id = null;
        $this->annual_gross_salary = null;
        $this->annual_hours = null;
        $this->hourly_cost_worker_gross = null;
        $this->direction = null;
        $this->post_code = null;
        $this->population_id = null;
        $this->province_id = null;
        $this->population = null;
        $this->observation = null;
        $this->iban = null;
        $this->password = null;
        $this->quote_group_id = null;
    }

    public function store()
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
            'company_id' => 'required',
        ]);

        if ($this->dni){
            $dni = Student::findDni($this->dni);
            if ($dni){
                $this->emit('alreadyExists', 'dni');
                return;
            }
        }
        if ($this->user){
            $user = Student::findUser($this->user);
            if ($user){
                $this->emit('alreadyExists', 'user');
                return;
            }
        }

        $data = [
            'name' => $this-> name,
            'surname' => $this-> surname,
            'dni' => $this-> dni,
            'telephone' => $this-> telephone,
            'email' => $this-> email,
            'company_id' => $this-> company_id,
            'user' => $this-> user,
            'password' => $this-> password,
            'date_of_birth' => $this-> date_of_birth,
            'level_study_id' => $this-> level_study_id,
            'disabled' => $this-> disabled == true ? 1 : 0,
            'social_security_number' => $this-> social_security_number,
            'c_quote' => $this-> c_quote,
            'quote_group_id' => $this-> quote_group_id,
            'professional_category_id' => $this-> professional_category_id,
            'annual_gross_salary' => $this-> annual_gross_salary,
            'annual_hours' => $this-> annual_hours,
            'hourly_cost_worker_gross' => $this-> hourly_cost_worker_gross,
            'direction' => $this-> direction,
            'post_code' => $this-> post_code,
            'province_id' => $this-> province_id,
            'population' => $this-> population,
            'observation' => $this-> observation,
            'iban' => $this-> iban
        ];

        $student = Student::createStudent($data);
        session()->flash('message', 'Alumno creado con exito.');
        $this->emit('toastr', 'success');
        return redirect('/students/edit/'.$student->id);
    }
    public function calculateHourlyCost (){
        if ($this->annual_gross_salary == 0 && $this->annual_hours == 0){
            $this->hourly_cost_worker_gross = 0;
        } else if ($this->annual_gross_salary != '' && $this->annual_hours != ''){
            $this->annual_gross_salary = str_replace(',', '.', $this->annual_gross_salary);
            $this->annual_hours = str_replace(',', '.', $this->annual_hours);
            $this->hourly_cost_worker_gross = $this->annual_gross_salary / $this->annual_hours;
        }
    }
}
