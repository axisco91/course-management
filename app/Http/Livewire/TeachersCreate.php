<?php

namespace App\Http\Livewire;

use App\Models\AreasTeacherArea;
use App\Models\Province;
use App\Models\Teacher;
use App\Models\TeacherArea;
use Livewire\Component;
use Livewire\WithPagination;
use function session;
use function view;

class TeachersCreate extends Component
{
    protected $paginationTheme = 'bootstrap';
    public $name, $surname, $dni, $email, $telephone, $user, $password, $observations, $iban, $address,
        $post_code, $province_id, $population, $teacher_areas, $teacher_area_id, $active;
    public $route;
    public $updateMode = false;

    public function render()
    {
        return view('livewire.teachers.create');
    }

    public function mount(){
        $this->provinces = Province::all();
        $this->teacher_areas = TeacherArea::all();

        $this->route = url()->previous();
    }

    public function hydrate(){
        $this->emit('select2');
    }

    private function resetInput()
    {
        $this->name = null;
        $this->surname = null;
        $this->dni = null;
        $this->email = null;
        $this->telephone = null;
        $this->user = null;
        $this->password = null;
        $this->observations = null;
        $this->iban = null;
        $this->address = null;
        $this->post_code = null;
        $this->province_id = null;
        $this->population = null;
        $this->teacher_area_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'surname' => 'required',
            'dni' => 'required',
            'email' => 'required',
            'user' => 'required',
            'password' => 'required'
        ]);

        $teacher = Teacher::create([
            'name' => $this-> name,
            'surname' => $this-> surname,
            'dni' => $this-> dni,
            'email' => $this-> email,
            'telephone' => $this-> telephone,
            'user' => $this-> user,
            'password' => $this-> password,
            'observations' => $this-> observations,
            'iban' => $this-> iban,
            'address' => $this-> address,
            'post_code' => $this-> post_code,
            'province_id' => $this-> province_id,
            'population' => $this-> population,
        ]);
        if (!empty($this->teacher_area_id)){
            $teacher->teacherAreas()->sync($this->teacher_area_id);
        }

        $this->resetInput();
        session()->flash('message', 'Docente creado con exito.');
        return redirect($this->route);
    }
}
