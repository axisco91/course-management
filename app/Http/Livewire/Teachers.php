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

class Teachers extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $inactiveFilter, $name, $surname, $dni, $email, $telephone, $user, $password, $observations, $iban, $address,
        $post_code, $province_id, $population, $teacher_areas, $teacher_area_id, $active;
    public $updateMode = false;
    public $search_name, $search_surname, $search_email, $search_dni, $search_telephone;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        $search_name = '%'.$this->search_name.'%';
        $search_surname = '%'.$this->search_surname.'%';
        $search_email = '%'.$this->search_email.'%';
        $search_dni = '%'.$this->search_dni.'%';
        $search_telephone = '%'.$this->search_telephone.'%';

        $teachers = Teacher::getTeachers($keyWord, $this->inactiveFilter, $search_name, $search_surname, $search_email, $search_dni, $search_telephone);

        return view('livewire.teachers.view', [
            'teachers' => $teachers,
        ]);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    public function mount(){
        $this->provinces = Province::all();
        $this->teacher_areas = TeacherArea::all();
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
        $this->create_province_id = null;
        $this->create_teacher_area_id = null;
    }

    public function changeState($id){
        $teacher = Teacher::find($id);
        if ($teacher->active == 1){
            $teacher->update([
                'active' => 0
            ]);
            session()->flash('message', 'Docente activado con exito.');
        } else {
            $teacher->update([
                'active' => 1
            ]);
            session()->flash('message', 'Docente desactivado con exito.');
        }
    }

    public function general($id){
        if ($id){

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
        }
    }
}
