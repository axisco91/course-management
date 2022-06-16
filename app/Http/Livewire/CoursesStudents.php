<?php

namespace App\Http\Livewire;

use App\Models\Registration;
use Livewire\Component;
use Livewire\WithPagination;

class CoursesStudents extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $search_name, $search_surname, $search_dni;
    public $route;

    public function render()
    {
        $search_name = '%'.$this->search_name.'%';
        $search_surname = '%'.$this->search_surname.'%';
        $search_dni = '%'.$this->search_dni.'%';
        $this->students = Registration::getRegistrated($this->selected_id, $search_name, $search_surname, $search_dni);

        return view('livewire.courses.coursesStudents');
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    public function mount($id){
        $this->selected_id = $id;
    }
}
