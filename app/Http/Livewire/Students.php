<?php

namespace App\Http\Livewire;

use App\Exports\StudentsExport;
use App\Models\Company;
use App\Models\LevelStudy;
use App\Models\ProfessionalCategory;
use App\Models\Province;
use App\Models\QuoteGroup;
use App\Models\Registration;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;

class Students extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $inactiveFilter, $name, $surname, $dni, $telephone, $email, $company_id, $user, $date_of_birth,
        $level_study_id, $disabled, $social_security_number, $c_quote, $quote_group_id, $professional_category_id, $annual_gross_salary,
        $annual_hours, $hourly_cost_worker_gross, $direction, $post_code, $population_id, $province_id, $population, $observation, $iban,
        $password, $active, $courses, $course, $tab = 'info';
    public $companies, $level_studies, $professional_categories, $provinces, $quote_groups;
    public $search_name, $search_surname, $search_email, $search_dni, $search_telephone, $search_company, $search_course_name, $search_group;

    protected $listeners = [
        'changeState' => 'changeState',
        'destroy' => 'destroy'
    ];

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $search_name = '%'.$this->search_name.'%';
        $search_surname = '%'.$this->search_surname.'%';
        $search_email = '%'.$this->search_email.'%';
        $search_dni = '%'.$this->search_dni.'%';
        $search_telephone = '%'.$this->search_telephone.'%';
        $search_company = $this->search_company;

        if ($this->selected_id){
            $search_course_name = '%'.$this->search_course_name.'%';
            $search_group = '%'.$this->search_group.'%';
            $this->courses = Registration::getStudentCourses($this->selected_id, $search_course_name, $search_group);
        }

       $students = Student::getStudents($keyWord, $this->inactiveFilter, $search_name, $search_surname, $search_email, $search_dni, $search_telephone, $search_company);

        foreach ($students as $student) {
            $registration = Registration::where('student_id', $student->id)->first();
            if ($registration){
                $student['used'] = true;
            } else {
                $student['used'] = false;
            }
        }

        return view('livewire.students.list', [
            'students' => $students,
        ]);
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

    public function mount(){
        $this->companies = Company::where('active', 1)->get();
        $this->level_studies = LevelStudy::all();
        $this->professional_categories = ProfessionalCategory::all();
        $this->provinces = Province::all();
        $this->quote_groups = QuoteGroup::all();
    }

    public function hydrate(){
        $this->emit('select2');
    }

    public function changeState($id){
        $student = new Student;
        $record = $student->getStudent($id);
        if ($record->active == 1){
            $student->activeInactive($id, 0);
            session()->flash('message', 'Alumno desactivado con exito.');
            $value = 'state-update';
        } else {
            $student->activeInactive($id, 1);
            session()->flash('message', 'Alumno activado con exito.');
            $value = 'activated';
        }
        $this->dispatchBrowserEvent('status-update', ['value' => $value]);
    }

    public function general($id){
        if ($id){
            $record = Student::getStudent($id);
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
            $this->quote_group_id = $record-> quote_group_id;
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

    public function downloadExcel(){
        return (new StudentsExport($this->search_name, $this->search_surname, $this->search_email, $this->search_dni, $this->search_telephone, $this->search_company, $this->inactiveFilter))->download('docentes.xlsx');
    }

    public function destroy($id)
    {
        if ($id) {
            $registration = Registration::where('student_id', $id)->first();
            if ($registration){

            } else {
                Student::destroy($id);
            }
        }
    }

}
