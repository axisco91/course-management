<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\LevelStudy;
use App\Models\ProfessionalCategory;
use App\Models\Province;
use App\Models\QuoteGroup;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;

class StudentsUpdate extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap', $listeners = ['studentsUpdated' => 'studentsUpdated'];
    public $selected_id, $keyWord, $inactiveFilter, $name, $surname, $dni, $telephone, $email, $company_id, $user, $date_of_birth, $level_study_id, $disabled, $social_security_number, $c_quote, $quote_group_id, $professional_category_id, $annual_gross_salary, $annual_hours, $hourly_cost_worker_gross, $direction, $post_code, $population_id, $province_id, $population, $observation, $iban, $password, $inactive;
    public $route;
    public $companies, $level_studies, $professional_categories, $provinces, $quote_groups;

    public function render()
    {
        return view('livewire.students.update');
    }

    public function mount($id){
        $this->companies = Company::where('inactive', 0)->get();
        $this->level_studies = LevelStudy::all();
        $this->professional_categories = ProfessionalCategory::all();
        $this->provinces = Province::all();
        $this->quote_groups = QuoteGroup::all();

        // Obtain student
        $student = new Student();
        $record = $student->getStudent($id);

        $this->selected_id = $id;
        $this->name = $record-> name;
        $this->surname = $record-> surname;
        $this->dni = $record-> dni;
        $this->telephone = $record-> telephone;
        $this->email = $record-> email;
        $this->company_id = $record-> company_id;
        $this->user = $record-> user;
        $this->date_of_birth = $record-> date_of_birth;
        $this->level_study_id = $record-> level_study_id;
        $this->disabled = $record-> disabled == true ? 1 : 0;
        $this->social_security_number = $record-> social_security_number;
        $this->c_quote = $record-> c_quote;
        $this->quote_group = $record-> quote_group;
        $this->professional_category_id = $record-> professional_category_id;
        $this->annual_gross_salary = $record-> annual_gross_salary;
        $this->annual_hours = $record-> annual_hours;
        $this->hourly_cost_worker_gross = $record-> hourly_cost_worker_gross;
        $this->direction = $record-> direction;
        $this->post_code = $record-> post_code;
        $this->population_id = $record-> population_id;
        $this->province_id = $record-> province_id;
        $this->population = $record-> population;
        $this->observation = $record-> observation;
        $this->iban = $record-> iban;
        $this->password = $record-> password;
        $this->quote_group_id = $record-> quote_group_id;

        $this->route = url()->previous();
    }

    public function hydrate(){
        $this->emit('select2');
    }

    /**
     * @return Update Student
     */
    public function update()
    {
        $this->validate([
            'name' => 'required',
            'surname' => 'required',
            'dni' => 'required',
            'telephone' => 'required',
            'email' => 'required',
            'user' => 'required',
            'level_study_id' => 'required',
            'password' => 'required',
            'company_id' => 'required'
        ]);

        if ($this->selected_id) {
            if ($this->dni){
                $dni = Student::findDni($this->dni, $this->selected_id);
                if ($dni){
                    $this->emit('alreadyExists', 'dni');
                    return;
                }
            }
            if ($this->user){
                $user = Student::findUser($this->user, $this->selected_id);
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
                'population_id' => $this-> population_id,
                'province_id' => $this-> province_id,
                'population' => $this-> population,
                'observation' => $this-> observation,
                'iban' => $this-> iban
            ];

            $student = Student::updateStudent($this->selected_id, $data);

            session()->flash('message', 'Alumno Actulizado con exito.');
            return redirect($this->route);
        } else {
            session()->flash('error', 'Alumno Actulizado sin exito.');
        }
    }
}
