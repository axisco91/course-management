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
        $post_code, $province_id, $population, $teacher_areas, $teacher_area_id, $inactive;
    public $create_province_id, $create_teacher_area_id = [];
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';

        $teachers = Teacher::select('*');
        if ($this->inactiveFilter != 1) {
            $teachers = $teachers->where('inactive', 0);
        }
        $teachers = $teachers->where(function ($query) use ($keyWord){
            $query->orWhere('name', 'LIKE', $keyWord)
                ->orWhere('surname', 'LIKE', $keyWord)
                ->orWhere('dni', 'LIKE', $keyWord)
                ->orWhere('email', 'LIKE', $keyWord)
                ->orWhere('telephone', 'LIKE', $keyWord)
                ->orWhere('user', 'LIKE', $keyWord)
                ->orWhere('password', 'LIKE', $keyWord)
                ->orWhere('observations', 'LIKE', $keyWord)
                ->orWhere('iban', 'LIKE', $keyWord);
        })->orderBy('name','desc')
            ->paginate(10);

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

    public function store()
    {
        $this->validate([
		'name' => 'required',
		'surname' => 'required',
		'dni' => 'required',
		'email' => 'required',
		'user' => 'required',
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
            'province_id' => $this-> create_province_id,
            'population' => $this-> population,
        ]);
        if (!empty($this->create_teacher_area_id)){
        $teacher->teacherAreas()->sync($this->create_teacher_area_id);
        }

        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Docente creado con exito.');
    }

    public function edit($id)
    {
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

        $this->updateMode = true;
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

            $this->resetInput();
            $this->updateMode = false;
			session()->flash('message', 'Docente Actulizado con exito.');
        }
    }

    public function changeState($id){
        $teacher = Teacher::find($id);
        if ($teacher->inactive == 1){
            $teacher->update([
                'inactive' => 0
            ]);
            session()->flash('message', 'Docente activado con exito.');
        } else {
            $teacher->update([
                'inactive' => 1
            ]);
            session()->flash('message', 'Docente desactivado con exito.');
        }
    }
}
