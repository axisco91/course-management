<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\LevelStudy;
use App\Models\ProfessionalCategory;
use App\Models\Province;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;

class Students extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $inactiveFilter, $name, $surname, $dni, $telephone, $email, $company_id, $user, $date_of_birth, $level_study_id, $disabled, $social_security_number, $c_quote, $quote_group, $professional_category_id, $annual_gross_salary, $annual_hours, $hourly_cost_worker_gross, $direction, $post_code, $population_id, $province_id, $population, $observation, $iban, $password, $inactive;
    public $create_company_id, $create_level_study_id, $create_professional_category_id, $create_province_id;
    public $updateMode = false;
    public $companies, $level_studies, $professional_categories, $provinces;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';

        $students = Student::select('students.*', 'companies.name as company', 'level_studies.name as level_study',
            'professional_categories.name as professional_category', 'provinces.name as province')
            ->leftjoin('companies', 'companies.id', '=', 'students.company_id')
            ->leftjoin('level_studies', 'level_studies.id', '=', 'students.level_study_id')
            ->leftjoin('professional_categories', 'professional_categories.id', '=', 'students.professional_category_id')
            ->leftjoin('provinces', 'provinces.id', '=', 'students.province_id');
        if ($this->inactiveFilter != 1) {
            $students = $students->where('students.inactive', 0);
        }
        $students = $students->where(function ($query) use ($keyWord){
            $query->orWhere('students.name', 'LIKE', $keyWord)
                ->orWhere('surname', 'LIKE', $keyWord)
                ->orWhere('dni', 'LIKE', $keyWord)
                ->orWhere('companies.name', 'LIKE', $keyWord)
                ->orWhere('students.telephone', 'LIKE', $keyWord)
                ->orWhere('students.email', 'LIKE', $keyWord)
                ->orWhere('user', 'LIKE', $keyWord)
                ->orWhere('date_of_birth', 'LIKE', $keyWord)
                ->orWhere('level_studies.name', 'LIKE', $keyWord)
                ->orWhere('disabled', 'LIKE', $keyWord)
                ->orWhere('social_security_number', 'LIKE', $keyWord)
                ->orWhere('c_quote', 'LIKE', $keyWord)
                ->orWhere('quote_group', 'LIKE', $keyWord)
                ->orWhere('professional_categories.name', 'LIKE', $keyWord)
                ->orWhere('annual_gross_salary', 'LIKE', $keyWord)
                ->orWhere('annual_hours', 'LIKE', $keyWord)
                ->orWhere('hourly_cost_worker_gross', 'LIKE', $keyWord)
                ->orWhere('direction', 'LIKE', $keyWord)
                ->orWhere('students.post_code', 'LIKE', $keyWord)
                ->orWhere('provinces.name', 'LIKE', $keyWord)
                ->orWhere('students.population', 'LIKE', $keyWord)
                ->orWhere('students.iban', 'LIKE', $keyWord);
        })->orderBy('students.name','asc')
            ->paginate(10);

        return view('livewire.students.view', [
            'students' => $students,
        ]);
    }

    public function mount(){
        $this->companies = Company::where('inactive', 0)->get();
        $this->level_studies = LevelStudy::all();
        $this->professional_categories = ProfessionalCategory::all();
        $this->provinces = Province::all();
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
        $this->create_company_id = null;
        $this->create_level_study_id = null;
        $this->create_profesional_category_id = null;
        $this->create_province_id = null;
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
		'create_level_study_id' => 'required',
        'password' => 'required'
        ]);

        Student::create([
			'name' => $this-> name,
			'surname' => $this-> surname,
			'dni' => $this-> dni,
			'telephone' => $this-> telephone,
			'email' => $this-> email,
			'company_id' => $this-> create_company_id,
			'user' => $this-> user,
            'password' => $this-> password,
			'date_of_birth' => $this-> date_of_birth,
			'level_study_id' => $this-> create_level_study_id,
			'disabled' => $this-> disabled == true ? 1 : 0,
			'social_security_number' => $this-> social_security_number,
			'c_quote' => $this-> c_quote,
			'quote_group' => $this-> quote_group,
			'professional_category_id' => $this-> create_professional_category_id,
			'annual_gross_salary' => $this-> annual_gross_salary,
			'annual_hours' => $this-> annual_hours,
			'hourly_cost_worker_gross' => $this-> hourly_cost_worker_gross,
			'direction' => $this-> direction,
			'post_code' => $this-> post_code,
			'province_id' => $this-> create_province_id,
			'population' => $this-> population,
			'observation' => $this-> observation,
			'iban' => $this-> iban
        ]);

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Alumno creado con exito.');
    }

    public function edit($id)
    {
        $record = Student::findOrFail($id);

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
		$this->disabled = $record-> disabled;
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

        $this->updateMode = true;
    }

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
        ]);

        if ($this->selected_id) {
			$record = Student::find($this->selected_id);
            $record->update([
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
                'quote_group' => $this-> quote_group,
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
            ]);

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Alumno Actulizado con exito.');
        }
    }
    public function changeState($id){
        $student = Student::find($id);
        if ($student->inactive == 1){
            $student->update([
                'inactive' => 0
            ]);
            session()->flash('message', 'Alumno activado con exito.');
        } else {
            $student->update([
                'inactive' => 1
            ]);
            session()->flash('message', 'Alumno desactivado con exito.');
        }
    }

    public function general($id){
        if ($id){
            $record = Student::findOrFail($id);

            $this->name = $record-> name;
            $this->surname = $record-> surname;
            $this->dni = $record-> dni;
            $this->telephone = $record-> telephone;
            $this->email = $record-> email;
            $this->company_id = $record-> company_id;
            $this->user = $record-> user;
            $this->date_of_birth = $record-> date_of_birth;
            $this->level_study_id = $record-> level_study_id;
            $this->disabled = $record-> disabled;
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
        }
    }
}
