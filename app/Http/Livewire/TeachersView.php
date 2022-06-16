<?php

namespace App\Http\Livewire;

use App\Models\AreasTeacherArea;
use App\Models\Province;
use App\Models\Teacher;
use App\Models\TeacherArea;
use Livewire\Component;
use function session;
use function view;

class TeachersView extends Component
{

    public $selected_id, $name, $surname, $dni, $email, $telephone, $user, $password, $observations, $iban, $address,
        $post_code, $province_id, $population, $teacher_areas, $teacher_area_id, $inactive, $route;

    public function render()
    {
        return view('livewire.teachers.view');
    }

    public function mount($id){
        $this->provinces = Province::all();
        $this->teacher_areas = TeacherArea::all();

        $record = Teacher::findOrFail($id);

        $this->selected_id = $id;
        $this->name = $record-> name;
        $this->surname = $record-> surname;
        $this->dni = $record-> dni;
        $this->email = $record-> email;
        $this->telephone = $record-> telephone;
        $this->user = $record-> user;
        $this->password = $record-> password;
        $this->observations = $record-> observations;
        $this->iban = $record-> iban;
        $this->address = $record-> address;
        $this->post_code = $record-> post_code;
        $this->province_id = $record-> province_id;
        $this->population = $record-> population;
        $teachers_areas = AreasTeacherArea::where('teacher_id', $id)->get();
        $area = [];
        foreach ($teachers_areas as $teacher_area){
            array_push($area, $teacher_area['teacher_area_id']);
        }
        $this->teacher_area_id = $area;

        $this->route = url()->previous();
    }

    public function hydrate(){
        $this->emit('select2');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'surname' => 'required',
            'dni' => 'required',
            'email' => 'required',
            'user' => 'required',
        ]);

        if ($this->selected_id) {
            $record = Teacher::find($this->selected_id);
            $record->update([
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
                'population' => $this-> population
            ]);

            if (!empty($this->teacher_area_id)){
                $record->teacherAreas()->sync($this->teacher_area_id);
            }
            session()->flash('message', 'Docente Actulizado con exito.');
            return redirect($this->route);
        }
    }
}
