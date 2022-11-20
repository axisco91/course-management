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
use Livewire\WithPagination;
use App\Mail\PotentialStudent as PotentialEmail;

class PotentialStudents extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $surname, $dni, $telephone, $email, $date_of_birth,
        $level_study_id, $disabled, $social_security_number, $professional_category_id, $company_name,
        $direction, $post_code, $population_id, $province_id, $population, $training_action_id, $professional_family_id, $professional_area_id, $courses, $tab = 'info';
    public $level_studies, $professional_categories, $provinces, $training_actions, $professional_areas, $professional_families;
    public $search_name, $search_surname, $search_email, $search_dni, $search_telephone, $send_form;

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

       $students = PotentialStudent::getPotentialStudents($keyWord, $search_name, $search_surname, $search_email, $search_dni, $search_telephone);

        return view('livewire.potential-students.list', [
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
        $this->send_form = null;
    }

    public function mount(){
        $this->companies = Company::where('active', 0)->get();
        $this->level_studies = LevelStudy::all();
        $this->professional_categories = ProfessionalCategory::all();
        $this->provinces = Province::all();
        $this->training_actions = TrainingAction::all();
        $this->professional_families = ProfessionalFamily::all();
        $this->professional_areas = ProfessionalArea::all();
    }

    public function hydrate(){
        $this->emit('select2');
    }

    public function general($id){
        if ($id){
            $record = PotentialStudent::getPotentialStudent($id);
            $this->selected_id = $id;
            $this->name = $record-> name;
            $this->surname = $record-> surname;
            $this->dni = $record-> dni;
            $this->telephone = $record-> telephone;
            $this->email = $record-> email;
            $this->company_name = $record-> company_name;
            $this->date_of_birth = $record-> date_of_birth;
            $this->level_study_id = $record-> level_study_id;
            $this->disabled = $record-> disabled;
            $this->social_security_number = $record-> social_security_number;
            $this->professional_category_id = $record-> professional_category_id;
            $this->direction = $record-> direction;
            $this->post_code = $record-> post_code;
            $this->population_id = $record-> population_id;
            $this->province_id = $record-> province_id;
            $this->population = $record-> population;
            $this->professional_area_id = $record-> professional_area_id;
            $this->training_action_id = $record-> training_action_id;
            $this->professional_family_id = $record-> professional_family_id;
        }
    }

    public function sendEmail(){
        new PotentialEmail($this->send_form);
        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'Correo enviado con exito.');
        $this->emit('toastr', 'success');
    }

    public function destroy($id)
    {
        if ($id) {
            PotentialStudent::destroy($id);
        }
    }

}
