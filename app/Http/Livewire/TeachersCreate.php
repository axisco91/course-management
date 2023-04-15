<?php

namespace App\Http\Livewire;

use App\Models\Province;
use App\Models\Teacher;
use App\Models\TeacherArea;
use Livewire\Component;
use function session;
use function view;

class TeachersCreate extends Component
{
    public $name, $surname, $dni, $email, $telephone, $user, $password, $observations, $iban, $address,
        $post_code, $province_id, $population, $teacher_areas, $teacher_area_id, $active;

    public function render()
    {
        return view('livewire.teachers.create');
    }

    public function mount(){
        $this->provinces = Province::all();
        $this->teacher_areas = TeacherArea::all();
    }

    public function hydrate(){
        $this->emit('select2');
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

        if ($this->dni){
            $dni = Teacher::findDni($this->dni);
            if ($dni){
                $this->emit('alreadyExists', 'dni');
                return;
            }
        }
        if ($this->user){
            $user = Teacher::findUser($this->user);
            if ($user){
                $this->emit('alreadyExists', 'user');
                return;
            }
        }

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
        session()->flash('message', 'Docente creado con exito.');
        $this->emit('toastr', 'success');
        return redirect('teachers/edt/'.$teacher->id);
    }
}
