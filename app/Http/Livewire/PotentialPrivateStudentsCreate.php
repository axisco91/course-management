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

class PotentialPrivateStudentsCreate extends Component
{

    protected $paginationTheme = 'bootstrap';
    public $name,
        $surname,
        $dni,
        $telephone,
        $email,
        $date_of_birth,
        $level_study_id,
        $disabled,
        $direction,
        $post_code,
        $population_id,
        $province_id,
        $population,
        $comment;
    public $updateMode = false;
    public $route;
    public $level_studies, $professional_categories, $provinces;

    public function render()
    {
        return view('livewire.potential-students.create-private-student');
    }

    public function mount(){
        $this->level_studies = LevelStudy::all();
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
        $this->date_of_birth = null;
        $this->level_study_id = null;
        $this->disabled = null;
        $this->direction = null;
        $this->post_code = null;
        $this->population_id = null;
        $this->province_id = null;
        $this->population = null;
        $this->comment = null;
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
            'date_of_birth' => $this-> date_of_birth,
            'level_study_id' => $this-> level_study_id,
            'disabled' => $this-> disabled == true ? 1 : 0,
            'direction' => $this-> direction,
            'post_code' => $this-> post_code,
            'province_id' => $this-> province_id,
            'population' => $this-> population,
            'comment' => $this->comment
        ];

        $student = PotentialStudent::createPotentialStudent($data);

        $this->resetInput();
        session()->flash('message', 'Has creado la solicitud con exito.');
        return redirect('potential_private_student/finalized');
    }
}
